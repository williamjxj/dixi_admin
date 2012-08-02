<?php
session_start();
error_reporting(E_ALL);
define('SITEROOT', '../');

ini_set('include_path',ini_get('include_path').PATH_SEPARATOR.SITEROOT.'configs/'.PATH_SEPARATOR.SITEROOT.'include/');
require_once("setting.inc.php");
require_once("config.inc.php");
require_once("ListAdvanced.inc.php");
global $config;

class ReportsClass extends ListAdvanced
{
  var $url, $self, $html, $session;
  public function __construct() {
    parent::__construct();
	$this->mdb2 = $this->pear_connect_williamjxj();
	$this->mdb1 = $this->pear_connect_admin();
  }
  function pear_connect_williamjxj() {
	if(($_SERVER['SERVER_ADDR'] == '74.53.88.82') || preg_match("/cdat\.com/", $_SERVER['SERVER_NAME']) ) {
		$dsn = array (
			"phptype" => "mysqli",
			"username" => "cdatcom_william",
			"password" => "Benjamin001",
			"hostspec" => "localhost",
			"database" => "cdatcom_williamjxj"
		);
	}
	else {  
		$dsn = array (
			"phptype" => "mysqli",
			"username" => "williamjxj",
			"password" => "Benjamin001",
			"hostspec" => "localhost",
			"database" => "williamjxj"
		);
	}
	$options = array(
		'debug'       => 2,
		'persistent'  => true,
		'portability' => MDB2_PORTABILITY_ALL,
	);	
	$mdb2 = MDB2::factory($dsn, $options);
	if (PEAR::isError($mdb2)) {
		die($mdb2->getMessage());
	}
	return $mdb2;
  }
  
  function get_record($id) {
  	$query = "SELECT * FROM reports WHERE rid=".$id;
	$res = $this->mdb2->query($query);
	$row = $res->fetchRow(MDB2_FETCHMODE_ASSOC);
	//$this->print_array($row);
	return $row;
  }
  
  function get_search_form_settings() {
	return array(
		array(
			'type' => 'text',
			'display_name' => 'Title:',
			'id' => 'title_s',
			'name' => 'title',
		),
		array(
			'type' => 'textarea',
			'display_name' => 'Notes:',
			'id' => 'notes_s',
			'name' => 'notes',
		),
		array(
			'type' => 'date',
			'display_name' => 'Created Date:',
			'id' => 'created_s',
			'name' => 'created',
			'size' => INPUT_SIZE,
		),
		array(
			'type' => 'date',
			'display_name' => 'Updated Date:',
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
			'display_name' => 'ID:',
			'name' => 'rid',
		),
		array(
			'type' => 'text',
			'display_name' => 'Title:',
			'name' => 'title',
		),
		array(
			'type' => 'text',
			'display_name' => 'Created By:',
			'name' => 'createdby',
		),
		array(
			'type' => 'text',
			'display_name' => 'Updated By:',
			'name' => 'updatedby',
		),
		/*array(
			'type' => 'textarea',
			'display_name' => 'Content:',
			'name' => 'content',
		),*/
		array(
			'type' => 'date',
			'display_name' => 'Created Date:',
			'name' => 'created',
			'size' => INPUT_SIZE,
		),
		array(
			'type' => 'date',
			'display_name' => 'Updated Date:',
			'name' => 'updated',
			'size' => INPUT_SIZE,
		),
		array(
			'type' => 'textarea',
			'display_name' => 'Notes:',
			'name' => 'notes',
			'size' => INPUT_SIZE*2,
		),
		array(
			'type' => 'hidden',
			'name' => 'rid',
			'index' => 'ID',
		)
	);
  } 
  function edit_wysiwyg_table()
  {
	$title = $this->mdb2->escape(trim($_POST['title']));
	$notes = get_magic_quotes_gpc()? addslashes($_POST['notes']) : $_POST['notes'];
	$id = $_POST['id'];
	foreach($_POST as $k=>$v) if(preg_match("/^elm_/", $k)) {
		if (get_magic_quotes_gpc()) $content = $v;
		else $content = addslashes($v);
	}

	$query = "UPDATE reports set content = '" . $content . "', " .
		 "title = '" . $title . "', notes = '".$notes."' WHERE rid =  " . $id; 
	$this->print_array($query);
	$affected = $this->mdb2->exec($query);
	if (PEAR::isError($affected)) {
		die($affected->getMessage());
	}

	$query = "SELECT * FROM reports WHERE rid=" . $id;
	$row = $this->mdb2->queryRow($query, '', MDB2_FETCHMODE_ASSOC);

	$ary = array();
	$ary['rid'] = $id;
	$ary['title'] = $row['title'];
	$ary['notes'] = $row['notes'];
	$ary['content'] = $row['content'];
	$encodedArray = array_map("utf8_encode", $ary);
	return $encodedArray;
  }

