<?php
namespace amekusa\WPVagrantize;

class ReWP {
	private $path;

	public function __construct($xPath) {
		$this->path = $xPath;
	}

	public function init() {
		$parser = new \Spyc();
		$data = $parser->loadFile($this->path . '/provision/default.yml');
		$data = array_merge($data, $this->getSiteData());
		$in = $parser->dump();

		$file = fopen($this->path . '/site.yml', 'w');
		fwrite($file, $in);
		fclose($file);
	}

	private function getSiteData() {
		$r = array ( // @formatter:off
			'hostname_old' => gethostname(),
			'admin_user' => '',
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
		if (!current_user_can('edit_posts')) wp_die();

		if ($this->command_exists('bzip2')) {
			$compression_pipe = '| bzip2 --stdout';
			$ext = '.sql.bz2';
		} else {
			$compression_pipe = '';
			$ext = '.sql';
		}

		@ini_set('memory_limit', '2048M');
		@ini_set('max_execution_time', 0);

		$tmpname = '/tmp/' . sha1(uniqid()) . $ext;
		$filename = sanitize_title(get_bloginfo('name')) . '-' . date('YmdHis') . $ext;
		$cmd = sprintf("mysqldump -h'%s' -u'%s' -p'%s' %s --single-transaction %s > %s", DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, $compression_pipe, $tmpname);

		exec($cmd);
		header('Content-Type: application/bzip');
		header('Content-Length: ' . filesize($tmpname));
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		readfile($tmpname);
		unlink($tmpname);

		exit();
	}
}
