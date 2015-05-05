<?php
namespace amekusa\WPVagrantize;

use Ifsnop\Mysqldump\Mysqldump;

class ReWP {
	private $path;
	private $parser;
	private $user;
	private $data;

	public function __construct($xPath = null) {
		$this->path = $xPath ? $xPath : __DIR__;
		$this->parser = new \Spyc();
		$this->init();
	}

	public function init() {
		$this->user = wp_get_current_user();
		$this->data = $this->parser->loadFile($this->path . '/provision/default.yml');
		$this->setData($this->getSiteData());
	}

	public function getParser() {
		return $this->parser;
	}

	public function getData() {
		return $this->data;
	}

	public function getSiteData() {
		$r = array ( // @formatter:off
			'hostname_old' => gethostname(),
			'version' => get_bloginfo('version'),
			'lang' => get_bloginfo('language'),
			'title' => get_bloginfo('name'),
			'multisite' => is_multisite(),
			'admin_user' => $this->user->user_login,
			'admin_pass' => '',
			'db_prefix' => $GLOBALS['wpdb']->prefix,
			'db_host' => DB_HOST,
			'db_name' => DB_NAME,
			'db_user' => DB_USER,
			'db_pass' => DB_PASSWORD,
			'plugins' => get_option('active_plugins'),
			'theme' => wp_get_theme(),
			'import_sql' => $this->user->has_cap('import'),
		); // @formatter:on
		return $r;
	}

	public function setData($xData) {
		$data = $this->sanitizeData($xData);
		$this->data = array_merge($this->data, $data);
		$this->exportData();
	}

	public function sanitizeData($xData) {
		$r = array ();
		foreach ($this->data as $i => $iData) {
			if (!array_key_exists($i, $xData)) continue;
			if ($xData[$i]) {
				// TODO Abort if the type of $xData[$i] doesn't match for $iData
			}
			$r[$i] = $xData[$i];
			unset($xData[$i]);
		}
		return $r;
	}

	public function export() {
		if (!$this->user->has_cap('edit_post')) throw new UserCapabilityException();
	}

	public function exportData() {
		$dump = $this->parser->dump($this->data, 2, false);
		$file = fopen($this->path . '/site.yml', 'w');
		fwrite($file, $dump);
		return fclose($file);
	}

	public function exportDB() {
		if (!$this->user->has_cap('export')) throw new UserCapabilityException('You have no sufficient rights to export the database');

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
			$dump->start($this->path . '/' . $this->data['import_sql_file']);
		} catch (\Exception $e) {
			throw $e;
		}

		@ini_set('memory_limit', $memLim);
		@ini_set('max_execution_time', $timeLim);
	}
}
