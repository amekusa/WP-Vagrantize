<?php

/**
 * 1 time file downloader
 *
 * Deletes the requested file after an user downloaded it.
 */

@ini_set('memory_limit', '2048M');
@ini_set('max_execution_time', 0);
@ini_set('zlib.output_compression', 'Off');

// Validation

if (!array_key_exists('file', $_GET)) return;
if (!$_GET['file'] || !is_string($_GET['file'])) return;

$dir = __DIR__ . '/.exports';
$file = realpath($dir . '/' . ltrim($_GET['file'], '/'));

//// Does the file exist?
if (!$file) return;

//// Is the file actually a file?
if (!is_file($file)) return;

//// Is the file located on a correct place?
if (!preg_match('/^' . preg_quote($dir, '/') . '/', $file)) return;

//// Is the file readable?
if (!is_readable($file)) return;

// Download

header('Accept-Ranges: bytes');
header('Content-Description: File Transfer');
header('Content-Disposition: attachment; filename="' . basename($file) . '"');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($file));

$type = 'application/octet-stream';
switch (pathinfo($file, PATHINFO_EXTENSION)) {
case 'zip':
	$type = 'application/zip';
}
header('Content-Type: ' . $type);

ob_clean();
flush();

// Outputs the file content, and deletes it immediately
if (readfile($file) !== false) @unlink($file);
