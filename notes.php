<?php
session_start();
error_reporting(E_ALL);
define('SITEROOT', './');

ini_set('include_path',ini_get('include_path').PATH_SEPARATOR.SITEROOT.'configs/'.PATH_SEPARATOR.SITEROOT.'include/');
require_once("setting.inc.php");
require_once("config.inc.php");
require_once("ListAdvanced.inc.php");
global $config;


class NotesClass extends ListAdvanced
{
  var $url, $self, $session;
  public function __construct() { parent::__construct(); }

  function get_search_form_settings() {
	return array(
		array(
			'type' => 'text',
			'display_name' => 'File:',
			'id' => 'file_s',
			'name' => 'file',
		),
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
			'display_name' => 'Created:',
			'id' => 'created_s',
			'name' => 'created',
			'size' => INPUT_SIZE,
		),
	);
  }

  function get_edit_form_settings() {
	return array(
		array(
			'type' => 'text',
			'display_name' => 'File:',
			'name' => 'file',
			'readonly' => 'readonly',
		),
		array(
			'type' => 'text',
			'display_name' => 'Title:',
			'name' => 'title',
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
	case 'xml':
		$ret = '<img src="'.$config['path'].'images/icons/icon-xml.png" border="0" width="16" height="16" title="'.$type.'" />';
		break;
	default:
		$ret = '<img src="'.$config['path'].'images/icons/icon-bak.png" border="0" width="16" height="16" title="'.$type.'" />';
	}
	return $ret;
  }

}

$note = new NotesClass() or die("Can't generate the instance.");
if(! $note->check_access()) {
  echo "<script>if(window.opener){window.opener.location.href='".LOGIN."';} else{window.parent.location.href='".LOGIN."';}</script>";exit;
}
$note->get_table_info();
$note->set_default_config(array('colorbox'=>true, 'calender'=>true));


if(isset($_REQUEST['action'])) {
	switch($_REQUEST['action']) {
		case 'delete':
			$note->delete($_GET['id']);
			break;
		case 'edit':
      $ary = $note->get_edit_form_settings();
      echo json_encode($note->edit_table($ary));
			break;
		default:
			break;
	}
}
elseif(isset($_GET['js_search_form'])) {
	$ary = $note->get_search_form_settings();
	$note->assign('search_form', $ary);	
	$note->assign('config', $config);
	$note->display($config['templs']['search']); //'search.tpl.html'
}
elseif(isset($_GET['js_edit_form'])) {
	$ary = $note->get_edit_form_settings();
	$record = $note->get_record($_GET['id']);

        if(isset($_GET['tr'])) $form_id = 'ef_'.$_GET['id'].'-'.$_GET['tr'];
        else $form_id = 'ef_'.$_GET['id'];

	$note->assign('form_id', $form_id);	
	$note->assign('record', $record);	
	$note->assign('edit_form', $ary);	
	$note->assign('config', $config);
	$note->display($config['templs']['edit']); 	//'edit.tpl.html'
}
elseif(isset($_POST['js_edit_column'])) {
	$ret = $note->update_column(array('updatedby'=>$note->username));
	echo json_encode($ret);
}
else if( isset($_POST['search']) || (isset($_GET['page']) && isset($_GET['sort'])) || isset($_GET['page']) ) {
	if(isset($_POST['search'])) $note->parse();

	$data = $note->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $note->get_header($note->get_mappings());
	$pagination = $note->draw( $data['current_page'], $data['total_pages'] );
	
	$note->assign('config', $config);
	$note->assign('header', $header);
	$note->assign('data', $data);
	$note->assign("pagination", $pagination);
	$tpl = $note->get_html_template();
	$note->display($tpl); // not use display, should use AJAX.
}
// default: list data.
else {
	if (isset($_SESSION[$note->self][$note->session['sql']])) $_SESSION[$note->self][$note->session['sql']] = '';

	$total_rows = $note->get_total_rows($note->get_count_sql());

	$_SESSION[$note->self][$note->session['rows']] = $total_rows < 1 ? 1 : $total_rows;
	
	$data = $note->list_all();
	$data['options'] = array('edit', 'delete');
	$header = $note->get_header($note->get_mappings());
	$pagination = $note->draw( $data['current_page'], $data['total_pages'] );

	$note->assign('config', $config);
	$note->assign('header', $header);
	$note->assign('data', $data);
	$note->assign("pagination", $pagination);
	$tpl = $note->get_html_template();
	$note->assign("template", $tpl);

	$note->display($config['templs']['main']);
}

$note->clear_array($note->data);
?>
