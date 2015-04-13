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

		add_action('load-' . $this->getId(), function () {
			$getRewpData = new AjaxAction('get-rewp-data', function () {
				$rewp = new ReWP();
				$data = $rewp->getData();
				wp_send_json($data);
			});
			$getRewpData->register();

			add_action('admin_enqueue_scripts', function () {
				wp_enqueue_script( // @formatter:off
					'wp-vagrantize-menu',
					plugins_url('../scripts/menu.wp-vagrantize.jquery.js', __FILE__),
					array ('jquery')
				); // @formatter:on
				wp_localize_script( // @formatter:off
					'wp-vagrantize-menu',
					'vars',
					array ('alert' => __('YEAHHHHHH!!!', 'wp-vagrantize'))
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
		echo plugin_dir_path(__FILE__);
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
