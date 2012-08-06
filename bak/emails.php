<?php
session_start();
error_reporting(E_ALL);
define('SITEROOT', './');

ini_set('include_path',ini_get('include_path').PATH_SEPARATOR.SITEROOT.'configs/'.PATH_SEPARATOR.SITEROOT.'include/');
require_once("setting.inc.php");
require_once("config.inc.php");
require_once("ListAdvanced.inc.php");
global $config;

class EmailClass extends ListAdvanced
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
      'display_name' => 'English Name:',
      'id' => 'englishname_s',
      'name' => 'englishname',
    ),
    array(
      'type' => 'text',
      'display_name' => 'Last Name:',
      'id' => 'chinesename_s',
      'name' => 'chinesename',
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
      'name' => 'englishname',
    ),
    array(
      'type' => 'text',
      'display_name' => 'Last Name:',
      'name' => 'chinesename',
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
      'id' => 'englishname',
      'name' => 'englishname',
    ),
    array(
      'type' => 'text',
      'display_name' => 'Last Name:',
      'id' => 'chinesename',
      'name' => 'chinesename',
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
        $sql = $this->replace_str_once_new("level", '(select name from levels where levels.level=onefamily.level) as level', $sql);
      }
      return $sql;
    }
    return $query;
  }

  function get_emails_asc()
  {
    $emails = "";
    $query = "SELECT email FROM onefamily ORDER BY email";
	$res = $this->mdb2->query($query);
    while ($row = $res->fetchRow()) {
      $emails .= $row[0] . ",<br>";
    }
	return preg_replace("/,$/", '', $emails);
  }
  
  function get_emails_desc()
  {
    $emails = "";
    $query = "SELECT email FROM onefamily ORDER BY email DESC";
	$res = $this->mdb2->query($query);
    while ($row = $res->fetchRow()) {
      $emails .= $row[0] . ",<br>";
    }
	return preg_replace("/,$/", '', $emails);
  }
}

try {
  $email = new EmailClass();
} catch (Exception $e) {
  echo $e->getMessage(), "\n";
}

if(! $email->check_access()) {
  $email->set_breakpoint();
  echo "<script>if(window.opener){window.opener.location.href='".LOGIN."';} else{window.parent.location.href='".LOGIN."';}</script>";exit;
}

$email->set_default_config(array('jvalidate' => true,'tabs' => array('1' => 'List Emails', '2' => 'Add Email', '3' => 'Email Groups')));
$email->get_table_info();

if(isset($_GET['js_search_form'])) {
  $ary = $email->get_search_form_settings();
  $email->assign('search_form', $ary);  
  $email->assign('config', $config);
  $email->display($config['templs']['search']);
}
elseif(isset($_GET['js_get_emails'])) {
  $emails = array();
  $emails['asc'] = $email->get_emails_asc();
  $emails['desc'] = $email->get_emails_desc();
  $email->assign('emails', $emails);  
  $email->display('emails.tpl.html');
}
elseif(isset($_GET['js_edit_form'])) {
  $ary = $email->get_edit_form_settings();
  $record = $email->get_record($_GET['id']);

  if(isset($_GET['tr'])) $form_id = 'ef_'.$_GET['id'].'-'.$_GET['tr'];
  else $form_id = 'ef_'.$_GET['id'];

  $email->assign('form_id', $form_id);  
  $email->assign('record', $record);  
  $email->assign('edit_form', $ary);  
  $email->assign('config', $config);
  $email->display($config['templs']['edit']); //'edit.tpl.html');
}
elseif(isset($_GET['js_add_form'])) {
  $ary = $email->get_add_form_settings();
  $email->assign('add_form', $ary);  
  $email->assign('config', $config);
  $email->display($config['templs']['add']); //'add.tpl.html'
}
elseif(isset($_POST['js_edit_column'])) {
  $ret = $email->update_column(array('updatedby'=>$email->username));
  echo json_encode($ret);
}
elseif(isset($_REQUEST['action'])) {
  switch($_REQUEST['action']) {
    case 'edit':
      $ary = $email->get_edit_form_settings();
      echo json_encode($email->edit_table($ary));
      break;
    case 'delete':
      $email->delete($_GET['id']);
      break;
    case 'add':
      $last_uid = $email->create(array('createdby'=>$email->username, 'updatedby'=>$email->username, 'created'=>'NOW()'));
      $query = "UPDATE onefamily AS au, (SELECT name FROM levels WHERE level=".$_POST['level']." ) AS l SET au.lname = l.name WHERE uid=".$last_uid;
      $affected = $email->mdb2->exec($query);
      if (PEAR::isError($affected)) {
        die($affected->getMessage().': ' . $query . ". line: " . __LINE__);
      }
      break;    
    default:
      break;
  }
}
else if( isset($_POST['search']) || (isset($_GET['page']) && isset($_GET['sort'])) || isset($_GET['page']) || isset($_GET['js_reload_list']) ) {
  if(isset($_POST['search'])) $email->parse();

  $data = $email->list_all();
  $data['options'] = array(EDIT);

  $header = $email->get_header($email->get_mappings());
  $pagination = $email->draw( $data['current_page'], $data['total_pages'] );
  
  $email->assign('config', $config);
  $email->assign('header', $header);
  $email->assign('data', $data);
  $email->assign("pagination", $pagination);
  $tpl = $email->get_html_template();
  $email->display($tpl);
}
else {
  if (isset($_SESSION[$email->self][$email->session['sql']])) $_SESSION[$email->self][$email->session['sql']] = '';

  $total_rows = $email->get_total_rows($email->get_count_sql());

  $_SESSION[$email->self][$email->session['rows']] = $total_rows < 1 ? 1 : $total_rows;
  
  $data = $email->list_all();
  $data['options'] = array(EDIT);

  $header = $email->get_header($email->get_mappings());
  $pagination = $email->draw( $data['current_page'], $data['total_pages'] );

  $email->assign('config', $config);
  $email->assign('header', $header);
  $email->assign('data', $data);
  $email->assign("pagination", $pagination);
  $email->assign("template", $email->get_html_template());
  $email->display($config['templs']['main']);
}
?>
