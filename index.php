<?php
session_start();
define('SITEROOT', './');
require_once(SITEROOT.'/configs/setting.inc.php');
require_once(SITEROOT.'/configs/config.inc.php');
require_once(SITEROOT.'/configs/base.inc.php');

define('SMARTY_DIR', SITEROOT.'/include/Smarty-3.0.4/libs/');
require_once(SMARTY_DIR . 'Smarty.class.php');
global $config;

if(isset($_COOKIE['admin']['path']) && (!empty($_COOKIE['admin']['path'])))
	$config['path'] = SITEROOT.'themes/'.$_COOKIE['admin']['path'].'/';
else 
	$config['path'] = SITEROOT.'themes/default/';
$config['list'] = get_list_defs($config['path']);

$base = new BaseClass();
if(! $base->check_access()) {
  echo "<script>if(window.opener){window.opener.location.href='".LOGIN."';} else{window.location.href='".LOGIN."';}</script>";exit;
}
$smarty = new Smarty();
$smarty->assign("config", $config);
//echo "<pre>"; print_r($config); echo "</pre>";
$smarty->display($config['templs']['index']);
?>
