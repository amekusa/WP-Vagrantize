<?php
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
if (!defined('WP_VAGRANTIZE_HOME')) define('WP_VAGRANTIZE_HOME', __DIR__ . '/');
if (!defined('WP_VAGRANTIZE_URL')) define('WP_VAGRANTIZE_URL', plugin_dir_url(__FILE__));
require_once __DIR__ . '/src/main.php';
