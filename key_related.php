<?php
session_start();
define('SITEROOT', './');
error_reporting(E_ALL);

require_once(SITEROOT.'/configs/setting.inc.php');
require_once(SITEROOT.'/configs/config.inc.php');
require_once(SITEROOT.'/configs/ListAdvanced.inc.php');

class KeyRelatedClass extends ListAdvanced
{
  var $url, $self, $session;

  public function __construct() {
    parent::__construct();
	$this->table = 'keywords';
  }

  function get_search_form_settings() {
	return array(
		array(
		  'type' => 'text',
		  'display_name' => '关键词:',
		  'id' => 'kid_s',
		  'name' => 'kid',
		),
		array(
			'type' => 'date',
			'display_name' => '创建日期:',
			'id' => 'created_s',
			'name' => 'created',
			'size' => INPUT_SIZE,
		),
		array(
			'type' => 'text',
			'display_name' => '创建者:',
			'id' => 'createdby_s',
			'name' => 'createdby',
		),			
	);
  }

  function get_edit_form_settings() {
	return array(
		array(
			'type' => 'text',
			'display_name' => '关键词:',
			'name' => 'keyword',
		),
		array(
			'type' => 'hidden',
			'name' => 'kid',
			'db_type' => 'int',
		)
	);
  }

  function get_add_form_settings() {
	return array(
		array(
		  'type' => 'text',
		  'display_name' => '关键词:',
		  'id' => 'keyword',
		  'name' => 'keyword',
		),
	);
  }

  
  // parse_ini 不支持多国字体。
  function get_header() {
  	return array(
		'RID' => 'rid',
		'keyword' => 'keyword',
		'related_keyword' => 'rk',
		//'URL' => 'kurl',
		'kid' => 'kid',
		'createdby' => 'createdby',
		'created' => 'created',
	);
  }  
}

//////////////////////////////////////////

global $config;
$config['tabs'] = array('1'=>'关键词列表', '2'=>'添加关键词', '3'=>'上载包含关键词的文件');

$kr = new KeyRelatedClass();

if(! $kr->check_access()) {
  $kr->set_breakpoint();
  echo "<script>if(window.opener){window.opener.location.href='".LOGIN."';} else{window.parent.location.href='".LOGIN."';}</script>";
  exit;
}
$kr->get_table_info();

$kr->set_default_config(array('calender'=>true,'jvalidate'=>true));

if(isset($_GET['js_search_form'])) {
	$ary = $kr->get_search_form_settings();
	$kr->assign('search_form', $ary);	
	$kr->assign('config', $config);
	$kr->display($config['templs']['search']);
}
elseif(isset($_GET['js_edit_form'])) {
	$ary = $kr->get_edit_form_settings();
	$record = $kr->get_record($_GET['id']);
	$kr->assign('record', $record);	
	if(isset($_GET['tr'])) $kr->assign('form_id', 'ef_'.$_GET['id'].'-'.$_GET['tr']);
	else $kr->assign('form_id', 'ef_'.$_GET['id']);
	$kr->assign('edit_form', $ary);	
	$kr->assign('config', $config);
	$kr->display($config['templs']['edit']);
}
elseif(isset($_GET['js_add_form'])) {
	$ary = $kr->get_add_form_settings();
	$kr->assign('add_form', $ary);	
	$kr->assign('config', $config);
	$kr->display($config['templs']['add']);
}
elseif(isset($_POST['js_edit_column'])) {
	$ret = $kr->update_column(array('updatedby'=>$kr->username));
	echo json_encode($ret);
}
elseif(isset($_REQUEST['action'])) {
	switch($_REQUEST['action']) {
		case 'edit':
		  $ary = $kr->get_edit_form_settings();
		  echo json_encode($kr->edit_table($ary));
			break;
		case 'delete':
			$kr->delete($_GET['id']);
			break;
		case 'add':
			$last_kid = $kr->create(array('createdby'=>$kr->username, 'updatedby'=>$kr->username, 'created'=>'NOW()'));
			break;    
		default:
			break;
	}
}
else if( isset($_POST['search']) || (isset($_GET['page']) && isset($_GET['sort'])) || isset($_GET['page']) || isset($_GET['js_reload_list']) ) {
	if(isset($_POST['search'])) $kr->parse();

	$data = $kr->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $kr->get_header(); //$kr->get_mappings();

	$pagination = $kr->draw( $data['current_page'], $data['total_pages'] );
	
	$data['self_i18n'] = '�ؼ���';
	$kr->assign('config', $config);
	$kr->assign('header', $header);
	$kr->assign('data', $data);
	$kr->assign("pagination", $pagination);
	$tpl = $kr->get_html_template();
	$kr->display($tpl); // not use display, should use AJAX.
}
// default: list data.
else {

	if (isset($_SESSION[$kr->self][$kr->session['sql']])) $_SESSION[$kr->self][$kr->session['sql']] = '';

	$total_rows = $kr->get_total_rows($kr->get_count_sql());

	$_SESSION[$kr->self][$kr->session['rows']] = $total_rows < 1 ? 1 : $total_rows;
	
	$data = $kr->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $kr->get_header(); //$kr->get_mappings();

	$pagination = $kr->draw( $data['current_page'], $data['total_pages'] );
	
	$tpl = $kr->get_html_template();

	$kr->assign('config', $config);
	$kr->assign('header', $header);
	$kr->assign('data', $data);
	$kr->assign("pagination", $pagination);

	$kr->assign("template", $tpl);
	$kr->display($config['templs']['main']);
}
?>
