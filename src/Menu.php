<?php
namespace amekusa\WPVagrantize;

class Menu {
	const PARENT_SLUG = 'tools';
	const SLUG = 'wp-vagrantize';
	private static $instance;
	private $isRegistered;

	public static final function getInstance() {
		if (!isset(static::$instance)) static::$instance = new static();
		return static::$instance;
	}

	private function __construct() {
		add_action('admin_menu', array ($this, 'register'));
		add_action('admin_init', function () {
			if ($this->isCurrent()) echo 'CURRENT';
		});
	}

	public function register() {
		if ($this->isRegistered) throw new \RuntimeException('The menu is already registered');
		add_submenu_page( // @formatter:off
			static::PARENT_SLUG . '.php', // Parent menu
			'WP Vagrantize', // Page title
			'WP Vagrantize', // Menu title
			'export', // Capability
			static::SLUG, // Menu slug
			array ($this, 'renderPage') // Callback to render the page
		); // @formatter:on
		$this->isRegistered = true;
	}

	public function isCurrent() {
		if (!$scr = get_current_screen()) return false;
		return $scr->id == static::PARENT_SLUG . '_page_' . static::SLUG;
	}

	public function renderPage() {
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
