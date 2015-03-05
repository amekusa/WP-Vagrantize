<?php
namespace amekusa\WPVagrantize;

class Menu {

	public function __construct() {
		add_action('admin_menu', array ($this, 'register'));
	}

	public function register() {
		add_management_page(
			'WP Vagrantize', // Page title
			'WP Vagrantize', // Menu title
			'export', // Capability
			'wp-vagrantize', // Menu slug
			array ($this, 'renderPage') // Function to render the page
		);
	}

	public function renderPage() {
		?>
<div class="wrap">
	<h2>WP Vagrantize</h2>
</div>
		<?php
	}
}
