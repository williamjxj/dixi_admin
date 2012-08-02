<?php
session_start();
error_reporting(E_ALL);
define('SITEROOT', './');

ini_set('include_path',ini_get('include_path').PATH_SEPARATOR.SITEROOT.'configs/'.PATH_SEPARATOR.SITEROOT.'include/');
require_once("setting.inc.php");
require_once("config.inc.php");
require_once("ListAdvanced.inc.php");
global $config;


$config['tabs'] = array('1'=>'List  Pages', '2'=>'Add  Page'); //, '3'=>'Assign Pages'

class PagesClass extends ListAdvanced
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
		  'id' => 'site_id_s',
		  'name' => 'site_id',
		  'call_func' => 'get_sites_options',
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
		  'display_name' => 'Page Name:',
		  'id' => 'component_nname',
		  'name' => 'name',
		),
		array(
		  'type' => 'textarea',
		  'display_name' => 'Description:',
		  'id' => 'description',
		  'name' => 'description',
		  'size' => INPUT_SIZE+10,
		),
		array(
		  'type' => 'text',
		  'display_name' => 'URL:',
		  'id' => 'url',
		  'name' => 'url',
		),
		array(
		  'type' => 'select',
		  'display_name' => 'Sites:',
		  'id' => 'site_id',
		  'name' => 'site_id',
		  'call_func' => 'get_sites_options',
		),      
	  );
  }

  function get_site_name($query) {
	if(isset($query) && $query) {
		if (preg_match("/,\s*site_id,/", $query)) {
			$query = $this->replace_str_once_new("/site_id/", '(select name from sites where sites.site_id=pages.site_id) as site_id', $query);
		}
		return $query;
	}
	return;
  }

}

$page = new PagesClass();
if(! $page->check_access()) {
  $page->set_breakpoint();
  echo "<script>if(window.opener){window.opener.location.href='".LOGIN."';} else{window.parent.location.href='".LOGIN."';}</script>";exit;
}
$page->get_table_info();
$page->set_default_config(array('calender'=>true,'jvalidate'=>true));

if (isset($_GET['pid'])) {
	switch($_GET['step']) {
	case 1:
		$page->select_pages($_GET['pid']);
		break;
	case 2:
		$uid = isset($_GET['uid']) ? $_GET['uid'] : $this->userid;
		$page->select_users_pages($uid);
		break;
	default:
		break;
	}
}
elseif(isset($_POST['update'])) {
	$ac->update_pages();
}
elseif(isset($_GET['js_search_form'])) {
	$ary = $page->get_search_form_settings();
	$page->assign('search_form', $ary);	
	$page->assign('config', $config);
	$page->display($config['templs']['search']);
}
elseif(isset($_GET['js_edit_form'])) {
	$ary = $page->get_edit_form_settings();
	$record = $page->get_record($_GET['id']);
	$page->assign('record', $record);	
	if(isset($_GET['tr'])) $page->assign('form_id', 'ef_'.$_GET['id'].'-'.$_GET['tr']);
	else $page->assign('form_id', 'ef_'.$_GET['id']);
	$page->assign('edit_form', $ary);	
	$page->assign('config', $config);
	$page->display($config['templs']['edit']);
}
elseif(isset($_GET['js_add_form'])) {
	$ary = $page->get_add_form_settings();
	$page->assign('add_form', $ary);	
	$page->assign('config', $config);
	$page->display($config['templs']['add']);
}
elseif(isset($_POST['js_edit_column'])) {
	$ret = $page->update_column(array('updatedby'=>$page->username));
	echo json_encode($ret);
}
elseif(isset($_REQUEST['action'])) {
	switch($_REQUEST['action']) {
		case 'edit':
		  $ary = $page->get_edit_form_settings();
		  echo json_encode($page->edit_table($ary));
			break;
		case 'delete':
			$page->delete($_GET['id']);
			break;
		case 'add':
			$last_pid = $page->create(array('createdby'=>$page->username, 'updatedby'=>$page->username, 'created'=>'NOW()'));
                        $query = "UPDATE pages AS p, (SELECT name FROM sites WHERE site_id=".$_POST['site_id']." ) AS s SET p.sname = s.name WHERE pid=".$last_pid;
                        $affected = $page->mdb2->exec($query);
                        if (PEAR::isError($affected)) {
                                die($affected->getMessage().': ' . $query . ". line: " . __LINE__);
                        }
			break;    
		default:
			break;
	}
}
else if( isset($_POST['search']) || (isset($_GET['page']) && isset($_GET['sort'])) || isset($_GET['page']) || isset($_GET['js_reload_list']) ) {
	if(isset($_POST['search'])) $page->parse();

	$data = $page->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $page->get_header($page->get_mappings());

	$pagination = $page->draw( $data['current_page'], $data['total_pages'] );
	
	$page->assign('config', $config);
	$page->assign('header', $header);
	$page->assign('data', $data);
	$page->assign("pagination", $pagination);
	$tpl = $page->get_html_template();
	$page->display($tpl); // not use display, should use AJAX.
}
// default: list data.
else {
	if (isset($_SESSION[$page->self][$page->session['sql']])) $_SESSION[$page->self][$page->session['sql']] = '';

	$total_rows = $page->get_total_rows($page->get_count_sql());

	$_SESSION[$page->self][$page->session['rows']] = $total_rows < 1 ? 1 : $total_rows;
	
	$data = $page->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $page->get_header($page->get_mappings());

	$pagination = $page->draw( $data['current_page'], $data['total_pages'] );
	
	$tpl = $page->get_html_template();

	$page->assign('config', $config);
	$page->assign('header', $header);
	$page->assign('data', $data);
	$page->assign("pagination", $pagination);

	$page->assign("template", $tpl);
	$page->display($config['templs']['main']);
}
?>
