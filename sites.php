<?php
session_start();
error_reporting(E_ALL);
define('SITEROOT', './');

ini_set('include_path',ini_get('include_path').PATH_SEPARATOR.SITEROOT.'configs/'.PATH_SEPARATOR.SITEROOT.'include/');
require_once("setting.inc.php");
require_once("config.inc.php");
require_once("ListAdvanced.inc.php");
global $config;


$config['tabs'] = array('1'=>'站点列表', '2'=>'添加底细真相子站点');


class SitesClass extends ListAdvanced
{
  var $url, $self, $session;
  public function __construct() {
    parent::__construct();
  }

  function get_search_form_settings() {
	return array(
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
			'name' => 'url',
		),/*
		array(
			'type' => 'date',
			'display_name' => 'Created:',
			'name' => 'created',
			'size' => INPUT_SIZE,
		),*/
		array(
			'type' => 'textarea',
			'display_name' => 'description:',
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
			'name' => 'site_id',
			'db_type' => 'int',
		)
	);
  }

  function get_add_form_settings() {
	return array(
		array(
		  'type' => 'text',
		  'display_name' => 'Site Name:',
		  'id' => 'site_name',
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
		  'display_name' => 'Users Group:',
		  'id' => 'level',
		  'name' => 'level',
		  'call_func' => 'get_level',
		),
    );
  }
	function select_users_sites($uid, $cid=0) {
		$sql = "SELECT us.sid, s.name FROM users_sites us inner join sites s on us.sid=s.sid and uid=".$uid;
		$str = '';
		$res = $this->mdb2->query($sql);
		while($row = $res->fetchRow()) {
			$str .= '<option value="'.$row[0].'">'.$row[1]."</option>\n";			
		}
		echo $str;
	}
	function update_sites() {
		$uid = $_POST['uid']?$_POST['uid']:$this->userid;
		$sids = explode(",", $_POST['sids']);
		$sql = "DELETE FROM users_sites WHERE uid=". $uid;

		$affected = $this->mdb2->exec($sql);
		if (PEAR::isError($affected)) {
			die($affected->getMessage());
		}
		foreach($sids as $sid) {
			$sql = "INSERT INTO users_sites(uid, sid) VALUES(". $uid. ", ". $sid . ")";
			echo $sql."\n";
			$affected = $this->mdb2->exec($sql);
			if (PEAR::isError($affected)) {
				continue;
			}
		}
		return true;
	}

}

$site = new SitesClass();
if(! $site->check_access()) {
  $site->set_breakpoint();
  echo "<script>if(window.opener){window.opener.location.href='".LOGIN."';} else{window.parent.location.href='".LOGIN."';}</script>";exit;
}
$site->get_table_info();
$site->set_default_config(array('calender'=>true,'jvalidate'=>true));


if (isset($_GET['cid'])) {
	switch($_GET['step']) {
	case 1:
		$sql = "SELECT sid, name FROM sites WHERE active='Y' and sid not in (SELECT sid FROM users_sites WHERE uid=".$this->userid.") ORDER BY name";
		$site->get_select_options($sql);
		break;
	case 2:
		$uid = isset($_GET['uid']) ? $_GET['uid'] : $this->userid;
		$site->select_users_sites($uid);
		break;
	default:
		break;
	}
}
elseif(isset($_POST['update'])) {
	$site->update_sites();
}
elseif(isset($_GET['js_search_form'])) {
	$ary = $site->get_search_form_settings();
	$site->assign('search_form', $ary);	
	$site->assign('config', $config);
	$site->display($config['templs']['search']);
}
elseif(isset($_GET['js_edit_form'])) {
	$ary = $site->get_edit_form_settings();
	$record = $site->get_record($_GET['id']);
	$site->assign('record', $record);	

	if(isset($_GET['tr'])) $site->assign('form_id', 'ef_'.$_GET['id'].'-'.$_GET['tr']);
	else $site->assign('form_id', 'ef_'.$_GET['id']);

	$site->assign('edit_form', $ary);	
	$site->assign('config', $config);
	$site->display($config['templs']['edit']);
}
elseif(isset($_GET['js_add_form'])) {
	$ary = $site->get_add_form_settings();
	$site->assign('add_form', $ary);
	$site->assign('config', $config);
	
	$site->display($config['templs']['add']);
}
elseif(isset($_POST['js_edit_column'])) {
	$ret = $site->update_column(array('updatedby'=>$site->username));
	echo json_encode($ret);
}
elseif(isset($_REQUEST['action'])) {
	switch($_REQUEST['action']) {
		case 'edit':
		  $ary = $site->get_edit_form_settings();
		  echo json_encode($site->edit_table($ary));
			break;
		case 'delete':
			$site->delete($_GET['id']);
			break;
		case 'add':
			$site->create(array('createdby'=>$site->username, 'updatedby'=>$site->username, 'created'=>'NOW()'));
			break;
		default:
			break;
	}
}
else if( isset($_POST['search']) || (isset($_GET['page']) && isset($_GET['sort'])) || isset($_GET['page']) || isset($_GET['js_reload_list']) ) {
	if(isset($_POST['search'])) $site->parse();

	$data = $site->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $site->get_header($site->get_mappings());

	$pagination = $site->draw( $data['current_page'], $data['total_pages'] );
	
	$site->assign('config', $config);
	$site->assign('header', $header);
	$site->assign('data', $data);
	$site->assign("pagination", $pagination);
	$tpl = $site->get_html_template();
	$site->display($tpl); // not use display, should use AJAX.
}
// default: list data.
else {
	if (isset($_SESSION[$site->self][$site->session['sql']])) $_SESSION[$site->self][$site->session['sql']] = '';

	$total_rows = $site->get_total_rows($site->get_count_sql());

	$_SESSION[$site->self][$site->session['rows']] = $total_rows < 1 ? 1 : $total_rows;
	
	$data = $site->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $site->get_header($site->get_mappings());

	$pagination = $site->draw( $data['current_page'], $data['total_pages'] );
	
	$tpl = $site->get_html_template();

	$site->assign('config', $config);
	$site->assign('header', $header);
	$site->assign('data', $data);
	$site->assign("pagination", $pagination);

	$site->assign("template", $tpl);
	$site->display($config['templs']['main']);
}
?>
