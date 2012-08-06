<?php
session_start();
error_reporting(E_ALL);
define('SITEROOT', './');

ini_set('include_path',ini_get('include_path').PATH_SEPARATOR.SITEROOT.'configs/'.PATH_SEPARATOR.SITEROOT.'include/');
require_once("setting.inc.php");
require_once("config.inc.php");
require_once("ListAdvanced.inc.php");
global $config;

class UsersClass extends ListAdvanced
{
  var $url, $self, $session;
  public function __construct() {
    parent::__construct();
  }

  function get_search_form_settings() {
  return array(
    /*array( 'type' => 'text', 'display_name' => 'User ID:', 'id' => 'uid_s', 'name' => 'uid', 'db_type' => 'int',),
    array( 'type' => 'text', 'display_name' => 'Password:', 'id' => 'password_s', 'name' => 'password',), */
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
    array(
      'type' => 'select',
      'display_name' => 'User Group:',
      'id' => 'level_s',
      'name' => 'level',
      'db_type' => 'int',
      'call_func' => 'get_level',
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
      'type' => 'text',
      'display_name' => 'Email:',
      'name' => 'email',
    ),
    array(
      'type' => 'text',
      'display_name' => 'User Group:',
      'name' => 'level',
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
      'type' => 'textarea',
      'display_name' => 'Description:',
      'id' => 'description',
      'name' => 'description',
      'size' => INPUT_SIZE+10,
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
      'type' => 'text',
      'display_name' => 'Email:',
      'id' => 'email',
      'name' => 'email',
    ),
    array(
      'type' => 'select',
      'display_name' => 'User Group:',
      'id' => 'level',
      'name' => 'level',
      'call_func' => 'get_level',
    ),      
    );
  }    

  function get_level_by_id($level) {
    $query = "SELECT level, name FROM levels WHERE level=".$level;
  $row = $this->mdb2->queryRow($query);
  echo  "\t<option value=\"" . $row[0] . "\">$row[1]</option>\n";
  }
  
  function get_level_name($query) {
  if(isset($query) && $query) {
    $sql = $query;
    if (preg_match("/,\s*level,/", $sql)) {
      $sql = $this->replace_str_once_new("level", '(select name from levels where levels.level=admin_users.level) as level', $sql);
    }
    return $sql;
  }
  return $query;
  }
}

try {
  $user = new UsersClass();
} catch (Exception $e) {
  echo $e->getMessage(), "\n";
}

if(! $user->check_access()) {
  $user->set_breakpoint();
  echo "<script>if(window.opener){window.opener.location.href='".LOGIN."';} else{window.parent.location.href='".LOGIN."';}</script>";exit;
}
// ListBase.php: when construct, 'path','self','title' already set up.
$user->set_default_config(array('jvalidate' => true,'tabs' => array('1' => '管理用户列表', '2' => '添加管理用户')));
$user->get_table_info();

if(isset($_GET['js_search_form'])) {
  $ary = $user->get_search_form_settings();
  $user->assign('search_form', $ary);  
  $user->assign('config', $config);
  $user->display($config['templs']['search']);
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
      $last_uid = $user->create(array('createdby'=>$user->username, 'updatedby'=>$user->username, 'created'=>'NOW()'));
      $query = "UPDATE admin_users AS au, (SELECT name FROM levels WHERE level=".$_POST['level']." ) AS l SET au.lname = l.name WHERE uid=".$last_uid;
      $affected = $user->mdb2->exec($query);
      if (PEAR::isError($affected)) {
        die($affected->getMessage().': ' . $query . ". line: " . __LINE__);
      }
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
  $user->display($tpl);
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
