<?php

$config = array(
	'debug' => true, // usd by smarty templates as well as php.
	'smarty' => SITEROOT.'/configs/smarty.ini',
	'footer' => array(),
	'cores' => get_cores(),
	'tools' => get_supports(),
	'manages' => get_manages(),
);

///////////////////////////////
function get_core() {
	return array(
		'站点管理' => 'sites.php',
		'网页' => 'pages.php',
		'模块' => 'modules.php',
		'正文内容' => 'contents.php',
		'资源' => 'resources.php',
	);
}
function get_core() {
	return array(
		'关键词' => 'keywords.php',
		'类别' => 'categories.php',
		'栏目' => 'items.php',
		'标签' => 'tags.php',
		'通道' => 'channels.php',
		'正文内容' => 'contents.php',
		'模块' => 'modules.php',
		'资源' => 'resources.php',
	);
}
function get_support() {
	return array(
		'注册信息' => 'login_info.php',
		'管理操作' => 'actions.php',
		'日志' => 'read_log.php',
		'维护记录' => 'reports.php',
		'跟踪' => 'tracks.php',
	);
}
function get_manages() {
	return array(
		'管理用户' => 'users.php',
		'管理用户组' => 'levels.php',
		'普通用户' => 'common_users.php',
		'网页韵律' => 'themes.php',
	);
}


?>