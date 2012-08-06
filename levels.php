<?php
session_start();
error_reporting(E_ALL);
define('SITEROOT', './');

ini_set('include_path',ini_get('include_path').PATH_SEPARATOR.SITEROOT.'configs/'.PATH_SEPARATOR.SITEROOT.'include/');
require_once("setting.inc.php");
require_once("config.inc.php");
require_once("ListAdvanced.inc.php");
global $config;


$config['tabs'] = array('1'=>'用户组列表', '2'=>'添加用户组');


class LevelsClass extends ListAdvanced
{
  var $url, $self, $session;
  public function __construct() {
    parent::__construct();
  }

  function get_search_form_settings() {
	return array(
		/*array(
			'type' => 'text',
			'display_name' => 'Level:',
			'id' => 'level_s',
			'name' => 'level',
		),*/
		array(
		  'type' => 'text',
		  'display_name' => 'Name:',
		  'id' => 'name_s',
		  'name' => 'name',
		),
		array(
			'type' => 'text',
			'display_name' => 'Description:',
			'id' => 'description_s',
			'name' => 'description',
		),
		array(
			'type' => 'radio',
			'display_name' => 'Active:',
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
			'display_name' => 'Created Date:',
			'id' => 'created_s',
			'name' => 'created',
			'size' => INPUT_SIZE,
		),
		array(
			'type' => 'text',
			'display_name' => 'Created By:',
			'id' => 'createdby_s',
			'name' => 'createdby',
		),			
	);
  }

  function get_edit_form_settings() {
	return array(
		array(
			'type' => 'text',
			'display_name' => 'Level:',
			'name' => 'level',
			'readonly' => 'readonly',
		),
		array(
			'type' => 'text',
			'display_name' => 'Name:',
			'name' => 'name',
		),
		array(
			'type' => 'textarea',
			'display_name' => 'description:',
			'name' => 'description',
		),/*
		array(
			'type' => 'date',
			'display_name' => 'Created:',
			'name' => 'created',
			'size' => INPUT_SIZE,
		), */
		array(
			'type' => 'radio',
			'display_name' => 'Active:',
			'name' => 'active',
			'lists' => array(
				'N' => 'No',
				'Y' => 'Yes',
			),
		),
		array(
			'type' => 'hidden',
			'name' => 'level',
		)
	);
  }

  function get_add_form_settings() {
	return array(
		/*array(
		  'type' => 'text',
		  'display_name' => 'Level:',
		  'id' => 'level',
		  'name' => 'level',
		  'db_type' => 'int',
		),*/
		array(
		  'type' => 'text',
		  'display_name' => 'Group Name:',
		  'id' => 'level_name',
		  'name' => 'name',
		  'size' => INPUT_SIZE+10,
		),
		array(
		  'type' => 'textarea',
		  'display_name' => 'Description:',
		  'id' => 'description',
		  'name' => 'description',
		  'size' => INPUT_SIZE+10,
		),
	  );
  }
}

$level = new LevelsClass();
if(! $level->check_access()) {
  $level->set_breakpoint();
  echo "<script>if(window.opener){window.opener.location.href='".LOGIN."';} else{window.parent.location.href='".LOGIN."';}</script>";exit;
}
$level->get_table_info();
$level->set_default_config(array('calender'=>true,'jvalidate'=>true));

if(isset($_GET['js_search_form'])) {
	$ary = $level->get_search_form_settings();
	$level->assign('search_form', $ary);	
	$level->assign('config', $config);
	$level->display($config['templs']['search']);
}
elseif(isset($_GET['js_edit_form'])) {
	$ary = $level->get_edit_form_settings();
	$record = $level->get_record($_GET['id']);
	$level->assign('record', $record);	

        if(isset($_GET['tr'])) $level->assign('form_id', 'ef_'.$_GET['id'].'-'.$_GET['tr']);
        else $level->assign('form_id', 'ef_'.$_GET['id']);

	$level->assign('edit_form', $ary);	
	$level->assign('config', $config);
	$level->display($config['templs']['edit']);
}
elseif(isset($_GET['js_add_form'])) {
	$ary = $level->get_add_form_settings();
	$level->assign('add_form', $ary);	
	$level->assign('config', $config);
	$level->display($config['templs']['add']);
}

elseif(isset($_POST['js_edit_column'])) {
	$ret = $level->update_column(array('updatedby'=>$level->username));
	echo json_encode($ret);
}
elseif(isset($_REQUEST['action'])) {
	switch($_REQUEST['action']) {
		case 'edit':
		  $ary = $level->get_edit_form_settings();
		  echo json_encode($level->edit_table($ary));
			break;
		case 'delete':
			$level->delete($_GET['id']);
			break;
			/* action:add, description:...., level:2*/
		case 'add':
			$level->create(array('createdby'=>$level->username,
								 'updatedby'=>$level->username,
								 'created'=>'NOW()'
						));
			break;    
		default:
			break;
	}
}
else if( isset($_POST['search']) || (isset($_GET['page']) && isset($_GET['sort'])) || isset($_GET['page']) || isset($_GET['js_reload_list']) ) {
	if(isset($_POST['search'])) $level->parse();

	$data = $level->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $level->get_header($level->get_mappings());

	$pagination = $level->draw( $data['current_page'], $data['total_pages'] );
	
	$level->assign('config', $config);

	$level->assign('header', $header);
	$level->assign('data', $data);
	$level->assign("pagination", $pagination);
	$tpl = $level->get_html_template();
	$level->display($tpl); // not use display, should use AJAX.
}
else {
	if (isset($_SESSION[$level->self][$level->session['sql']]))
		$_SESSION[$level->self][$level->session['sql']] = '';

	$total_rows = $level->get_total_rows($level->get_count_sql());

	$_SESSION[$level->self][$level->session['rows']] = $total_rows < 1 ? 1 : $total_rows;
	
	$data = $level->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $level->get_header($level->get_mappings());

	$pagination = $level->draw( $data['current_page'], $data['total_pages'] );
	
	$tpl = $level->get_html_template();

	$level->assign('config', $config);
	$level->assign('header', $header);
	$level->assign('data', $data);
	$level->assign("pagination", $pagination);

	$level->assign("template", $tpl);
	$level->display($config['templs']['main']);
}
?>
