<?php
session_start();
error_reporting(E_ALL);
define('SITEROOT', './');

ini_set('include_path',ini_get('include_path').PATH_SEPARATOR.SITEROOT.'configs/'.PATH_SEPARATOR.SITEROOT.'include/');
require_once("setting.inc.php");
require_once("config.inc.php");
require_once("ListAdvanced.inc.php");
global $config;


// $config['tabs'] = array('1'=>'List Resources', '2'=>'Add Resources', '3'=>'Resources - Contents');
$config['tabs'] = array('1'=>'资源列表', '2'=>'添加资源');
$config['plupload'] = true;

class ResourcesClass extends ListAdvanced
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
		  'type' => 'select',
		  'display_name' => 'Modules:',
		  'id' => 'mid_s',
		  'name' => 'mid',
		  'call_func' => 'get_modules_options',
		  'db_type' => 'int',
		),      
		array(
			'type' => 'select',
			'display_name' => 'Type:',
			'id' => 'type_s',
			'name' => 'type',
			'call_func' => 'get_types_options',
		),
		array(
			'type' => 'text',
			'display_name' => 'File:',
			'id' => 'file_s',
			'name' => 'file',
		),
		array(
			'type' => 'text',
			'display_name' => 'Author:',
			'id' => 'author_s',
			'name' => 'author',
		),
		array(
			'type' => 'text',
			'display_name' => 'CreatedBy:',
			'id' => 'createdby_s',
			'name' => 'createdby',
		),
		array(
			'type' => 'text',
			'display_name' => 'UpdatedBy:',
			'id' => 'updatedby_s',
			'name' => 'updatedby',
		),
		array(
			'type' => 'date',
			'display_name' => 'Created:',
			'id' => 'created_s',
			'name' => 'created',
			'size' => INPUT_SIZE,
		),
		array(
			'type' => 'date',
			'display_name' => 'Updated:',
			'id' => 'updated_s',
			'name' => 'updated',
			'size' => INPUT_SIZE,
		),
	);
  }

  function get_edit_form_settings() {
	return array(
		array(
		  'type' => 'text',
		  'display_name' => 'Site:',
		  'name_value' => 'site_id',
		  'name' => 'sname',
		),      
		array(
		  'type' => 'text',
		  'display_name' => 'Modules:',
		  'name_value' => 'mid',
		  'name' => 'mname',
		),      
		array(
			'type' => 'text',
			'display_name' => 'File:',
			'name' => 'file',
			'readonly' => 'readonly',
		),
		array(
			'type' => 'text',
			'display_name' => 'Author:',
			'name' => 'author',
		),
		array(
			'type' => 'text',
			'display_name' => 'CreatedBy:',
			'name' => 'createdby',
			'readonly' => 'readonly',
		),
		array(
			'type' => 'text',
			'display_name' => 'UpdatedBy:',
			'name' => 'updatedby',
		),
		array(
			'type' => 'textarea',
			'display_name' => 'Notes:',
			'name' => 'notes',
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
			'name' => 'rid',
			'db_type' => 'int',
		)
	);
  }

  function get_ftype($type)
  {
  	global $config;
	$ftype = substr($type, strpos($type, '/')+1);
	$ftype = strtolower($ftype);
	$ret = '';
	switch($ftype) {
	case 'pdf':
		$ret = '<img src="'.$config['path'].'images/icons/icon-pdf.png" border="0" width="16" height="16" title="'.$type.'" />';
		break;
	case 'html':
		$ret = '<img src="'.$config['path'].'images/icons/icon-htm.png" border="0" width="16" height="16" title="'.$type.'" />';
		break;
	case 'htm':
		$ret = '<img src="'.$config['path'].'images/icons/icon-html.png" border="0" width="16" height="16" title="'.$type.'" />';
		break;
	case 'xls':
		$ret = '<img src="'.$config['path'].'images/icons/icon-xls.png" border="0" width="16" height="16" title="'.$type.'" />';
		break;
	case 'xlsx':
		$ret = '<img src="'.$config['path'].'images/icons/icon-xlsx.png" border="0" width="16" height="16" title="'.$type.'" />';
		break;
	case 'csv':
		$ret = '<img src="'.$config['path'].'images/icons/icon-csv.png" border="0" width="16" height="16" title="'.$type.'" />';
		break;
	case 'txt':
		$ret = '<img src="'.$config['path'].'images/icons/icon-txt.png" border="0" width="16" height="16" title="'.$type.'" />';
		break;
	case 'jpg':
	case 'jpeg':
		$ret = '<img src="'.$config['path'].'images/icons/icon-jpg.png" border="0" width="16" height="16" title="'.$type.'" />';
		break;
	case 'png':
		$ret = '<img src="'.$config['path'].'images/icons/icon-png.png" border="0" width="16" height="16" title="'.$type.'" />';
		break;
	case 'gif':
		$ret = '<img src="'.$config['path'].'images/icons/icon-gif.png" border="0" width="16" height="16" title="'.$type.'" />';
		break;
	case 'xml':
		$ret = '<img src="'.$config['path'].'images/icons/icon-xml.png" border="0" width="16" height="16" title="'.$type.'" />';
		break;
	default:
		$ret = '<img src="'.$config['path'].'images/icons/icon-bak.png" border="0" width="16" height="16" title="'.$type.'" />';
	}
	return $ret;
  }

  function get_site_name($query) {
	if(isset($query) && $query) {
		if (preg_match("/,\s*site_id,/", $query))
			$query = preg_replace("/site_id/", '(select name from sites where resources.site_id=sites.site_id) as site_id', $query);
		return $query;
	}
	return;
  }
  function get_module_name($query) {
	if(isset($query) && $query) {
		if (preg_match("/,\s*mid,/", $query))
			$query = preg_replace("/mid/", '(select name from modules where modules.mid=resources.mid) as mid', $query);
		return $query;
	}
	return;
  }
  function get_resources_options_by_sid($site_id){
	$sql = "SELECT rid, file, author, (SELECT name FROM modules m where r.mid=m.mid) AS mname FROM resources r WHERE r.site_id=".$site_id." ORDER BY author";
	return 	$this->get_select_options($sql);
  }
  function get_resources_options_by_mid($module_id){
	$sql = "SELECT rid, file, author, (SELECT name FROM modules m WHERE m.mid=r.mid) AS mname FROM resources r WHERE mid=" . $module_id . " ORDER BY author";
	return 	$this->get_select_options($sql);
  }  
  function get_types_options()
  {
  	$sql = "SELECT DISTINCT type FROM resources";
	$res = $this->mdb2->query($sql);
	echo "\t<option value=''> --- Select --- </option>\n";

	while ($row=$res->fetchRow()) {
		echo "\t".'<option value="'.$row[0].'">'.$row[0]."</option>\n";
	}
	//return $this->get_select_options($sql);
  }

  function update_contents_resources()
  {
		$rid = $_POST['rid'];
		$mids = explode(",", $_POST['mids']);

		$sql = "DELETE FROM contents_resources WHERE rid=". $rid;
		$affected = $this->mdb2->exec($sql);
		if (PEAR::isError($affected)) {
			die($affected->getMessage());
		}
		foreach($mids as $cid) {
			$sql = "INSERT INTO contents_resources(cid, rid, createdby) VALUES(". $cid. ", ".$rid.", '".$this->username."')";
			echo $sql."\n";
			$affected = $this->mdb2->exec($sql);
			if (PEAR::isError($affected)) {
				continue;
			}
		}
		return true;
  }
  function get_resource_record($rid) {
  	$ary = array();	
  	$query = "SELECT * FROM resources WHERE rid=".$rid; //vw_resources

	$result = $this->mdb2->query($query);
	$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);

	$ary['rid'] = $rid;
	$ary['file'] = $row['file'];
	$ary['author'] = $row['author'];
	$ary['site_id'] = $row['site_id'];
	$ary['sname'] = $row['sname'];
	$ary['mid'] = $row['mid'];
	$ary['mname'] = $row['mname'];
	$encodedArray = array_map("utf8_encode", $ary);
	return $encodedArray;
  }
  function select_assigned_contents($rid, $site_id)
  {
	$sqlc = "SELECT c.cid, concat(linkname,'-',author) as linkname, author FROM contents c INNER JOIN contents_resources cr WHERE cr.cid = c.cid AND active='Y' AND cr.rid=".$rid." AND c.site_id=".$site_id." ORDER BY linkname ";
	return 	$this->get_select_options($sqlc);
  }  
  function select_available_contents($rid, $site_id)
  {
	$sqlc = "SELECT cid, concat(linkname,'-',author) as linkname, author FROM contents c WHERE c.cid NOT IN (SELECT cid from contents_resources WHERE rid=".$rid.") AND active='Y' AND c.site_id=".$site_id." ORDER BY linkname";
	return 	$this->get_select_options($sqlc);
  }

}

