<?php

$config = array(
	'debug' => true, // used by smarty templates a well a php.
	'marty' => SITEROOT.'/config/marty.ini',
	'footer' => array(),
	'core' => get_core(),
	'site' => get_site(),
	'tool' => get_support(),
	'manage' => get_manage(),
);

///////////////////////////////
function get_site() {
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
	);
}
function get_support() {
	return array(
		'注册信息' => 'login_info.php',
		'管理操作' => 'actions.php',
		'日志' => 'read_log.php',
		'维护记录' => 'reports.php',
		'跟踪' => 'tracks.php',
		'Notes' => 'notes.php',
	);
}
function get_manage() {
	return array(
		'管理用户' => 'users.php',
		'管理用户组' => 'levels.php',
		'普通用户' => 'common_user.php',
		'网页韵律' => 'themes.php',
	);
}
?>