<?php
session_start();
error_reporting(E_ALL);
define('SITEROOT', './');

ini_set('include_path',ini_get('include_path').PATH_SEPARATOR.SITEROOT.'configs/'.PATH_SEPARATOR.SITEROOT.'include/');
require_once("setting.inc.php");
require_once("config.inc.php");
require_once("ListAdvanced.inc.php");
global $config;


class LoginInfoClass extends ListAdvanced
{
  var $url, $self, $session;
  public function __construct() {
    parent::__construct();
  }

  function get_search_form_settings() {
	return array(
		array(
			'type' => 'text',
			'display_name' => 'UID:',
			'id' => 'uid_s',
			'name' => 'uid',
		),
		array(
			'type' => 'text',
			'display_name' => 'User Name:',
			'id' => 'username_s',
			'name' => 'username',
		),
		array(
			'type' => 'text',
			'display_name' => 'IP:',
			'id' => 'ip_s',
			'name' => 'ip',
		),
		array(
			'type' => 'date',
			'display_name' => 'Login Time:',
			'id' => 'login_time_s',
			'name' => 'login_time',
			'size' => INPUT_SIZE,
		),
	);
  }
}
$log = new LoginInfoClass();
if(! $log->check_access()) {
  $log->set_breakpoint();
  echo "<script>if(window.opener){window.opener.location.href='".LOGIN."';} else{window.parent.location.href='".LOGIN."';}</script>";exit;
}
$log->get_table_info();

if(isset($_GET['js_search_form'])) {
	$ary = $log->get_search_form_settings();
	$log->assign('search_form', $ary);	
	$log->assign('config', $config);
	$log->display($config['templs']['search']);
}
else if( isset($_POST['search']) || (isset($_GET['page']) && isset($_GET['sort'])) || isset($_GET['page']) ) {
	if(isset($_POST['search'])) $log->parse();

	$data = $log->list_all();
	$data['options'] = array(DELETE);
	
	$header = $log->get_header($log->get_mappings());

	$pagination = $log->draw( $data['current_page'], $data['total_pages'] );
	
	$log->assign('config', $config);
	$log->assign('header', $header);
	$log->assign('data', $data);
	$log->assign("pagination", $pagination);
	$tpl = $log->get_html_template();
	$log->display($tpl); // not use display, should use AJAX.
}
elseif(isset($_REQUEST['action'])) {
	switch($_REQUEST['action']) {
		case 'delete':
			$log->delete($_GET['id']);
			break;
		default:
			break;
	}
}
else {
	if (isset($_SESSION[$log->self][$log->session['sql']])) $_SESSION[$log->self][$log->session['sql']] = '';

	$total_rows = $log->get_total_rows($log->get_count_sql());

	$_SESSION[$log->self][$log->session['rows']] = $total_rows < 1 ? 1 : $total_rows;
	
	$data = $log->list_all();
	$data['options'] = array(DELETE);
	
	$header = $log->get_header($log->get_mappings());

	$pagination = $log->draw( $data['current_page'], $data['total_pages'] );
	
	$tpl = $log->get_html_template();

	$log->assign('config', $config);
	$log->assign('header', $header);
	$log->assign('data', $data);
	$log->assign("pagination", $pagination);
	$log->assign("template", $tpl);

	$log->display($config['templs']['main']);
}
?>