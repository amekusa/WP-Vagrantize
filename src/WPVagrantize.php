<?php
namespace amekusa\WPVagrantize;

class WPVagrantize {
	private static $instance;
	
	public static final function instance() {
		if (!isset(static::$instance)) static::$instance = new static();
		return static::$instance;
	}
	
	private function __construct() {
		
	}
}