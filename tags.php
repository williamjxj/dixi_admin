<?php
session_start();
define('SITEROOT', './');
error_reporting(E_ALL);

require_once(SITEROOT.'/configs/setting.inc.php');
require_once(SITEROOT.'/configs/config.inc.php');
require_once(SITEROOT.'/configs/ListAdvanced.inc.php');

class KeySearchClass extends ListAdvanced
{
  var $url, $self, $session;

  public function __construct() {
    parent::__construct();
	$this->table = 'key_search';
  }

  function get_search_form_settings() {
	return array(
		array(
		  'type' => 'text',
		  'display_name' => '查询词:',
		  'id' => 'keyword_s',
		  'name' => 'keyword',
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
			'display_name' => '查询词:',
			'name' => 'keyword',
		),
		array(
			'type' => 'text',
			'display_name' => '包括:',
			'name' => 'include',
		),
		array(
			'type' => 'text',
			'display_name' => '排除:',
			'name' => 'exclude',
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
		  'display_name' => '查询词:',
		  'id' => 'keyword',
		  'name' => 'keyword',
		),
	);
  }

  
  // parse_ini 不支持多国字体。
  function get_header() {
  	return array(
		'kid' => 'kid',
		'查询词' => 'keyword',
		'包括' => 'include',
		'排除' => 'exclude',
		'创建者' => 'createdby',
		'创建日期' => 'created',
		'更新日期' => 'updated',
	);
  }  
}

//////////////////////////////////////////

global $config;
$config['tabs'] = array('1'=>'标签列表', '2'=>'添加标签');

$ks = new KeySearchClass();

if(! $ks->check_access()) {
  $ks->set_breakpoint();
  echo "<script>if(window.opener){window.opener.location.href='".LOGIN."';} else{window.parent.location.href='".LOGIN."';}</script>";
  exit;
}
$ks->get_table_info();

$ks->set_default_config(array('calender'=>true,'jvalidate'=>true));

if (isset($_GET['kid'])) {
	switch($_GET['step']) {
	case 1:
		$ks->select_pages($_GET['kid']);
		break;
	case 2:
		$uid = isset($_GET['uid']) ? $_GET['uid'] : $this->userid;
		$ks->select_users_pages($uid);
		break;
	default:
		break;
	}
}
elseif(isset($_POST['update'])) {
	$ac->update_pages();
}
elseif(isset($_GET['js_search_form'])) {
	$ary = $ks->get_search_form_settings();
	$ks->assign('search_form', $ary);	
	$ks->assign('config', $config);
	$ks->display($config['templs']['search']);
}
elseif(isset($_GET['js_edit_form'])) {
	$ary = $ks->get_edit_form_settings();
	$record = $ks->get_record($_GET['id']);
	$ks->assign('record', $record);	
	if(isset($_GET['tr'])) $ks->assign('form_id', 'ef_'.$_GET['id'].'-'.$_GET['tr']);
	else $ks->assign('form_id', 'ef_'.$_GET['id']);
	$ks->assign('edit_form', $ary);	
	$ks->assign('config', $config);
	$ks->display($config['templs']['edit']);
}
elseif(isset($_GET['js_add_form'])) {
	$ary = $ks->get_add_form_settings();
	$ks->assign('add_form', $ary);	
	$ks->assign('config', $config);
	$ks->display($config['templs']['add']);
}
elseif(isset($_POST['js_edit_column'])) {
	$ret = $ks->update_column(array('updatedby'=>$ks->username));
	echo json_encode($ret);
}
elseif(isset($_REQUEST['action'])) {
	switch($_REQUEST['action']) {
		case 'edit':
		  $ary = $ks->get_edit_form_settings();
		  echo json_encode($ks->edit_table($ary));
			break;
		case 'delete':
			$ks->delete($_GET['id']);
			break;
		case 'add':
			$ks->create(array('createdby'=>$ks->username, 'updatedby'=>$ks->username, 'created'=>'NOW()'));
			break;    
		default:
			break;
	}
}
else if( isset($_POST['search']) || (isset($_GET['page']) && isset($_GET['sort'])) || isset($_GET['page']) || isset($_GET['js_reload_list']) ) {
	if(isset($_POST['search'])) $ks->parse();

	$data = $ks->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $ks->get_header(); //$ks->get_mappings();

	$pagination = $ks->draw( $data['current_page'], $data['total_pages'] );
	
	$ks->assign('config', $config);
	$ks->assign('header', $header);
	$ks->assign('data', $data);
	$ks->assign("pagination", $pagination);
	$tpl = $ks->get_html_template();
	$ks->display($tpl); // not use display, should use AJAX.
}
// default: list data.
else {

	if (isset($_SESSION[$ks->self][$ks->session['sql']])) $_SESSION[$ks->self][$ks->session['sql']] = '';

	$total_rows = $ks->get_total_rows($ks->get_count_sql());

	$_SESSION[$ks->self][$ks->session['rows']] = $total_rows < 1 ? 1 : $total_rows;
	
	$data = $ks->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $ks->get_header(); //$ks->get_mappings();

	$pagination = $ks->draw( $data['current_page'], $data['total_pages'] );
	
	$tpl = $ks->get_html_template();

	$ks->assign('config', $config);
	$ks->assign('header', $header);
	$ks->assign('data', $data);
	$ks->assign("pagination", $pagination);

	$ks->assign("template", $tpl);
	$ks->display($config['templs']['main']);
}
?>
