<?php
namespace amekusa\WPVagrantize;

use Ifsnop\Mysqldump\Mysqldump;

class ReWP {
	private $path;
	private $data;

	public function __construct($xPath = null) {
		$this->path = $xPath ? $xPath : __DIR__;
		$this->init();
	}

	public function init() {
		$parser = new \Spyc();
		$data = $parser->loadFile($this->path . '/provision/default.yml');
		$this->data = array_merge($data, $this->getSiteData());
		$dump = $parser->dump();
		$file = fopen($this->path . '/site.yml', 'w');
		fwrite($file, $dump);
		fclose($file);
	}

	public function getData() {
		return $this->data;
	}

	private function getSiteData() {
		$user = wp_get_current_user();
		$r = array ( // @formatter:off
			'hostname_old' => gethostname(),
			'admin_user' => $user->user_login,
			'admin_pass' => '',
			'db_prefix' => $GLOBALS['wpdb']->prefix,
			'db_host' => DB_HOST,
			'db_name' => DB_NAME,
			'db_user' => DB_USER,
			'db_pass' => DB_PASSWORD
		); // @formatter:on
		return $r;
	}

	public function export() {
		if (!current_user_can('edit_post')) throw new UserCapabilityException();

		/*
		$compression_pipe = '';
		$ext = '.sql';


		$tmpname = '/tmp/' . sha1(uniqid()) . $ext;
		$filename = sanitize_title(get_bloginfo('name')) . '-' . date('YmdHis') . $ext;
		$cmd = sprintf("mysqldump -h'%s' -u'%s' -p'%s' %s --single-transaction %s > %s", DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, $compression_pipe, $tmpname);

		// exec($cmd);
		passthru(escapeshellcmd($cmd));
		header('Content-Type: application/bzip');
		header('Content-Length: ' . filesize($tmpname));
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		readfile($tmpname);
		unlink($tmpname);

		exit();
		*/
	}

	public function exportSql() {
		if (!current_user_can('manage_database')) throw new UserCapabilityException('You don’t have sufficient rights to manage the database');

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
