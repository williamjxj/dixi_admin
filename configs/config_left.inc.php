<?php
// all based on SITEROOT: './'.
//echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

$config = array(
	'debug' => true, // usd by smarty templates as well as php.
	'smarty' => SITEROOT.'/configs/smarty.ini',
	'top' => array(
		'Logout' => '/index.php?logout=1'
	),
	'footer' => array(),
	'cores' => get_cores(),
	'tools' => get_supports(),
	'manages' => get_manages(),
);

///////////////////////////////

function get_cores() {
	return array(
		'正文内容' => 'contents.php',
		'模块' => 'modules.php',
		'资源' => 'resources.php',
		'邮件' => 'emails.php',
	);
}

function get_supports() {
	return array(
		'注册信息' => 'login_info.php',
		'管理操作' => 'actions.php',
		'日志' => 'read_log.php',
		'维护记录' => 'reports.php',
		'跟踪' => 'tracks.php',
		'团契' => 'divisions.php',
		'Stickynotes' => 'stickynotes.php',
	);
}
function get_manages() {
	return array(
		'管理用户' => 'users.php',
		'管理用户组' => 'levels.php',
		'普通用户' => 'common_users.php',
		'站点管理' => 'sites.php',
		'网页' => 'pages.php',
		'网页韵律' => 'themes.php',
	);
}

?>