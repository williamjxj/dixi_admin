<?php
session_start();
define('SITEROOT', './'); //getcwd():c:/projects/admin/
require_once(SITEROOT.'/configs/setting.inc.php');
//require_once(SITEROOT.'/configs/config_left.inc.php');

define('SMARTY_DIR', SITEROOT.'/include/Smarty-3.0.4/libs/');
require_once(SMARTY_DIR . 'Smarty.class.php');
global $config;

if(isset($_COOKIE['admin']['path']) && (!empty($_COOKIE['admin']['path'])))
	$config['path'] = SITEROOT.'themes/'.$_COOKIE['admin']['path'].'/';
else 
	$config['path'] = SITEROOT.'themes/default/';

$smarty = new Smarty();
$smarty->assign("config", $config);
$smarty->display($config['path'].'templates/header.tpl.html');
//$smarty->display($config['templs']['header']);
?>
