<?php
session_start();
$filename = '.breakpoint.txt';
// dynamic decide which file is present.
if (is_file($filename)) {
	$fh = fopen($filename, 'r');
	$file = fgets($fh);
	fclose($fh);
	unlink($filename);
}
else {
	$file = 'contents.php';
}

header('Location: '.$file);
exit;
?>