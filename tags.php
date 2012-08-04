<?php
session_start();
define('SITEROOT', './');
error_reporting(E_ALL);

require_once(SITEROOT.'/configs/setting.inc.php');
require_once(SITEROOT.'/configs/config.inc.php');
require_once(SITEROOT.'/configs/ListAdvanced.inc.php');

class TagClass extends ListAdvanced
{
  var $url, $self, $session;

  public function __construct() {
    parent::__construct();
	$this->table = 'tags';
  }

  function get_search_form_settings() {
	return array(
		array(
		  'type' => 'text',
		  'display_name' => '标签:',
		  'id' => 'tid_s',
		  'name' => 'tid',
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
			'display_name' => '标签:',
			'name' => 'name',
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
			'name' => 'tid',
			'db_type' => 'int',
		)
	);
  }

  function get_add_form_settings() {
	return array(
		array(
		  'type' => 'text',
		  'display_name' => '标签:',
		  'id' => 'name',
		  'name' => 'name',
		),
	);
  }

  
  // parse_ini 不支持多国字体。
  function get_header() {
  	return array(
		'tid' => 'tid',
		'标签' => 'name',
		'频率' => 'frequency',
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
$config['tabs'] = array('1'=>'标签列表', '2'=>'添加标签');

$tag = new TagClass();

if(! $tag->check_access()) {
  $tag->set_breakpoint();
  echo "<script>if(window.opener){window.opener.location.href='".LOGIN."';} else{window.parent.location.href='".LOGIN."';}</script>";
  exit;
}
$tag->get_table_info();

$tag->set_default_config(array('calender'=>true,'jvalidate'=>true));

if (isset($_GET['tid'])) {
	switch($_GET['step']) {
	case 1:
		$tag->select_pages($_GET['tid']);
		break;
	case 2:
		$uid = isset($_GET['uid']) ? $_GET['uid'] : $this->userid;
		$tag->select_users_pages($uid);
		break;
	default:
		break;
	}
}
elseif(isset($_POST['update'])) {
	$ac->update_pages();
}
elseif(isset($_GET['js_search_form'])) {
	$ary = $tag->get_search_form_settings();
	$tag->assign('search_form', $ary);	
	$tag->assign('config', $config);
	$tag->display($config['templs']['search']);
}
elseif(isset($_GET['js_edit_form'])) {
	$ary = $tag->get_edit_form_settings();
	$record = $tag->get_record($_GET['id']);
	$tag->assign('record', $record);	
	if(isset($_GET['tr'])) $tag->assign('form_id', 'ef_'.$_GET['id'].'-'.$_GET['tr']);
	else $tag->assign('form_id', 'ef_'.$_GET['id']);
	$tag->assign('edit_form', $ary);	
	$tag->assign('config', $config);
	$tag->display($config['templs']['edit']);
}
elseif(isset($_GET['js_add_form'])) {
	$ary = $tag->get_add_form_settings();
	$tag->assign('add_form', $ary);	
	$tag->assign('config', $config);
	$tag->display($config['templs']['add']);
}
elseif(isset($_POST['js_edit_column'])) {
	$ret = $tag->update_column(array('updatedby'=>$tag->username));
	echo json_encode($ret);
}
elseif(isset($_REQUEST['action'])) {
	switch($_REQUEST['action']) {
		case 'edit':
		  $ary = $tag->get_edit_form_settings();
		  echo json_encode($tag->edit_table($ary));
			break;
		case 'delete':
			$tag->delete($_GET['id']);
			break;
		case 'add':
			$tag->create(array('createdby'=>$tag->username, 'updatedby'=>$tag->username, 'created'=>'NOW()'));
			break;    
		default:
			break;
	}
}
else if( isset($_POST['search']) || (isset($_GET['page']) && isset($_GET['sort'])) || isset($_GET['page']) || isset($_GET['js_reload_list']) ) {
	if(isset($_POST['search'])) $tag->parse();

	$data = $tag->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $tag->get_header(); //$tag->get_mappings();

	$pagination = $tag->draw( $data['current_page'], $data['total_pages'] );
	
	$tag->assign('config', $config);
	$tag->assign('header', $header);
	$tag->assign('data', $data);
	$tag->assign("pagination", $pagination);
	$tpl = $tag->get_html_template();
	$tag->display($tpl); // not use display, should use AJAX.
}
// default: list data.
else {

	if (isset($_SESSION[$tag->self][$tag->session['sql']])) $_SESSION[$tag->self][$tag->session['sql']] = '';

	$total_rows = $tag->get_total_rows($tag->get_count_sql());

	$_SESSION[$tag->self][$tag->session['rows']] = $total_rows < 1 ? 1 : $total_rows;
	
	$data = $tag->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $tag->get_header(); //$tag->get_mappings();

	$pagination = $tag->draw( $data['current_page'], $data['total_pages'] );
	
	$tpl = $tag->get_html_template();

	$tag->assign('config', $config);
	$tag->assign('header', $header);
	$tag->assign('data', $data);
	$tag->assign("pagination", $pagination);

	$tag->assign("template", $tpl);
	$tag->display($config['templs']['main']);
}
?>
