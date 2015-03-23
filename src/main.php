<?php
namespace amekusa\WPVagrantize;

add_action('init', function () { // The entry point
	if (!is_admin()) return; // Nothing to do with the front view
	require __DIR__ . '/../vendor/autoload.php';
	$main = WPVagrantize::getInstance();
});
