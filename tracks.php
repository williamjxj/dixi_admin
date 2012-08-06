<?php
session_start();
error_reporting(E_ALL);
define('SITEROOT', './');

ini_set('include_path',ini_get('include_path').PATH_SEPARATOR.SITEROOT.'configs/'.PATH_SEPARATOR.SITEROOT.'include/');
require_once("setting.inc.php");
require_once("config.inc.php");
require_once("ListAdvanced.inc.php");
global $config;

class TracksClass extends ListAdvanced
{
  var $url, $self, $html, $session, $allowedTags;
  public function __construct() {
    parent::__construct();
	$this->mdb1 = $this->pear_connect_admin();
	$this->mdb2 = $this->mdb1;
	$this->allowedTags = ALLOWTAGS;
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
		die($mdb2->getMessage() . ' - line: ' . __LINE__);
	}
	return $mdb2;
  }
  
  function get_record($id) {
  	$query = "SELECT * FROM tracks WHERE tid=".$id;
	$res = $this->mdb2->query($query);
	$row = $res->fetchRow(MDB2_FETCHMODE_ASSOC);
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
			'name' => 'tid',
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
			'name' => 'tid',
			'index' => 'ID',
		)
	);
  } 
  function edit_wysiwyg_table()
  {
	if (get_magic_quotes_gpc()) {
		$title = trim($_POST['title']);
		$notes = trim($_POST['notes']);
		$content = $_POST['wysiwyg'];
	}
	else {
		$title = $this->mdb2->escape(trim($_POST['title']));
		$notes = $this->mdb2->escape(trim($_POST['notes']));
		$content = $this->mdb2->escape($_POST['wysiwyg']); //addslashes
	}
	$id = $_POST['tid'];
	if(isset($this->username)) $username = $this->username;
	else $username = 'Tester';

	$content = strip_tags($content,$this->allowedTags);

	$query = "UPDATE tracks set content = '" . $content . "', " .
		 "title = '" . $title . "', notes = '".$notes."', updatedby='".$username."' WHERE tid =  " . $id; 
	//$this->print_array($query);
	$affected = $this->mdb2->exec($query);
	if (PEAR::isError($affected)) {
		die($affected->getMessage());
	}

	$query = "SELECT * FROM tracks WHERE tid=" . $id;
	$row = $this->mdb2->queryRow($query, '', MDB2_FETCHMODE_ASSOC);

	$ary = array();
	$ary['tid'] = $id;
	$ary['title'] = $row['title'];
	$ary['notes'] = $row['notes'];
	$ary['content'] = $row['content'];
	$encodedArray = array_map("utf8_encode", $ary);
	return $encodedArray;
  }

  function add()
  {
	if (get_magic_quotes_gpc()) {
		$title = trim($_POST['title']);
		$notes = trim($_POST['notes']);
		$content = $_POST['wysiwyg'];
	}
	else {
		$title = $this->mdb2->escape(trim($_POST['title']));
		$notes = $this->mdb2->escape(trim($_POST['notes']));
		$content = $this->mdb2->escape($_POST['wysiwyg']); //addslashes
	}
  	if(isset($this->username)) $username = $this->username;
	else $username = 'Tester';

	$content = strip_tags($content,$this->allowedTags);

	$query = "INSERT INTO tracks (title,notes,content,createdby,created,updatedby, updated)
		VALUES('".$title."', '".$notes."', '".$content."', '".$username."', NOW(), '', NULL)";

	$affected = $this->mdb2->exec($query);
	if (PEAR::isError($affected)) {
		die($affected->getMessage() . ' - line: ' . __LINE__);
	}
	$id = $this->mdb2->lastInsertID();
	$query = "SELECT * FROM tracks WHERE tid=" . $id;

	$row = $this->mdb2->queryRow($query, '', MDB2_FETCHMODE_ASSOC);
	$ary = array();
	$ary['tid'] = $id;
	$ary['title'] = $row['title'];
	$ary['notes'] = $row['notes'];
	$ary['content'] = $row['content'];
	$encodedArray = array_map("utf8_encode", $ary);
	return $encodedArray;
  }
}

