<?php
session_start();
define('SITEROOT', './'); //getcwd():c:/projects/admin/
require_once(SITEROOT.'/configs/setting.inc.php');

define('SMARTY_DIR', SITEROOT.'/include/Smarty-3.0.4/libs/');
require_once(SMARTY_DIR . 'Smarty.class.php');
global $config;

$config['path'] = SITEROOT.'themes/default/';
$config['ipath'] = SITEROOT.'include/';

$smarty = new Smarty();
$smarty->assign("config", $config);
$smarty->display($config['path'].'templates/header.tpl.html');
//$smarty->display($config['templs']['header']);
?>
