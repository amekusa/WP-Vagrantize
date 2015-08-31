<?php

/**
 * 1 time file downloader
 * Deletes the file after user downloaded it.
 */

// Validation

if (!array_key_exists('file', $_GET)) return;
if (!$_GET['file'] || !is_string($_GET['file'])) return;

$dir = __DIR__ . '/.exports';
$file = realpath($dir . '/' . ltrim($_GET['file'], '/'));

if (!$file) return;

//// Directory check
if (!preg_match('/^' . preg_quote($dir, '/') . '/', $file)) return;

// Do download

@ini_set('memory_limit', '2048M');
@ini_set('max_execution_time', 0);

header('Content-Description: File Transfer');
header('Content-Type: application/force-download');
header('Content-Disposition: attachment; filename=' . basename($file));
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . filesize($file));
ob_clean();
flush();
readfile($file);

@unlink($file); // Delete the file