$rese = new ResourcesClass();
if(! $rese->check_access()) {
  $rese->set_breakpoint();
  echo "<script>if(window.opener){window.opener.location.href='".LOGIN."';} else{window.parent.location.href='".LOGIN."';}</script>";exit;
}

$rese->get_table_info();
$rese->set_default_config(array('qtip'=>true, 'plupload'=>true, 'calender'=>true));


if(isset($_GET['js_search_form'])) {
	$ary = $rese->get_search_form_settings();
	$rese->assign('search_form', $ary);	
	$rese->assign('config', $config);
	$rese->display($config['templs']['search']);
}
elseif(isset($_GET['js_edit_form'])) {
	$ary = $rese->get_edit_form_settings();
	$record = $rese->get_record($_GET['id']);
	$rese->assign('record', $record);	

	if(isset($_GET['tr'])) $rese->assign('form_id', 'ef_'.$_GET['id'].'-'.$_GET['tr']);
	else $rese->assign('form_id', 'ef_'.$_GET['id']);

	$rese->assign('edit_form', $ary);	
	$rese->assign('config', $config);
	$rese->display($config['templs']['edit']);
}
elseif(isset($_GET['js_edit_form_3'])) {
	$data = array();
	if(!isset($data['self'])) $data['self'] = $rese->self;
	if(isset($_GET['id']))
		$record = $rese->get_resource_record($_GET['id']);
	else
		$record = array();

	$data['record'] = $record;
	$data['sites'] = $rese->get_sites_array();
	$data['modules'] = $rese->get_modules_array();
	$rese->assign('config', $config);
	$rese->assign('data', $data);
	$rese->display($config['templs']['assign_cr']); //'assign_contents-resources.tpl.html'
}
elseif(isset($_GET['js_add_form'])) {
	//$ary = $rese->get_add_form_settings();
	//$rese->get_add_form($ary);
	//$header = $rese->get_header($rese->get_mappings());
	//$rese->assign('header', $header);
	$data['sites'] = $rese->get_sites_array();
	$rese->assign('config', $config);
	$rese->assign('data', $data);
	$rese->display($config['templs']['add_plupload']); //'add_plupload.tpl.html'
}
// use $.load, so it is $_POST, not $_GET, use $_REQUEST for compatible.
elseif(isset($_REQUEST['js_get_modules'])) {
	$rese->get_modules_options($_REQUEST['site_id']);
}
elseif(isset($_REQUEST['js_get_resources_by_sid'])) {
	$rese->get_resources_options_by_sid($_REQUEST['site_id']);
}
elseif(isset($_REQUEST['js_get_resources_by_mid'])) {
	$rese->get_resources_options_by_mid($_REQUEST['mid']);
}
elseif (isset($_GET['rid']) && isset($_GET['step'])) {
	switch($_GET['step']) {
	case 1:
		$rese->select_assigned_contents($_GET['rid'], $_GET['site_id']);
		break;
	case 2:
		$rese->select_available_contents($_GET['rid'], $_GET['site_id']);
		break;
	}
}
elseif(isset($_POST['js_update'])) {
	$rese->update_contents_resources();
}

