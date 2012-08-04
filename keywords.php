<?php
session_start();
define('SITEROOT', './');
error_reporting(E_ALL);

require_once(SITEROOT.'/configs/setting.inc.php');
require_once(SITEROOT.'/configs/config.inc.php');
require_once(SITEROOT.'/configs/ListAdvanced.inc.php');

class KeywordClass extends ListAdvanced
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
		  'type' => 'select',
		  'display_name' => '频率:',
		  'id' => 'frequency_s',
		  'name' => 'frequency',
		  'call_func' => 'get_frequency_options',
		  'db_type' => 'int',
		),
		array(
		  'type' => 'select',
		  'display_name' => '标签:',
		  'id' => 'tag_s',
		  'name' => 'tag',
		  'call_func' => 'get_tag_options',
		),
		array(
			'type' => 'radio',
			'display_name' => '状态:',
			'id' => 'active_s',
			'name' => 'active',
			'lists' => array(
				'N' => '禁止',
				'Y' => '活动',
				'A' => '所有',
			),
			'checked' => 'A',
			'ignore' => 'A',
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
			'type' => 'radio',
			'display_name' => '状态:',
			'name' => 'active',
			'lists' => array(
				'N' => '禁止',
				'Y' => '活动',
			),
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
		'kid' => 'kid',
		'关键词' => 'keyword',
		'频率' => 'frequency',
		'标签' => 'tag',
		'状态' => 'active',
		'创建日期' => 'created',
		'创建者' => 'createdby',
		'更新日期' => 'updated',
		'更新者' => 'updatedby',
	);
  }  
}

//////////////////////////////////////////

global $config;
$config['tabs'] = array('1'=>'关键词列表', '2'=>'添加关键词');

$kw = new KeywordClass();

if(! $kw->check_access()) {
  $kw->set_breakpoint();
  echo "<script>if(window.opener){window.opener.location.href='".LOGIN."';} else{window.parent.location.href='".LOGIN."';}</script>";
  exit;
}
$kw->get_table_info();

$kw->set_default_config(array('calender'=>true,'jvalidate'=>true));

if (isset($_GET['kid'])) {
	switch($_GET['step']) {
	case 1:
		$kw->select_pages($_GET['kid']);
		break;
	case 2:
		$uid = isset($_GET['uid']) ? $_GET['uid'] : $this->userid;
		$kw->select_users_pages($uid);
		break;
	default:
		break;
	}
}
elseif(isset($_POST['update'])) {
	$ac->update_pages();
}
elseif(isset($_GET['js_search_form'])) {
	$ary = $kw->get_search_form_settings();
	$kw->assign('search_form', $ary);	
	$kw->assign('config', $config);
	$kw->display($config['templs']['search']);
}
elseif(isset($_GET['js_edit_form'])) {
	$ary = $kw->get_edit_form_settings();
	$record = $kw->get_record($_GET['id']);
	$kw->assign('record', $record);	
	if(isset($_GET['tr'])) $kw->assign('form_id', 'ef_'.$_GET['id'].'-'.$_GET['tr']);
	else $kw->assign('form_id', 'ef_'.$_GET['id']);
	$kw->assign('edit_form', $ary);	
	$kw->assign('config', $config);
	$kw->display($config['templs']['edit']);
}
elseif(isset($_GET['js_add_form'])) {
	$ary = $kw->get_add_form_settings();
	$kw->assign('add_form', $ary);	
	$kw->assign('config', $config);
	$kw->display($config['templs']['add']);
}
elseif(isset($_POST['js_edit_column'])) {
	$ret = $kw->update_column(array('updatedby'=>$kw->username));
	echo json_encode($ret);
}
elseif(isset($_REQUEST['action'])) {
	switch($_REQUEST['action']) {
		case 'edit':
		  $ary = $kw->get_edit_form_settings();
		  echo json_encode($kw->edit_table($ary));
			break;
		case 'delete':
			$kw->delete($_GET['id']);
			break;
		case 'add':
			$last_kid = $kw->create(array('createdby'=>$kw->username, 'updatedby'=>$kw->username, 'created'=>'NOW()'));
			break;    
		default:
			break;
	}
}
else if( isset($_POST['search']) || (isset($_GET['page']) && isset($_GET['sort'])) || isset($_GET['page']) || isset($_GET['js_reload_list']) ) {
	if(isset($_POST['search'])) $kw->parse();

	$data = $kw->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $kw->get_header(); //$kw->get_mappings();

	$pagination = $kw->draw( $data['current_page'], $data['total_pages'] );
	
	$kw->assign('config', $config);
	$kw->assign('header', $header);
	$kw->assign('data', $data);
	$kw->assign("pagination", $pagination);
	$tpl = $kw->get_html_template();
	$kw->display($tpl); // not use display, should use AJAX.
}
// default: list data.
else {

	if (isset($_SESSION[$kw->self][$kw->session['sql']])) $_SESSION[$kw->self][$kw->session['sql']] = '';

	$total_rows = $kw->get_total_rows($kw->get_count_sql());

	$_SESSION[$kw->self][$kw->session['rows']] = $total_rows < 1 ? 1 : $total_rows;
	
	$data = $kw->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $kw->get_header(); //$kw->get_mappings();

	$pagination = $kw->draw( $data['current_page'], $data['total_pages'] );
	
	$tpl = $kw->get_html_template();

	$kw->assign('config', $config);
	$kw->assign('header', $header);
	$kw->assign('data', $data);
	$kw->assign("pagination", $pagination);

	$kw->assign("template", $tpl);
	$kw->display($config['templs']['main']);
}
?>
