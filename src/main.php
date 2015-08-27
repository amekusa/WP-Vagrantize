<?php namespace amekusa\WPVagrantize;

const TEXT_DOMAIN = 'wp-vagrantize';
const COMPOSER_DIR = 'lib/composer';
const BOWER_DIR = 'lib/bower';
const SCRIPTS_DIR = 'scripts';
const STYLES_DIR = 'styles';

add_action('init', function () { // The entry point
	if (!is_admin()) return; // Nothing to do with the front view
	require WP_VAGRANTIZE_HOME . COMPOSER_DIR . '/autoload.php';
	$main = WPVagrantize::getInstance();
});
