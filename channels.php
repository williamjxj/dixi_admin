<?php
session_start();
error_reporting(E_ALL);
define('SITEROOT', './');

ini_set('include_path',ini_get('include_path').PATH_SEPARATOR.SITEROOT.'configs/'.PATH_SEPARATOR.SITEROOT.'include/');
require_once("setting.inc.php");
require_once("config.inc.php");
require_once("ListAdvanced.inc.php");
global $config;


$config['tabs'] = array('1'=>'通道列表', '2'=>'添加通道');


class ChannelsClass extends ListAdvanced
{
  var $url, $self, $session;
  public function __construct() {
    parent::__construct();
  }

  function get_search_form_settings() {
	return array(
		array(
			'type' => 'text',
			'display_name' => 'URL:',
			'id' => 'url_s',
			'name' => 'url',
		),
		array(
			'type' => 'text',
			'display_name' => '通道名称:',
			'id' => 'name_s',
			'name' => 'name',
		),
		array(
			'type' => 'radio',
			'display_name' => '活动状态:',
			'id' => 'active_s',
			'name' => 'active',
			'lists' => array(
				'N' => 'No',
				'Y' => 'Yes',
				'A' => 'All',
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
		array(
			'type' => 'date',
			'display_name' => '更新日期:',
			'id' => 'updated_s',
			'name' => 'updated',
			'size' => INPUT_SIZE,
		),
		array(
			'type' => 'text',
			'display_name' => '更新者:',
			'id' => 'updatedby_s',
			'name' => 'updatedby',
		),
	);
  }
	function get_header() {
		return array(
			'通道ID' => 'mid',
			'通道名称' => 'name',
			'链接地址' => 'url',
			'归类顺序' => 'weight',
			'组别' => 'groups',
			'状态' => 'active',
			'描述' => 'description',
			'创建' => 'created,createdby',
			'更新' => 'updated,updatedby',
		);
  }
  function get_edit_form_settings() {
	return array(
		array(
			'type' => 'text',
			'display_name' => '通道ID:',
			'name' => 'mid',
			'readonly' => 'readonly',
		),
		array(
			'type' => 'text',
			'display_name' => '通道名称:',
			'name' => 'name',
		),
		array(
			'type' => 'text',
			'display_name' => 'URL:',
			'name' => 'url',
		),
		array(
			'type' => 'radio',
			'display_name' => '活动状态:',
			'name' => 'active',
			'lists' => array(
				'N' => 'No',
				'Y' => 'Yes',
			),
		),
		array(
			'type' => 'textarea',
			'display_name' => '描述:',
			'name' => 'description',
		),
		array(
			'type' => 'text',
			'display_name' => '更新者:',
			'name' => 'updatedby',
		),
		array( 'type' => 'hidden', 'name' => 'mid', 'db_type' => 'int',)
	);
  }

  function get_add_form_settings() {
	return array(
		array(
		  'type' => 'text',
		  'display_name' => '通道名称:',
		  'id' => 'name',
		  'name' => 'name',
		),
		array(
		  'type' => 'text',
		  'display_name' => '通道 URL:',
		  'id' => 'url',
		  'name' => 'url',
		),
		array(
		  'type' => 'textarea',
		  'display_name' => '描述:',
		  'id' => 'description',
		  'name' => 'description',
		),
	  );
  }
}

$cha = new ChannelsClass();
if(! $cha->check_access()) {
  $cha->set_breakpoint();
  echo "<script>if(window.opener){window.opener.location.href='".LOGIN."';} else{window.parent.location.href='".LOGIN."';}</script>";exit;
}
$cha->get_table_info();
$cha->set_default_config(array('calender'=>true,'jvalidate'=>true));

if(isset($_GET['js_search_form'])) {
	$ary = $cha->get_search_form_settings();
	$cha->assign('search_form', $ary);	
	$cha->assign('config', $config);
	$cha->display($config['templs']['search']);
}
elseif(isset($_GET['js_edit_form'])) {
	$ary = $cha->get_edit_form_settings();
	$record = $cha->get_record($_GET['id']);
	$cha->assign('record', $record);	


        if(isset($_GET['tr'])) $cha->assign('form_id', 'ef_'.$_GET['id'].'-'.$_GET['tr']);
        else $cha->assign('form_id', 'ef_'.$_GET['id']);

	$cha->assign('edit_form', $ary);	
	$cha->assign('config', $config);
	$cha->display($config['templs']['edit']);
}
elseif(isset($_GET['js_add_form'])) {
	$ary = $cha->get_add_form_settings();
	$cha->assign('add_form', $ary);	
	$cha->assign('config', $config);
	$cha->display($config['templs']['add']);
}
elseif(isset($_GET['js_assign_form'])) {
	$data = array();
	$data['userid'] = $cha->userid;
	$data['username'] = $cha->username;
	$data['sites'] = $cha->get_sites_array();

	$cha->assign('config', $config);
	$cha->assign('data', $data);
	$cha->display($config['templs']['assign_cm']); 
}
// jQuery.fn.load() uses $_POST.
elseif(isset($_POST['js_get_pages'])) {
	$cha->get_pages_options($_POST['site_id']);
}
elseif(isset($_POST['js_edit_column'])) {
	$ret = $cha->update_column(array('updatedby'=>$cha->username));
	echo json_encode($ret);
}
elseif(isset($_REQUEST['action'])) {
	switch($_REQUEST['action']) {
		case 'edit':
		  $ary = $cha->get_edit_form_settings();
		  echo json_encode($cha->edit_table($ary));
			break;
		case 'delete':
			$cha->delete($_GET['id']);
			break;
		case 'add':
			$last_mid = $cha->create(array('createdby'=>$cha->username,'updatedby'=>$cha->username,'created'=>'NOW()'));
			break;    
		default:
			break;
	}
}
else if( isset($_POST['search']) || (isset($_GET['page']) && isset($_GET['sort'])) || isset($_GET['page']) || isset($_GET['js_reload_list']) ) {
	if(isset($_POST['search'])) $cha->parse();

	$data = $cha->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $cha->get_header($cha->get_mappings());

	$pagination = $cha->draw( $data['current_page'], $data['total_pages'] );
	
	$cha->assign('config', $config);
	$cha->assign('header', $header);
	$cha->assign('data', $data);
	$cha->assign("pagination", $pagination);
	$tpl = $cha->get_html_template();
	$cha->display($tpl); // not use display, should use AJAX.
}
// default: list data.
else {
	if (isset($_SESSION[$cha->self][$cha->session['sql']])) $_SESSION[$cha->self][$cha->session['sql']] = '';

	$total_rows = $cha->get_total_rows($cha->get_count_sql());

	$_SESSION[$cha->self][$cha->session['rows']] = $total_rows < 1 ? 1 : $total_rows;
	
	$data = $cha->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $cha->get_header($cha->get_mappings());

	$pagination = $cha->draw( $data['current_page'], $data['total_pages'] );
	
	$tpl = $cha->get_html_template();

	$cha->assign('config', $config);
	$cha->assign('header', $header);
	$cha->assign('data', $data);
	$cha->assign("pagination", $pagination);

	$cha->assign("template", $tpl);
	$cha->display($config['templs']['main']);
}
?>
