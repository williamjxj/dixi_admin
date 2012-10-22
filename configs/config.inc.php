<?php
// all based on SITEROOT: './'.
//header('Content-Type: text/html; charset=UTF-8');

$config = array(
	'debug' => true, // usd by smarty templates as well as php; control to write logfile: admin.log.
	'path' => SITEROOT, //default.
	'smarty' => SITEROOT.'/configs/smarty.ini',
	'ipath' => SITEROOT.'include/',
	'layout' => array(
		'header' => 'header.tpl.html',  
		'title' => 'title.tpl.html',
		'navigator' => 'left.tpl.html',
		'footer' => 'footer.tpl.html',
		'include' => 'include.tpl.html',
		'main' => 'main.tpl.html',
	),
	'header' => array(
		'title' => '底细,真相,还原真相,反映实际情况',
		'description' => '底细,真相,还原真相,反映实际情况',
		'keywords' => '底细,真相,还原真相,反映实际情况',
		'meta_content' => 'text/html; charset=utf-8',
		'meta_defaultrobots' => 'index,follow',
		'meta_robots' => '底细,真相,还原真相,反映实际情况',
	),
	'js' => array(
		'jquery' => 'js/jquery-1.7.2.min.js',
		'jquery-ui' => 'jqueryui/js/jquery-ui-1.8.22.custom.min.js',
		'bootstrap' => 'bootstrap',
		'fancybox'  => 'jquery.fancybox',
	),
	'dcn' => 2,     //search, edit, add forms need.
	'calender' => true, //most common used in almost each app, 'search' is a basic function.
	'qtip' => true, //each app needs help.
	'list' => get_list_defs(),
	'templs' => get_templs(),
	'browser_id' => browser_ID(),
);

// The following function are only used by __this__ file.

function get_list_defs($default_path='') {
	if($default_path && (!empty($default_path)))
		$path = $default_path;
	else
		$path = SITEROOT.'themes/default/';
	return array(
		'up'   => '<img src="'.$path.'images/up-arrow.png" border="0" width="11"  height="5" alt="desc" >',
		'down' => '<img src="'.$path.'images/down-arrow.png" border="0" width="11" height="5" alt="asc" >',
		'SORT1'=> '<img src="'.$path.'images/up.jpg" border="0" width="13" height="11" alt="desc" >',
		'SORT2'=> '<img src="'.$path.'images/down.jpg" border="0" width="13" height="11" alt="asc" >',
		'wait' => '<img src="'.$path.'images/wait.gif" border="0" width="16" height="16" alt="processing. please wait..." >',
		'wait1'=> '<img src="'.$path.'images/spinner.gif" width="16" height="16" border="0">',
		'wait2' => $path.'images/spinner.gif',
	);
}

function get_templs($default_path='') {
	if($default_path && (!empty($default_path)))
		$path = $default_path.'/templates/';
	else
		$path = SITEROOT.'themes/default/templates/';
	return array(
		'index' => $path.'index.tpl.html',
		'layout' => $path.'layout.tpl.html',
		'header' => $path.'header.tpl.html',
		'left' => $path.'left.tpl.html',
		'main' => $path.'main.tpl.html',
		'list' => $path.'list.tpl.html',
		'search' => $path.'search.tpl.html',
		'add' => $path.'add.tpl.html',
		'edit' => $path.'edit.tpl.html',
		'add_plupload' => $path.'add_plupload.tpl.html',
		'add_tinymce' => $path.'add_tinymce.tpl.html',
		'edit_tinymce' => $path.'edit_tinymce.tpl.html',
		'assign' => $path.'assign.tpl.html',
		'assign_cr' => $path.'assign_contents-resources.tpl.html',
		'assign_rc' => $path.'assign_resources-contents.tpl.html',
		'assign_cm' => $path.'assign_pages-modules.tpl.html',
		'assign_mc' => $path.'assign_modules-contents.tpl.html',
		'resources' => $path.'resources.tpl.html',
		'pension_form' => $path.'pension_form.tpl.html',
		'calculator' => $path.'calculator.tpl.html',
	);
}

function browser_ID() {
    if(strstr($_SERVER['HTTP_USER_AGENT'], 'Firefox')){ $id="firefox"; }
    elseif(strstr($_SERVER['HTTP_USER_AGENT'], 'Safari') && !strstr($_SERVER['HTTP_USER_AGENT'], 'Chrome')){ $id="safari"; }
    elseif(strstr($_SERVER['HTTP_USER_AGENT'], 'Chrome')){ $id="chrome"; }
    elseif(strstr($_SERVER['HTTP_USER_AGENT'], 'Opera')){ $id="opera"; }
    elseif(strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE 6')){ $id="ie6"; }
    elseif(strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE 7')){ $id="ie7"; }
    elseif(strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE 8')){ $id="ie8"; }
    elseif(strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE 9')){ $id="ie9"; }
	return $id;
}

//$error => get_errors();
function get_errors() {
	array(
		'100' => 'Could not connect to MySQL DB.',
		'101' => 'Passwords are not equal.',
		'102' => 'Email is not correct.',
		'103' => 'Password is required.',
		'104' => 'Could not add user.',
		'105' => 'Usrname is required.',
	);
}

function get_months() {
	return array(
		'01' => '一月',
		'02' => '二月',
		'03' => '三月',
		'04' => '四月',
		'05' => '五月',
		'06' => '六月',
		'07' => '七月',
		'08' => '八月',
		'09' => '九月',
		'10' => '十月',
		'11' => '十一月',
		'12' => '十二月'
	); 
}

$timezone = "Asia/Shanghai";
if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);
		 

?>
