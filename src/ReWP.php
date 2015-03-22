<?php
namespace amekusa\WPVagrantize;

use Ifsnop\Mysqldump\Mysqldump;

class ReWP {
	protected $path, $data, $user, $parser;

	public function __construct($xPath = null) {
		$this->path = $xPath ? $xPath : __DIR__;
		$this->setup();
	}

	public function setup() {
		$this->data = $this->getParser()->loadFile($this->path . '/provision/default.yml');
		$this->updateData();
	}

	public function getData() {
		return $this->data;
	}

	protected function getUser() {
		if (!isset($this->user)) $this->user = wp_get_current_user();
		return $this->user;
	}

	protected function getParser() {
		if (!isset($this->parser)) $this->parser = new \Spyc();
		return $this->parser;
	}

	public function updateData() {
		$data = array ( // @formatter:off
			'hostname_old' => gethostname(),
			'version' => get_bloginfo('version'),
			'lang' => get_bloginfo('language'),
			'title' => get_bloginfo('name'),
			'multisite' => is_multisite(),
			'admin_user' => $this->getUser()->user_login,
			'admin_pass' => '',
			'db_prefix' => $GLOBALS['wpdb']->prefix,
			'db_host' => DB_HOST,
			'db_name' => DB_NAME,
			'db_user' => DB_USER,
			'db_pass' => DB_PASSWORD,
			'import_sql' => $this->getUser()->has_cap('import'),
		); // @formatter:on
		$this->data = array_merge($this->data, $data);
	}

	public function export() {
		if (!$this->getUser()->has_cap('edit_post')) throw new UserCapabilityException();
	}

	public function exportData() {
		$dump = $this->getParser()->dump($this->data);
		$file = fopen($this->path . '/site.yml', 'w');
		fwrite($file, $dump);
		return fclose($file);
	}

	public function exportDB() {
		if (!$this->getUser()->has_cap('export')) throw new UserCapabilityException('You have no sufficient rights to export the database');

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
					'single-transaction' => true,
					'lock-tables' => false
				)
			); // @formatter:on
			$dump->start($this->path . '/' . $this->data['import_sql_file']);
		} catch (\Exception $e) {
			throw new \RuntimeException('mysqldump-php error: ' . $e->getMessage());
		}

		@ini_set('memory_limit', $memLim);
		@ini_set('max_execution_time', $timeLim);
	}
}
