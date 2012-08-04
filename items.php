<?php
session_start();
error_reporting(E_ALL);
define('SITEROOT', './');

ini_set('include_path',ini_get('include_path').PATH_SEPARATOR.SITEROOT.'configs/'.PATH_SEPARATOR.SITEROOT.'include/');
require_once("setting.inc.php");
require_once("config.inc.php");
require_once("ListAdvanced.inc.php");
global $config;

$config['tabs'] = array('1'=>'栏目列表', '2'=>'添加栏目');

class ItemsClass extends ListAdvanced
{
  var $url, $self, $session;
  public function __construct() {
    parent::__construct();
  }

  function get_search_form_settings() {
	return array(
		array(
		  'type' => 'text',
		  'display_name' => '栏目名称:',
		  'id' => 'name_s',
		  'name' => 'name',
		),
		array(
			'type' => 'text',
			'display_name' => '描述:',
			'id' => 'description_s',
			'name' => 'desc',
		),
		array(
			'type' => 'radio',
			'display_name' => '状态:',
			'name' => 'active',
			'lists' => array(
				'N' => '禁止',
				'Y' => '激活',
				'A' => '所有',
			),
			'checked' => 'A',
			'ignore' => 'A',
		),
		array(
			'type' => 'date',
			'display_name' => '创建时间:',
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
	
	function get_header() {
		return array(
			'IID' => 'iid',
			'类别' => 'category',
			'项目' => 'name',
			'链接地址' => 'iurl',
			'频率' => 'frequency',
			'标签' => 'tag',
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
			'display_name' => '栏目名称:',
			'name' => 'name',
		),
		array(
			'type' => 'textarea',
			'display_name' => '描述:',
			'name' => 'description',
		),
		array(
			'type' => 'radio',
			'display_name' => '状态:',
			'name' => 'active',
			'lists' => array(
				'N' => '禁止',
				'Y' => '激活',
			),
		),
		array(
			'type' => 'hidden',
			'name' => 'iid',
		)
	);
  }

  function get_add_form_settings() {
	return array(
		array(
		  'type' => 'text',
		  'display_name' => '栏目名称:',
		  'id' => 'name',
		  'name' => 'name',
		  'size' => INPUT_SIZE+10,
		),
		array(
		  'type' => 'select',
		  'display_name' => '属于哪个类别：',
		  'id' => 'cid',
		  'name' => 'cid',
		  'call_func' => 'get_category_options',
		),      
		array(
		  'type' => 'text',
		  'display_name' => '链接地址:',
		  'id' => 'iurl',
		  'name' => 'iurl',
		),
		array(
		  'type' => 'textarea',
		  'display_name' => '描述:',
		  'id' => 'description',
		  'name' => 'description',
		  'size' => INPUT_SIZE+10,
		),
	 );
  }
  
  function get_category_options() {
	$sql = "SELECT cid, name, description FROM categories where active='Y' ORDER BY name";
	return 	$this->get_select_options($sql);
  }
  
}

//////////////////////////////////////////////////


$item = new ItemsClass();
if(! $item->check_access()) {
  $item->set_breakpoint();
  echo "<script>if(window.opener){window.opener.location.href='".LOGIN."';} else{window.parent.location.href='".LOGIN."';}</script>";exit;
}
$item->get_table_info();
$item->set_default_config(array('calender'=>true,'jvalidate'=>true));

if(isset($_GET['js_search_form'])) {
	$ary = $item->get_search_form_settings();
	$item->assign('search_form', $ary);	
	$item->assign('config', $config);
	$item->display($config['templs']['search']);
}
elseif(isset($_GET['js_edit_form'])) {
	$ary = $item->get_edit_form_settings();
	$record = $item->get_record($_GET['id']);
	$item->assign('record', $record);	

        if(isset($_GET['tr'])) $item->assign('form_id', 'ef_'.$_GET['id'].'-'.$_GET['tr']);
        else $item->assign('form_id', 'ef_'.$_GET['id']);

	$item->assign('edit_form', $ary);	
	$item->assign('config', $config);
	$item->display($config['templs']['edit']);
}
elseif(isset($_GET['js_add_form'])) {
	$ary = $item->get_add_form_settings();
	$item->assign('add_form', $ary);	
	$item->assign('config', $config);
	$item->display($config['templs']['add']);
}

elseif(isset($_POST['js_edit_column'])) {
	$ret = $item->update_column(array('updatedby'=>$item->username));
	echo json_encode($ret);
}
elseif(isset($_REQUEST['action'])) {
	switch($_REQUEST['action']) {
		case 'edit':
		  $ary = $item->get_edit_form_settings();
		  echo json_encode($item->edit_table($ary));
			break;
		case 'delete':
			$item->delete($_GET['id']);
			break;
			/* action:add, description:...., level:2*/
		case 'add':
			$item->create(array('createdby'=>$item->username,
								 'updatedby'=>$item->username,
								 'created'=>'NOW()'
						));
			break;    
		default:
			break;
	}
}
else if( isset($_POST['search']) || (isset($_GET['page']) && isset($_GET['sort'])) || isset($_GET['page']) || isset($_GET['js_reload_list']) ) {
	if(isset($_POST['search'])) $item->parse();

	$data = $item->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $item->get_header($item->get_mappings());

	$pagination = $item->draw( $data['current_page'], $data['total_pages'] );
	
	$item->assign('config', $config);

	$item->assign('header', $header);
	$item->assign('data', $data);
	$item->assign("pagination", $pagination);
	$tpl = $item->get_html_template();
	$item->display($tpl); // not use display, should use AJAX.
}
else {
	if (isset($_SESSION[$item->self][$item->session['sql']]))
		$_SESSION[$item->self][$item->session['sql']] = '';

	$total_rows = $item->get_total_rows($item->get_count_sql());

	$_SESSION[$item->self][$item->session['rows']] = $total_rows < 1 ? 1 : $total_rows;
	
	$data = $item->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $item->get_header($item->get_mappings());

	$pagination = $item->draw( $data['current_page'], $data['total_pages'] );
	
	$tpl = $item->get_html_template();

	$item->assign('config', $config);
	$item->assign('header', $header);
	$item->assign('data', $data);
	$item->assign("pagination", $pagination);

	$item->assign("template", $tpl);
	$item->display($config['templs']['main']);
}
?>
