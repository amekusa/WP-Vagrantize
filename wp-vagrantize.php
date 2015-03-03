<?php
namespace amekusa\wp_vagrantize;

/*
Plugin Name: WP Vagrantize
Version: 0.1-alpha
Description: PLUGIN DESCRIPTION HERE
Author: YOUR NAME HERE
Author URI: YOUR SITE HERE
Plugin URI: PLUGIN SITE HERE
Text Domain: wp-vagrantize
Domain Path: /languages
*/

add_action('init', function () { // The entry point
	include __DIR__.'/vendor/autoload.php';
	
});
