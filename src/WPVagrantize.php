<?php namespace amekusa\WPVagrantize;

/**
 * The main class
 *
 * @author amekusa <post@amekusa.com>
 */
class WPVagrantize {
	private static $instance;
	private $menu;
	private $prov;

	public static final function getInstance() {
		if (!isset(static::$instance)) static::$instance = new static();
		return static::$instance;
	}

	private function __construct() {
		$this->menu = Menu::getInstance();
		//$this->prov = new ReWP(__DIR__ . '/../vendor/amekusa/ReWP');
		//$this->prov->exportData();
		//$this->prov->exportDB();
	}
}
