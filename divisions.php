<?php
session_start();
error_reporting(E_ALL);
define('SITEROOT', './');

ini_set('include_path',ini_get('include_path').PATH_SEPARATOR.SITEROOT.'configs/'.PATH_SEPARATOR.SITEROOT.'include/');
require_once("setting.inc.php");
require_once("config.inc.php");
require_once("ListAdvanced.inc.php");
global $config;

$config['tabs'] = array('1'=>'List Division Groups', '2'=>'Add Division Group');

class DivisionsClass extends ListAdvanced
{
  var $url, $self, $session;
  public function __construct() {
    parent::__construct();
  }

  function get_search_form_settings() {
	return array(
		array(
			'type' => 'text',
			'display_name' => 'Division:',
			'id' => 'division_s',
			'name' => 'division',
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
			'type' => 'select',
			'display_name' => 'Site:',
			'id' => 'site_id_s',
			'name' => 'site_id',
			'db_type' => 'int',
			'call_func' => 'get_site',
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
			'display_name' => 'Division:',
			'name' => 'division',
		),
		array(
			'type' => 'textarea',
			'display_name' => 'description:',
			'name' => 'description',
		),
		array(
			'type' => 'date',
			'display_name' => 'Created:',
			'name' => 'created',
			'size' => INPUT_SIZE,
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
			'name' => 'division',
		)
	);
  }
  
  function get_division_by_id($division) {
  	$query = "SELECT division, name FROM divisions WHERE division=".$division;
	$row = $this->mdb2->queryRow($query);
	echo  "\t<option value=\"" . $row[0] . "\">$row[1]</option>\n";
  }
  
  function get_division() {
	$sql = "SELECT division, name FROM divisions ORDER BY division";
	$result = $this->mdb2->query($sql);
	echo "\t<option value=''> ------ </option>\n";
	while ($row=$result->fetchRow()) {
		echo "\t<option value=\"" . $row[0] . "\">$row[1]</option>\n";
	}
  }
  function get_site_name($query) {
	if(isset($query) && $query) {
		if (preg_match("/,\s*site_id,/", $query)) {
			$query = preg_replace("/site_id/", '(select name from sites where sites.site_id=components.site_id) as site_id', $query);
		}
		return $query;
	}
	return;
  }
  function get_add_form_settings() {
	return array(
		array(
		  'type' => 'text',
		  'display_name' => 'Division:',
		  'id' => 'division',
		  'name' => 'division',
		),
		array(
		  'type' => 'text',
		  'display_name' => 'Name:',
		  'id' => 'name',
		  'name' => 'name',
		),
		array(
			'type' => 'select',
			'display_name' => 'Site:',
			'id' => 'site_id',
			'name' => 'site_id',
			'db_type' => 'int',
			'call_func' => 'get_site', // unuse
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

$division = new DivisionsClass();
if(! $division->check_access()) {
  $division->set_breakpoint();
  echo "<script>if(window.opener){window.opener.location.href='".LOGIN."';} else{window.parent.location.href='".LOGIN."';}</script>";exit;
}
$division->get_table_info();
$division->set_default_config(array('calender'=>true,'jvalidate'=>true));

if(isset($_GET['js_search_form'])) {
	$ary = $division->get_search_form_settings();
	// $division->get_search_form($ary);
	$division->assign('search_form', $ary);	
	$division->assign('config', $config);
	// $division->print_array($config);
	$division->display($config['templs']['search']); //'search.tpl.html'
}
elseif(isset($_GET['js_edit_form'])) {
	$ary = $division->get_edit_form_settings();
	// $division->get_edit_form($_GET['id'], $ary);
	$record = $division->get_record($_GET['id']);

        if(isset($_GET['tr'])) $form_id = 'ef_'.$_GET['id'].'-'.$_GET['tr'];
        else $form_id = 'ef_'.$_GET['id'];

	$division->assign('form_id', $form_id);	
	$division->assign('record', $record);	
	$division->assign('edit_form', $ary);	
	$division->assign('config', $config);
	$division->display($config['templs']['edit']); //'edit.tpl.html');
}
elseif(isset($_GET['js_add_form'])) {
	$ary = $division->get_add_form_settings();
	// $division->get_add_form($ary);
	$division->assign('add_form', $ary);	
	$division->assign('config', $config);
	$division->display($config['templs']['add']); //'add.tpl.html'
}
elseif(isset($_POST['js_edit_column'])) {
	$ret = $division->update_column(array('updatedby'=>$division->username));
	echo json_encode($ret);
}
elseif(isset($_REQUEST['action'])) {
	switch($_REQUEST['action']) {
		case 'edit':
		  $ary = $division->get_edit_form_settings();
		  echo json_encode($division->edit_table($ary));
			break;
		case 'delete':
			$division->delete($_GET['id']);
			break;
		case 'add':
			$division->create(array('createdby'=>$division->username, 'updatedby'=>$division->username, 'created'=>'NOW()'));
			break;    
		default:
			break;
	}
}
else if( isset($_POST['search']) || (isset($_GET['page']) && isset($_GET['sort'])) || isset($_GET['page']) || isset($_GET['js_reload_list']) ) {
	if(isset($_POST['search'])) $division->parse();

	$data = $division->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $division->get_header($division->get_mappings());

	$pagination = $division->draw( $data['current_page'], $data['total_pages'] );
	
	$division->assign('config', $config);

	$division->assign('header', $header);
	$division->assign('data', $data);
	$division->assign("pagination", $pagination);
	$tpl = $division->get_html_template();
	$division->display($tpl); // not use display, should use AJAX.
}
else {
	if (isset($_SESSION[$division->self][$division->session['sql']])) $_SESSION[$division->self][$division->session['sql']] = '';

	$total_rows = $division->get_total_rows($division->get_count_sql());

	$_SESSION[$division->self][$division->session['rows']] = $total_rows < 1 ? 1 : $total_rows;
	
	$data = $division->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $division->get_header($division->get_mappings());

	$pagination = $division->draw( $data['current_page'], $data['total_pages'] );
	
	$tpl = $division->get_html_template();

	$division->assign('config', $config);
	$division->assign('header', $header);
	$division->assign('data', $data);
	$division->assign("pagination", $pagination);

	$division->assign("template", $tpl);
	$division->display($config['templs']['main']);
}
?>
