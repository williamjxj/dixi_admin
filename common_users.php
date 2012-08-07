<?php
// alter table divisions add column sname varchar(255) default null after site_id
session_start();
error_reporting(E_ALL);
define('SITEROOT', './');

ini_set('include_path',ini_get('include_path').PATH_SEPARATOR.SITEROOT.'configs/'.PATH_SEPARATOR.SITEROOT.'include/');
require_once("setting.inc.php");
require_once("config.inc.php");
require_once("ListAdvanced.inc.php");
global $config;

class CommonUsersClass extends ListAdvanced
{
  var $url, $self, $session;
  public function __construct() {
    parent::__construct();
  }

  function get_search_form_settings() {
	return array(
		array(
			'type' => 'text',
			'display_name' => 'User Name:',
			'id' => 'username_s',
			'name' => 'username',
		),
		array(
			'type' => 'text',
			'display_name' => 'Description:',
			'id' => 'description_s',
			'name' => 'description',
		),
		array(
			'type' => 'text',
			'display_name' => 'First Name:',
			'id' => 'firstname_s',
			'name' => 'firstname',
		),
		array(
			'type' => 'text',
			'display_name' => 'Last Name:',
			'id' => 'lastname_s',
			'name' => 'lastname',
		),
		array(
			'type' => 'text',
			'display_name' => 'Email:',
			'id' => 'email_s',
			'name' => 'email',
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
	);
  }

  function get_edit_form_settings() {
	return array(
		array(
			'type' => 'text',
			'display_name' => 'User ID:',
			'name' => 'uid',
			'readonly' => 'readonly',
		),
		array(
			'type' => 'text',
			'display_name' => 'User Name:',
			'name' => 'username',
		),
		array(
			'type' => 'password',
			'display_name' => 'Password:',
			'name' => 'password',
		),
		array(
			'type' => 'text',
			'display_name' => 'First Name:',
			'name' => 'firstname',
		),
		array(
			'type' => 'text',
			'display_name' => 'Last Name:',
			'name' => 'lastname',
		),
		array(
			'type' => 'date',
			'display_name' => 'Created:',
			'name' => 'created',
			'size' => INPUT_SIZE,
			'readonly' => 'readonly',
		),
		array(
			'type' => 'text',
			'display_name' => 'Email:',
			'name' => 'email',
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
			'type' => 'textarea',
			'display_name' => 'description:',
			'name' => 'description',
		),
		array(
			'type' => 'hidden',
			'name' => 'uid',
			'db_type' => 'int',
		)
	);
  }

  function get_add_form_settings() {
	return array(
		array(
		  'type' => 'text',
		  'display_name' => 'User Name:',
		  'id' => 'username',
		  'name' => 'username',
		),
		array(
		  'type' => 'text',
		  'display_name' => 'GWL:',
		  'id' => 'gwl',
		  'name' => 'gwl',
		),
		array(
		  'type' => 'password',
		  'display_name' => 'Password:',
		  'id' => 'password1',
		  'name' => 'password',
		),
		array(
		  'type' => 'password',
		  'display_name' => 'Password Again:',
		  'id' => 'password2',
		  'name' => 'password',
		),
		array(
		  'type' => 'text',
		  'display_name' => 'First Name:',
		  'id' => 'firstname',
		  'name' => 'firstname',
		),
		array(
		  'type' => 'text',
		  'display_name' => 'Last Name:',
		  'id' => 'lastname',
		  'name' => 'lastname',
		),
		array(
			'type' => 'date',
			'display_name' => 'Birth Date:',
			'id' => 'dob',
			'name' => 'dob',
			'size' => INPUT_SIZE,
		),
		array(
			'type' => 'radio',
			'display_name' => 'Gender:',
			'name' => 'sex',
			'lists' => array(
				'M' => 'Male',
				'F' => 'Female',
			),
			'checked' => 'M',
		),
		array(
		  'type' => 'select',
		  'display_name' => 'Division:',
		  'id' => 'division',
		  'name' => 'division',
		),
		array(
		  'type' => 'text',
		  'display_name' => 'Address:',
		  'id' => 'address1',
		  'name' => 'address1',
		),
		array(
		  'type' => 'text',
		  'display_name' => 'Phone:',
		  'id' => 'phone',
		  'name' => 'phone',
		),
		array(
		  'type' => 'text',
		  'display_name' => 'Email:',
		  'id' => 'email',
		  'name' => 'email',
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

try {
	$user = new CommonUsersClass();
} catch (Exception $e) {
	echo $e->getMessage(), "\n";
}

if(! $user->check_access()) {
  $user->set_breakpoint();
  echo "<script>if(window.opener){window.opener.location.href='".LOGIN."';} else{window.parent.location.href='".LOGIN."';}</script>";exit;
}

$user->set_default_config(array('jvalidate' => true,'tabs' => array('1' => '注册用户列表', '2' => '添加用户')));
$user->get_table_info();

// controller to dispatch the HTTP REQUESTS.
if(isset($_GET['js_search_form'])) {
	$ary = $user->get_search_form_settings();

	$user->assign('search_form', $ary);	
	$user->assign('config', $config);
	$user->display($config['templs']['search']); //'search.tpl.html'
}
elseif(isset($_GET['js_edit_form'])) {
	$ary = $user->get_edit_form_settings();

	$record = $user->get_record($_GET['id']);

	if(isset($_GET['tr'])) $form_id = 'ef_'.$_GET['id'].'-'.$_GET['tr'];
	else $form_id = 'ef_'.$_GET['id'];

	$user->assign('form_id', $form_id);	
	$user->assign('record', $record);	
	$user->assign('edit_form', $ary);	
	$user->assign('config', $config);
	$user->display($config['templs']['edit']); //'edit.tpl.html');
}
elseif(isset($_GET['js_add_form'])) {
	$ary = $user->get_add_form_settings();

	$user->assign('add_form', $ary);	
	$user->assign('config', $config);
	$user->display($config['templs']['add']); //'add.tpl.html'
}
elseif(isset($_POST['js_edit_column'])) {
	$ret = $user->update_column(array('updatedby'=>$user->username));
	echo json_encode($ret);
}
elseif(isset($_REQUEST['action'])) {
	switch($_REQUEST['action']) {
		case 'edit':
		  $ary = $user->get_edit_form_settings();
		  echo json_encode($user->edit_table($ary));
			break;
		case 'delete':
			$user->delete($_GET['id']);
			break;
		case 'add':
			$user->create(array('createdby'=>$user->username, 'updatedby'=>$user->username, 'created'=>'NOW()'));
			break;    
		default:
			break;
	}
}
else if( isset($_POST['search']) || (isset($_GET['page']) && isset($_GET['sort'])) || isset($_GET['page']) || isset($_GET['js_reload_list']) ) {
	if(isset($_POST['search'])) $user->parse();

	$data = $user->list_all();
	$data['options'] = array(EDIT, DELETE);

	$header = $user->get_header($user->get_mappings());
	$pagination = $user->draw( $data['current_page'], $data['total_pages'] );
	
	$user->assign('config', $config);
	$user->assign('header', $header);
	$user->assign('data', $data);
	$user->assign("pagination", $pagination);
	$tpl = $user->get_html_template();
	$user->display($tpl); // not use display, should use AJAX.
}
else {
	if (isset($_SESSION[$user->self][$user->session['sql']])) $_SESSION[$user->self][$user->session['sql']] = '';

	$total_rows = $user->get_total_rows($user->get_count_sql());

	$_SESSION[$user->self][$user->session['rows']] = $total_rows < 1 ? 1 : $total_rows;
	
	$data = $user->list_all();
	$data['options'] = array(EDIT, DELETE);

	$header = $user->get_header($user->get_mappings());
	$pagination = $user->draw( $data['current_page'], $data['total_pages'] );

	$user->assign('config', $config);
	$user->assign('header', $header);
	$user->assign('data', $data);
	$user->assign("pagination", $pagination);
	$user->assign("template", $user->get_html_template());
	$user->display($config['templs']['main']);
}

?>
