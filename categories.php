<?php
session_start();
error_reporting(E_ALL);
define('SITEROOT', './');

ini_set('include_path',ini_get('include_path').PATH_SEPARATOR.SITEROOT.'configs/'.PATH_SEPARATOR.SITEROOT.'include/');
require_once("setting.inc.php");
require_once("config.inc.php");
require_once("ListAdvanced.inc.php");
global $config;

$config['tabs'] = array('1'=>'ç±»åˆ«åˆ—è¡¨', '2'=>'æ·»åŠ ç±»åˆ«');

class CategoriesClass extends ListAdvanced
{
  var $url, $self, $session;
  public function __construct() {
    parent::__construct();
  }

  function get_search_form_settings() {
	return array(
		array(
		  'type' => 'select',
		  'display_name' => 'Site:',
		  'id' => 'cid_s',
		  'name' => 'cid',
		  'call_func' => 'get_categories_options',
		  'db_type' => 'int',
		),
		array(
			'type' => 'text',
			'display_name' => 'Name:',
			'id' => 'name_s',
			'name' => 'name',
		),
		/*array(
			'type' => 'text',
			'display_name' => 'Description:',
			'id' => 'description_s',
			'name' => 'description',
		),*/
		array(
			'type' => 'text',
			'display_name' => 'URL:',
			'id' => 'url_s',
			'name' => 'url',
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
			'display_name' => 'Create Date:',
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
			'display_name' => 'Name:',
			'name' => 'name',
		),
		array(
			'type' => 'text',
			'display_name' => 'URL:',
			'id' => 'url_s',
			'name' => 'url',
		), /*
		array(
			'type' => 'date',
			'display_name' => 'Created:',
			'name' => 'created',
			'size' => INPUT_SIZE,
		), */
		array(
			'type' => 'textarea',
			'display_name' => 'Description:',
			'name' => 'description',
		),
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
			'name' => 'pid',
			'db_type' => 'int',
		)
	);
  }

  function get_add_form_settings() {
	return array(
		array(
		  'type' => 'text',
		  'display_name' => 'À¸Ä¿Ãû³Æ :',
		  'id' => 'name',
		  'name' => 'name',
		),
		array(
		  'type' => 'textarea',
		  'display_name' => 'Description:',
		  'id' => 'description',
		  'name' => 'description',
		  'size' => INPUT_SIZE+10,
		),
/*
		array(
		  'type' => 'text',
		  'display_name' => 'URL:',
		  'id' => 'url',
		  'name' => 'url',
		),
		array(
		  'type' => 'select',
		  'display_name' => 'ç±»åˆ«:',
		  'id' => 'cid',
		  'name' => 'cid',
		  'call_func' => 'get_categories_options',
		),      
*/
	  );
  }

  function get_site_name($query) {
	if(isset($query) && $query) {
		if (preg_match("/,\s*cid,/", $query)) {
			$query = $this->replace_str_once_new("/cid/", '(select name from categories where categories.cid=pages.cid) as cid', $query);
		}
		return $query;
	}
	return;
  }

}

$category = new CategoriesClass();
if(! $category->check_access()) {
  $category->set_breakpoint();
  echo "<script>if(window.opener){window.opener.location.href='".LOGIN."';} else{window.parent.location.href='".LOGIN."';}</script>";exit;
}
$category->get_table_info();
$category->set_default_config(array('calender'=>true,'jvalidate'=>true));

if (isset($_GET['pid'])) {
	switch($_GET['step']) {
	case 1:
		$category->select_pages($_GET['pid']);
		break;
	case 2:
		$uid = isset($_GET['uid']) ? $_GET['uid'] : $this->userid;
		$category->select_users_pages($uid);
		break;
	default:
		break;
	}
}
elseif(isset($_POST['update'])) {
	$ac->update_pages();
}
elseif(isset($_GET['js_search_form'])) {
	$ary = $category->get_search_form_settings();
	$category->assign('search_form', $ary);	
	$category->assign('config', $config);
	$category->display($config['templs']['search']);
}
elseif(isset($_GET['js_edit_form'])) {
	$ary = $category->get_edit_form_settings();
	$record = $category->get_record($_GET['id']);
	$category->assign('record', $record);	
	if(isset($_GET['tr'])) $category->assign('form_id', 'ef_'.$_GET['id'].'-'.$_GET['tr']);
	else $category->assign('form_id', 'ef_'.$_GET['id']);
	$category->assign('edit_form', $ary);	
	$category->assign('config', $config);
	$category->display($config['templs']['edit']);
}
elseif(isset($_GET['js_add_form'])) {
	$ary = $category->get_add_form_settings();
	$category->assign('add_form', $ary);	
	$category->assign('config', $config);
	$category->display($config['templs']['add']);
}
elseif(isset($_POST['js_edit_column'])) {
	$ret = $category->update_column(array('updatedby'=>$category->username));
	echo json_encode($ret);
}
elseif(isset($_REQUEST['action'])) {
	switch($_REQUEST['action']) {
		case 'edit':
		  $ary = $category->get_edit_form_settings();
		  echo json_encode($category->edit_table($ary));
			break;
		case 'delete':
			$category->delete($_GET['id']);
			break;
		case 'add':
			$last_pid = $category->create(array('createdby'=>$category->username, 'updatedby'=>$category->username, 'created'=>'NOW()'));
                        $query = "UPDATE category AS p, (SELECT name FROM categories WHERE cid=".$_POST['cid']." ) AS s SET p.sname = s.name WHERE pid=".$last_pid;
                        $affected = $category->mdb2->exec($query);
                        if (PEAR::isError($affected)) {
                                die($affected->getMessage().': ' . $query . ". line: " . __LINE__);
                        }
			break;    
		default:
			break;
	}
}
else if( isset($_POST['search']) || (isset($_GET['page']) && isset($_GET['sort'])) || isset($_GET['page']) || isset($_GET['js_reload_list']) ) {
	if(isset($_POST['search'])) $category->parse();

	$data = $category->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $category->get_header($category->get_mappings());

	$pagination = $category->draw( $data['current_page'], $data['total_pages'] );
	
	$category->assign('config', $config);
	$category->assign('header', $header);
	$category->assign('data', $data);
	$category->assign("pagination", $pagination);
	$tpl = $category->get_html_template();
	$category->display($tpl); // not use display, should use AJAX.
}
// default: list data.
else {
	if (isset($_SESSION[$category->self][$category->session['sql']])) $_SESSION[$category->self][$category->session['sql']] = '';

	$total_rows = $category->get_total_rows($category->get_count_sql());

	$_SESSION[$category->self][$category->session['rows']] = $total_rows < 1 ? 1 : $total_rows;
	
	$data = $category->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $category->get_header($category->get_mappings());

	$pagination = $category->draw( $data['current_page'], $data['total_pages'] );
	
	$tpl = $category->get_html_template();

	$category->assign('config', $config);
	$category->assign('header', $header);
	$category->assign('data', $data);
	$category->assign("pagination", $pagination);

	$category->assign("template", $tpl);
	$category->display($config['templs']['main']);
}
?>
