<?php
session_start();
//echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

define('SITEROOT', './');
require_once(SITEROOT.'/configs/setting.inc.php');
require_once(SITEROOT.'/configs/config_left.inc.php');

define('SMARTY_DIR', SITEROOT.'/include/Smarty-3.0.4/libs/');
require_once(SMARTY_DIR . 'Smarty.class.php');
global $config;

if(isset($_COOKIE['admin']['path']) && (!empty($_COOKIE['admin']['path'])))
	$config['path'] = SITEROOT.'themes/'.$_COOKIE['admin']['path'].'/';
else 
	$config['path'] = SITEROOT.'themes/default/';

$smarty = new Smarty();
$smarty->assign("config", $config);

$smarty->display($config['path'].'templates/left.tpl.html');
//$smarty->display($config['templs']['left']);
?>