  function add()
  {
	$title = $this->mdb2->escape(trim($_POST['title_3']));
	$notes = $this->mdb2->escape(trim($_POST['notes_3']));
	if (get_magic_quotes_gpc()) $content = $_POST['elm3'];
	else $content = addslashes($_POST['elm3']);

	$query = "INSERT INTO reports (title,notes,content,createdby,created,updatedby)
		VALUES('".$title."', '".$notes."', '".$content."', '".$this->username."', NOW(), '".$this->username."')";

	$affected = $this->mdb2->exec($query);
	if (PEAR::isError($affected)) {
		die($affected->getMessage());
	}
	$id = $this->mdb2->lastInsertID();
	$query = "SELECT * FROM reports WHERE rid=" . $id;

	$row = $this->mdb2->queryRow($query, '', MDB2_FETCHMODE_ASSOC);
	$ary = array();
	$ary['rid'] = $id;
	$ary['title'] = $row['title'];
	$ary['notes'] = $row['notes'];
	$ary['content'] = $row['content'];
	$encodedArray = array_map("utf8_encode", $ary);
	return $encodedArray;
  }
}

$rp = new ReportsClass() or die("Can't generate the instance.");
if(! $rp->check_access($rp->mdb1)) {
  $rp->set_breakpoint();
  echo "<script>if(window.opener){window.opener.location.href='".LOGIN."';} else{window.parent.location.href='".LOGIN."';}</script>";exit;
}
$rp->get_table_info();
$config['tabs'] = array('1'=>'List Reports',  '2'=>'Add Report', '3'=> 'Update Reports');
$rp->set_default_config(array('WYSIWYG'=>true,'calender'=>true));

if(isset($_REQUEST['action'])) {
	switch($_REQUEST['action']) {
		case 'delete':
			$rp->delete($_GET['id']);
			break;
		case 'edit':
			$ary = $rp->get_edit_form_settings();
			echo json_encode($rp->edit_table($ary));
			break;
		default:
			break;
	}
}
elseif(isset($_GET['js_search_form'])) {
	$ary = $rp->get_search_form_settings();
	$rp->assign('search_form', $ary);	
	$rp->assign('config', $config);
	$rp->display($config['templs']['search']);
}
elseif(isset($_GET['js_edit_form'])) {
	$ary = $rp->get_edit_form_settings();
	$record = $rp->get_record($_GET['id']);
	$rp->assign('record', $record);	
	$rp->assign('form_id', 'ef_'.$_GET['id']);	
	$rp->assign('edit_form', $ary);	
	$rp->assign('config', $config);
	$rp->display($config['templs']['edit']);
}
elseif(isset($_GET['js_edit_form_3'])) {
  $data = array();
  if(!isset($data['self'])) $data['self'] = $rp->self;

  if(isset($_GET['id']))
	$record = $rp->get_record($_GET['id']);
  else
  	$record = array();

  $data['record'] = $record;  
  $rp->assign('config', $config);
  $rp->assign("data", $data);
  $rp->display($config['path'].'templates/edit_tinymce2.tpl.html');
}
else if( isset($_POST['search']) || (isset($_GET['page']) && isset($_GET['sort'])) || isset($_GET['page']) ) {
	if(isset($_POST['search'])) $rp->parse();

	$data = $rp->list_all();
	$data['options'] = array(VIEW, EDIT, DELETE);
	
	$header = $rp->get_header($rp->get_mappings());

	$pagination = $rp->draw( $data['current_page'], $data['total_pages'] );
	
	$rp->assign('config', $config);
	$rp->assign('header', $header);
	$rp->assign('data', $data);
	$rp->assign("pagination", $pagination);
	$tpl = $rp->get_html_template();
	$rp->display($tpl); // not use display, should use AJAX.
}
elseif(isset($_GET['js_add_form'])) {
	$rp->assign('config', $config);
	$rp->display($config['path'].'templates/add_tinymce2.tpl.html');	
}
elseif(isset($_POST['add_submit'])) {
	$ret = $rp->add();
	echo json_encode($ret);
}
elseif(isset($_POST['js_edit_column'])) {
	$ret = $rp->update_column(array('updatedby'=>$rp->username));
	echo json_encode($ret);
}
elseif(isset($_POST['edit_submit'])) {
	$ret = $rp->edit_wysiwyg_table();
	echo json_encode($ret);
}
else {
	if (isset($_SESSION[$rp->self][$rp->session['sql']])) $_SESSION[$rp->self][$rp->session['sql']] = '';

	$total_rows = $rp->get_total_rows($rp->get_count_sql());

	$_SESSION[$rp->self][$rp->session['rows']] = $total_rows < 1 ? 1 : $total_rows;
	
	$data = $rp->list_all();
	$data['options'] = array(VIEW, EDIT, DELETE);
	
	$header = $rp->get_header($rp->get_mappings());

	$pagination = $rp->draw( $data['current_page'], $data['total_pages'] );
	
	$tpl = $rp->get_html_template();

	$rp->assign('config', $config);
	$rp->assign('header', $header);
	$rp->assign('data', $data);
	$rp->assign("pagination", $pagination);
	$rp->assign("template", $tpl);

	$rp->display($config['templs']['main']);
}
?>