$tk = new TracksClass() or die("Can't generate the instance.");
/*if(! $tk->check_access($tk->mdb1)) {
  $tk->set_breakpoint();
  echo "<script>if(window.opener){window.opener.location.href='".LOGIN."';} else{window.parent.location.href='".LOGIN."';}</script>";exit;
}*/
$tk->get_table_info();
$config['tabs'] = array('1'=>'跟踪与记录列表',  '2'=>'添加跟踪与记录', '3'=> '更新跟踪与记录');
$tk->set_default_config(array('WYSIWYG'=>true,'calender'=>true));

if(isset($_REQUEST['action'])) {
	switch($_REQUEST['action']) {
		case 'delete':
			$tk->delete($_GET['id']);
			break;
		case 'edit':
			$ary = $tk->get_edit_form_settings();
			echo json_encode($tk->edit_table($ary));
			break;
		default:
			break;
	}
}
elseif(isset($_GET['js_search_form'])) {
	$ary = $tk->get_search_form_settings();
	$tk->assign('search_form', $ary);	
	$tk->assign('config', $config);
	$tk->display($config['templs']['search']);
}
elseif(isset($_GET['js_edit_form'])) {
	$ary = $tk->get_edit_form_settings();
	$record = $tk->get_record($_GET['id']);
	$tk->assign('record', $record);	
	$tk->assign('form_id', 'ef_'.$_GET['id']);	
	$tk->assign('edit_form', $ary);	
	$tk->assign('config', $config);
	$tk->display($config['templs']['edit']);
}
elseif(isset($_GET['js_edit_form_3'])) {
  $data = array();
  if(!isset($data['self'])) $data['self'] = $tk->self;

  if(isset($_GET['id']))
	$record = $tk->get_record($_GET['id']);
  else
  	$record = array();

  $data['record'] = $record;  
  $tk->assign('config', $config);
  $tk->assign("data", $data);
  $tk->display($config['path'].'templates/wysiwyg.tpl.html');
}
else if( isset($_POST['search']) || (isset($_GET['page']) && isset($_GET['sort'])) || isset($_GET['page']) || isset($_GET['js_reload_list']) ) {
	if(isset($_POST['search'])) $tk->parse();

	$data = $tk->list_all();
	$data['options'] = array(VIEW, EDIT, DELETE);
	
	$header = $tk->get_header($tk->get_mappings());

	$pagination = $tk->draw( $data['current_page'], $data['total_pages'] );
	
	$tk->assign('config', $config);
	$tk->assign('header', $header);
	$tk->assign('data', $data);
	$tk->assign("pagination", $pagination);
	$tpl = $tk->get_html_template();
	$tk->display($tpl); // not use display, should use AJAX.
}
elseif(isset($_GET['js_add_form'])) {
	$tk->assign('config', $config);
	$tk->display($config['path'].'templates/wysiwyg.tpl.html');	
}
elseif(isset($_POST['js_add'])) {
	$ret = $tk->add();
	echo json_encode($ret);
}
elseif(isset($_POST['js_edit'])) {
	$ret = $tk->edit_wysiwyg_table();
	echo json_encode($ret);
}
elseif(isset($_POST['js_edit_column'])) {
	$ret = $tk->update_column(array('updatedby'=>$tk->username));
	echo json_encode($ret);
}
else {
	if (isset($_SESSION[$tk->self][$tk->session['sql']])) $_SESSION[$tk->self][$tk->session['sql']] = '';

	$total_rows = $tk->get_total_rows($tk->get_count_sql());

	$_SESSION[$tk->self][$tk->session['rows']] = $total_rows < 1 ? 1 : $total_rows;
	
	$data = $tk->list_all();
	$data['options'] = array(VIEW, EDIT, DELETE);
	
	$header = $tk->get_header($tk->get_mappings());

	$pagination = $tk->draw( $data['current_page'], $data['total_pages'] );
	
	$tpl = $tk->get_html_template();

	$tk->assign('config', $config);
	$tk->assign('header', $header);
	$tk->assign('data', $data);
	$tk->assign("pagination", $pagination);
	$tk->assign("template", $tpl);

	$tk->display($config['templs']['main']);
}
?>
