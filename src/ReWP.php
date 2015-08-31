<?php namespace amekusa\WPVagrantize;

use Ifsnop\Mysqldump\Mysqldump;

class ReWP {
	private $path;
	private $parser;
	private $data;
	private $user;

	public function __construct($xPath = null) {
		$this->path = $xPath ? $xPath : __DIR__;
		$this->parser = new \Spyc();
		$this->parser->setting_dump_force_quotes = true;
		$this->updateData();
	}

	public function reset() {
		$dataSrcFile = $this->path . '/site.yml';
		if (!file_exists($dataSrcFile)) return true;
		return unlink($dataSrcFile);
	}

	public function getPath() {
		return $this->path;
	}

	public function getParser() {
		return $this->parser;
	}

	public function getData($xKey = null) {
		if (isset($xKey) && is_array($this->data))
			return array_key_exists($xKey, $this->data) ? $this->data[$xKey] : null;

		return $this->data;
	}

	public function getUser() {
		if (!$this->user) $this->user = wp_get_current_user();
		return $this->user;
	}

	public function getSiteData() {

		$r = array ( // @formatter:off
			'hostname_old' => gethostname(),
			'version' => get_bloginfo('version'),
			'lang' => get_locale(),
			'title' => get_bloginfo('name'),
			'multisite' => is_multisite(),
			'admin_user' => $this->getUser()->user_login,
			'db_prefix' => $GLOBALS['wpdb']->prefix,
			'db_host' => DB_HOST,
			'db_name' => DB_NAME,
			'db_user' => DB_USER,
			'db_pass' => DB_PASSWORD,
			'theme' => wp_get_theme()->get_stylesheet(),
			'import_sql' => $this->getUser()->has_cap('import'),
		); // @formatter:on

		$r['plugins'] = get_option('active_plugins');
		foreach ($r['plugins'] as $i => $iP) $r['plugins'][$i] = explode('/', $iP)[0];

		return $r;
	}

	public function setData($xData) {
		$data = $this->sanitizeData($xData);
		$this->data = array_merge($this->data, $data);
		return $this->data;
	}

	public function updateData() {
		$src = file_get_contents($this->path . '/provision/default.yml');
		$this->data = $this->parser->load($this->sanitizeDataSource($src));

		$data = null;
		$dataSrcFile = $this->path . '/site.yml';
		if (!file_exists($dataSrcFile)) $data = $this->getSiteData();
		else {
			$src = file_get_contents($dataSrcFile);
			$data = $this->parser->load($this->sanitizeDataSource($src));
		}
		$this->setData($data);
	}

	public function sanitizeDataSource($xDataSource) {
		$r = $xDataSource;
		$r = preg_replace('/\s*#.*$/m', '', $r); // Remove comments
		return $r;
	}

	public function sanitizeData($xData) {
		$r = array ();

		foreach ($this->data as $i => $iData) {
			if (!array_key_exists($i, $xData)) continue;

			// Sanitize by types

			if (is_bool($iData)) { // Boolean

				if (!is_bool($xData[$i])) { // Type mismatch, but…

					// String values: "false", "true" are capable
					if ($xData[$i] === 'false') $xData[$i] = false;
					else if ($xData[$i] === 'true') $xData[$i] = true;

					// Integer values: 0, 1 are capable
					else if ($xData[$i] === 0) $xData[$i] = false;
					else if ($xData[$i] === 1) $xData[$i] = true;

					// Others else are invalid
					else continue;
				}

			} else if (is_array($iData)) { // Array
				if (!is_array($xData[$i])) continue; // Type mismatch

				reset($iData);
				reset($xData[$i]);

				if (key($iData) === 0) { // Sequential array
					if (key($xData[$i]) !== 0) continue; // Type mismatch
					$xData[$i] = array_filter($xData[$i]); // Remove falsy elements

				} else if (is_string(key($iData))) { // Associative array
					if (!is_string(key($xData[$i]))) continue; // Type mismatch
				}
			}

			$r[$i] = $xData[$i];
			unset($xData[$i]);
		}
		return $r;
	}

	public function export() {
		if (!$this->getUser()->has_cap('edit_post')) throw new UserCapabilityException();
	}

	public function exportData() {
		$dump = $this->parser->dump($this->data, 2, 0);
		$file = $this->path . '/site.yml';
		$io = fopen($file, 'w');
		if (!$io) return false;
		if (fwrite($io, $dump) === false) {
			fclose($io);
			return false;
		}
		return fclose($io) ? $file : false;
	}

	public function exportDB($xDestDir = '') {
		if (!$this->getUser()->has_cap('export')) throw new UserCapabilityException('You have no sufficient rights to export the database');
		if (!array_key_exists('import_sql_file', $this->data)) throw new \RuntimeException('Insufficient data.');

		$dest = ($xDestDir ? $xDestDir : $this->path) . '/' . $this->data['import_sql_file'];

		$memLim = ini_get('memory_limit');
		@ini_set('memory_limit', '2048M');

		$timeLim = ini_get('max_execution_time');
		@ini_set('max_execution_time', 0);

		try {
			$dump = new Mysqldump( // @formatter:off
				DB_NAME,
				DB_USER,
				DB_PASSWORD,
				DB_HOST,
				'mysql',
				array (
					'add-drop-table' => true,
					'single-transaction' => false, // This requires SUPER privilege (@see https://github.com/ifsnop/mysqldump-php/issues/54)
					'lock-tables' => true          // So we must use this instead
				)
			); // @formatter:on
			$dump->start($dest);

		} catch (\Exception $e) {
			throw $e;
		}

		@ini_set('memory_limit', $memLim);
		@ini_set('max_execution_time', $timeLim);

		return $dest;
	}
}
