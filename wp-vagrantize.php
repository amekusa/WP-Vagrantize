<?php
/*
 Plugin Name: WP Vagrantize
 Version: 1.0.0
 Description: A smart way to reproduce your blog into the local machine
 Author: Satoshi Soma a.k.a amekusa
 Author URI: https://github.com/amekusa
 Plugin URI: https://github.com/amekusa/wp-vagrantize
 Text Domain: wp-vagrantize
 */
if (!defined('WP_VAGRANTIZE_HOME')) define('WP_VAGRANTIZE_HOME', __DIR__ . '/');
if (!defined('WP_VAGRANTIZE_URL')) define('WP_VAGRANTIZE_URL', plugin_dir_url(__FILE__));
require_once __DIR__ . '/src/main.php';
