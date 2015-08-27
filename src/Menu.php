<?php namespace amekusa\WPVagrantize;

class Menu {
	private static $instance;
	private $slug;
	private $parentSlug;
	private $screen;

	public static final function getInstance() {
		if (!isset(static::$instance)) static::$instance = new static();
		return static::$instance;
	}

	private function __construct() {
		$this->slug = 'wp-vagrantize';
		$this->parentSlug = 'tools';
		$this->screen = MenuScreen::create($this);
		add_action('admin_menu', function () {
			add_submenu_page( // @formatter:off
				$this->parentSlug . '.php', // Parent menu
				'WP Vagrantize', // Page title
				'WP Vagrantize', // Menu title
				'export', // Capability
				$this->slug, // Menu slug
				array ($this->screen, 'render') // Callback to render the screen
			); // @formatter:on
		});
	}

	public function getSlug() {
		return $this->slug;
	}

	public function getParentSlug() {
		return $this->parentSlug;
	}

	public function getScreen() {
		return $this->screen;
	}

	public function hasScreen() {
		return isset($this->screen);
	}
}
