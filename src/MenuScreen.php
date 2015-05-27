<?php
namespace amekusa\WPVagrantize;

class MenuScreen {
	private $menu;
	private $actions;

	public static function create(Menu $xMenu) {
		if ($xMenu->hasScreen()) throw new \RuntimeException('The menu already has an another screen');
		return new static($xMenu);
	}

	private function __construct(Menu $xMenu) {
		$this->menu = $xMenu;
		$rewp = new ReWP(WP_VAGRANTIZE_HOME . COMPOSER_DIR . '/amekusa/ReWP');

		$this->actions = array ( // @formatter:off

			new AjaxAction('saveReWPSettings', function () use($rewp) {
				if (!$_POST) wp_send_json_error();
				if (!array_key_exists('data', $_POST)) wp_send_json_error();

				$data = array ();
				parse_str($_POST['data'], $data);
				//var_dump($data); die;

				if (!$rewp->setData($data)) wp_send_json_error();
				wp_send_json_success();
			}),

			new AjaxAction('resetReWPSettings', function () use($rewp) {
				if (!$rewp->reset()) wp_send_json_error();
				wp_send_json_success();
			}),

			new AjaxAction('renderReWPSettingsTable', function () use($rewp) {
				$data = $rewp->getData();
				$parser = $rewp->getParser();

				foreach ($data as $i => $iData) {
// 					if (!is_array($iData)) continue;
// 					$iDump = $parser->dump($iData, 2, 0, true);
// 					$iDump = preg_replace('/---\n/', '', $iDump); // Remove "---"
// 					$data[$i] = $iDump;
				}

				ob_start();
				include __DIR__ . '/view/ReWPSettingsTable.php';
				wp_send_json_success(ob_get_clean());
			}),

			new AjaxAction('exportDB', function () use($rewp) {
				$rewp->exportDB();
				wp_send_json_success(array ('msg', 'Database exported.'));
			})
		); // @formatter:on
		foreach ($this->actions as $iAct)
			$iAct->register();

		add_action('load-' . $this->getId(), array ($this, 'setup'));
	}

	public function getId() {
		return $this->menu->getParentSlug() . '_page_' . $this->menu->getSlug();
	}

	public function setup() {
		add_action('admin_enqueue_scripts', function () {

			wp_enqueue_script( // @formatter:off
				'autosize',
				WP_VAGRANTIZE_URL . BOWER_DIR . '/autosize/dest/autosize.min.js',
				array ('jquery')
			); // @formatter:on

			wp_enqueue_script( // @formatter:off
				'wp-vagrantize-menu',
				WP_VAGRANTIZE_URL . SCRIPTS_DIR . '/menu.jquery.js',
				array ('jquery', 'autosize')
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
