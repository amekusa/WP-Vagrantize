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
			$rewp = new ReWP(WP_VAGRANTIZE_HOME . COMPOSER_DIR . '/amekusa/ReWP');
			$rewp->setup();
			$data = $rewp->getData();
			wp_send_json($data);
		});
		$getRewpData->register();

		add_action('load-' . $this->getId(), function () use($getRewpData) {

			add_action('admin_enqueue_scripts', function () use($getRewpData) {
				wp_enqueue_script( // @formatter:off
					'wp-vagrantize-menu',
					WP_VAGRANTIZE_URL . '/scripts/menu.wp-vagrantize.jquery.js',
					array ('jquery')
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
<div id="rewp-data"></div>
<table class="widefat">
	<tbody>
		<template id="rewp-data-row">
		<tr>
			<th></th>
			<td></td>
		</tr>
		</template>
	</tbody>
</table>

<?php $r = ob_get_clean(); // @formatter:on
		echo '<div class="wrap">' . $r . '</div>';
	}
}