elseif(isset($_POST['js_edit_column'])) {
	$ret = $rese->update_column(array('updatedby'=>$rese->username));
	echo json_encode($ret);
}
elseif(isset($_REQUEST['action'])) {
	switch($_REQUEST['action']) {
		case 'edit':
		  $ary = $rese->get_edit_form_settings();
		  echo json_encode($rese->edit_table($ary));
			break;
		case 'delete':
			$rese->delete($_GET['id']);
			break;
		case 'add':
			$rese->create(array('createdby'=>$rese->username, 'updatedby'=>$rese->username, 'created'=>'NOW()'));
			break;    
		default:
			break;
	}
}
else if( isset($_POST['search']) || (isset($_GET['page']) && isset($_GET['sort'])) || isset($_GET['page']) || isset($_GET['js_reload_list']) ) {
	if(isset($_POST['search'])) $rese->parse();

	$data = $rese->list_all();
	// $data['options'] = array(EDIT, CONTENT, DELETE);
	$data['options'] = array(EDIT, DELETE);
	
	$header = $rese->get_header($rese->get_mappings());

	$pagination = $rese->draw( $data['current_page'], $data['total_pages'] );
	
	$rese->assign('config', $config);
	$rese->assign('header', $header);
	$rese->assign('data', $data);
	$rese->assign("pagination", $pagination);
	$tpl = $rese->get_html_template();
	$rese->display($tpl); // not use display, should use AJAX.
}
// default: list data.
else {
	if (isset($_SESSION[$rese->self][$rese->session['sql']])) $_SESSION[$rese->self][$rese->session['sql']] = '';

	$total_rows = $rese->get_total_rows($rese->get_count_sql());

	$_SESSION[$rese->self][$rese->session['rows']] = $total_rows < 1 ? 1 : $total_rows;
	
	$data = $rese->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $rese->get_header($rese->get_mappings());

	$pagination = $rese->draw( $data['current_page'], $data['total_pages'] );
	
	$tpl = $rese->get_html_template();

	$rese->assign('config', $config);
	$rese->assign('header', $header);
	$rese->assign('data', $data);
	$rese->assign("pagination", $pagination);

	$rese->assign("template", $tpl);
	$rese->display($config['templs']['main']);
}
?>
