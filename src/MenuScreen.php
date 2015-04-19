<?php
namespace amekusa\WPVagrantize;

class MenuScreen {
	private $menu;

	public static function create(Menu $xMenu) {
		if ($xMenu->hasScreen()) throw new \RuntimeException('The menu already has an another screen');
		return new static($xMenu);
	}

	private function __construct(Menu $xMenu) {
		$this->menu = $xMenu;

		$getRewpData = new AjaxAction('get_rewp_data', function () {
			$rewp = new ReWP(WP_VAGRANTIZE_HOME . COMPOSER_DIR . 'amekusa/ReWP');
			$rewp->setup();
			$data = $rewp->getData();
			wp_send_json($data);
		});
		$getRewpData->register();

		add_action('load-' . $this->getId(), function () use($getRewpData) {

			add_action('admin_enqueue_scripts', function () use($getRewpData) {
				wp_enqueue_script( // @formatter:off
					'transparency',
					WP_VAGRANTIZE_URL . BOWER_DIR . 'transparency/dist/transparency.min.js',
					array ('jquery')
				); // @formatter:on
				wp_enqueue_script( // @formatter:off
					'wp-vagrantize-menu',
					WP_VAGRANTIZE_URL . 'scripts/menu.wp-vagrantize.jquery.js',
					array ('jquery', 'transparency')
				); // @formatter:on
				wp_localize_script( // @formatter:off
					'wp-vagrantize-menu',
					'WPVagrantize',
					array (
						'url' => admin_url('admin-ajax.php'),
						'action' => $getRewpData->getName(),
						'nonce' => $getRewpData->getNonce()
					)
				); // @formatter:on
			});
		});
	}

	public function isCurrent() {
		if (!$scr = get_current_screen()) return false;
		return $scr->id == $this->getId();
	}

	public function getId() {
		return $this->menu->getParentSlug() . '_page_' . $this->menu->getSlug();
	}

	public function render() {
		ob_start(); // @formatter:off ?>

<h2>WP Vagrantize</h2>
<h3>ReWP</h3>
<table id="rewp-data-table" class="widefat">
	<thead>
		<tr>
			<th>Setting</th>
			<td style="border-bottom:1px solid #e1e1e1; color:#333">Value</td>
		</tr>
	</thead>
	<tbody class="dummy">
		<tr>
			<td colspan="2">Loading ...</td>
		</tr>
	</tbody>
</table>

<?php $r = ob_get_clean(); // @formatter:on
		echo '<div class="wrap">' . $r . '</div>';
	}
}
