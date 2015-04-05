<?php
namespace amekusa\WPVagrantize;

class MenuScreen {
	private $menu;

	public function __construct(Menu $xMenu) {
		$this->menu = $xMenu;
		add_action('load-' . $this->getId(), array ($this, 'setup'));
	}

	public function setup() {
		add_action('admin_enqueue_scripts', function () {
			wp_enqueue_script('wp-vagrantize-', plugins_url('scripts/xxx.js', __FILE__), array ('jquery'));
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
<table class="widefat">
	<tbody>
		<tr>
			<th>xxx</th>
			<td>value</td>
		</tr>
	</tbody>
</table>
<?php $r = ob_get_clean(); // @formatter:on
		echo '<div class="wrap">' . $r . '</div>';
	}
}