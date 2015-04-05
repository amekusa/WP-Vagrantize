<?php
namespace amekusa\WPVagrantize;

class Menu {
	private static $instance;
	private $isRegistered;
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
		$this->screen = new MenuScreen($this);
		add_action('admin_menu', array ($this, 'register'));
	}

	public function register() {
		if ($this->isRegistered) throw new \RuntimeException('The menu is already registered');
		add_submenu_page( // @formatter:off
			$this->parentSlug . '.php', // Parent menu
			'WP Vagrantize', // Page title
			'WP Vagrantize', // Menu title
			'export', // Capability
			$this->slug, // Menu slug
			array ($this->screen, 'render') // Callback to render the screen
		); // @formatter:on
		$this->isRegistered = true;
	}

	public function isRegistered() {
		return $this->isRegistered;
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
}
