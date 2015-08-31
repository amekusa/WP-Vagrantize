<?php namespace amekusa\WPVagrantize;

class MenuScreen {
	protected $menu;
	protected $actions;

	public static function create(Menu $xMenu) {
		if ($xMenu->hasScreen()) throw new \RuntimeException('The menu already has an another screen');
		return new static($xMenu);
	}

	protected function __construct(Menu $xMenu) {
		$this->menu = $xMenu;
		$rewp = new ReWP(WP_VAGRANTIZE_HOME . COMPOSER_DIR . '/amekusa/ReWP');

		$this->actions = array ( // @formatter:off

			new AjaxAction('saveSettings', function () use($rewp) {
				if (!$_POST) wp_send_json_error();
				if (!array_key_exists('data', $_POST)) wp_send_json_error();

				$data = array ();
				parse_str($_POST['data'], $data);
				$rewp->setData($data);

				$dest = $rewp->exportData();
				if (!$dest) wp_send_json_error();
				else {
					$time = filemtime($dest);
					wp_send_json_success(array (
						'file' => $dest,
						'date' => date(get_option('time_format') . ', ' . get_option('date_format'), $time),
						'datetime' => date(DATE_W3C, $time)
					));
				}
			}),

			new AjaxAction('resetSettings', function () use($rewp) {
				if (!$rewp->reset()) wp_send_json_error();
				else wp_send_json_success();
			}),

			new AjaxAction('renderSettingsTable', function () use($rewp) {
				$data = $rewp->getData();
				ob_start();
				include __DIR__ . '/view/SettingsTable.php';
				wp_send_json_success(ob_get_clean());
			}),

			new AjaxAction('download', function () use($rewp) {
				$name = $rewp->getData('hostname');
				if (!$name) $name = $rewp->getData('ip');
				if (!$name) $name = 'vagrantme.up';
				$name = preg_replace('/^[^a-zA-Z0-9_-]+/', '', $name);
				$name = preg_replace('/[^a-zA-Z0-9_-]+$/', '', $name);
				$name = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $name);

				$dest = WP_VAGRANTIZE_HOME . ".exports/{$name}." . date('YmdHis') . '.zip';

				$zip = new \ZipArchiveEx();
				$zip->open($dest, \ZipArchive::OVERWRITE);
				$zip->addDirContents($rewp->getPath());
				$db = '';
				if ($rewp->getData('import_sql')) {
					$db = $rewp->exportDB(WP_VAGRANTIZE_HOME . '.exports');
					$zip->addFile($db, basename($db));
				}
				$zip->close();
				if ($db) unlink($db);

				$time = filemtime($dest);
				wp_send_json_success(array (
					'file' => $dest,
					'fileUrl' => WP_VAGRANTIZE_URL . 'download.php?file=' . basename($dest),
					'date' => date(get_option('time_format') . ', ' . get_option('date_format'), $time),
					'datetime' => date(DATE_W3C, $time)
				));
			})

		); // @formatter:on

		foreach ($this->actions as $iAct) $iAct->register();

		add_action('load-' . $this->getId(), array ($this, 'setup'));
	}

	public function getId() {
		return $this->menu->getParentSlug() . '_page_' . $this->menu->getSlug();
	}

	public function setup() {

		add_action('admin_enqueue_scripts', function () {

			wp_enqueue_script( // @formatter:off
				'wp-vagrantize-menu',
				WP_VAGRANTIZE_URL . SCRIPTS_DIR . '/menu.jquery.js',
				array ('jquery')
			); // @formatter:on

			$actions = array ();
			foreach ($this->actions as $iAct)
				$actions[$iAct->getName()] = $iAct->forJQAjax();
			wp_localize_script('wp-vagrantize-menu', 'actions', $actions);

			wp_enqueue_style( // @formatter:off
				'wp-vagrantize-common',
				WP_VAGRANTIZE_URL . STYLES_DIR . '/common.css'
			); // @formatter:on
		});
	}

	public function render() {
		include __DIR__ . '/view/MenuScreen.php';
	}
}
