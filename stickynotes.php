<?php
session_start();
error_reporting(E_ALL);
define('SITEROOT', './');
require_once(SITEROOT.'include/Smarty-3.0.4/libs/Smarty.class.php');

$smarty = new Smarty();

$smarty->template_dir = SITEROOT.'templates/';
$smarty->compile_dir = SITEROOT.'templates_c/';

$smarty->display('stickynotes.tpl.html');
?>