<?php
session_start();
error_reporting(E_ALL);
define('SITEROOT', './');

ini_set('include_path',ini_get('include_path').PATH_SEPARATOR.SITEROOT.'configs/'.PATH_SEPARATOR.SITEROOT.'include/');
require_once("setting.inc.php");
require_once("config.inc.php");
require_once("ListAdvanced.inc.php");
global $config;

$config['tabs'] = array('1'=>'网页韵律列表', '2'=>'添加网页韵律');

class ThemesClass extends ListAdvanced
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
			'display_name' => 'Path:',
			'id' => 'path_s',
			'name' => 'path',
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
			'display_name' => 'Theme Name:',
			'name' => 'name',
		),
		array(
			'type' => 'text',
			'display_name' => 'Path:',
			'name' => 'path',
		),
		array(
			'type' => 'textarea',
			'display_name' => 'Description:',
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
			'name' => 'tid',
		)
	);
  }
  
  function get_designed_themes() {
	$output = shell_exec("cd ~/public_html/admin/default/front/themes/; ls -l | grep ^d | awk '{ print $9 }'");
	$ns = explode("\n", $output);
	echo "\t<option value=''> --- Select --- </option>\n";
	foreach ($ns as $k=>$v) {
		if (! empty($v)) echo "\t<option value=\"" . $v . "\">$v</option>\n";
	}
  }
  
  function get_theme_by_id($tid) {
  	$query = "SELECT tid, name FROM themes WHERE tid=".$tid;
	$row = $this->mdb2->queryRow($query);
	echo  "\t<option value=\"" . $row[0] . "\">$row[1]</option>\n";
  }
  
  function get_themes() {
	$sql = "SELECT tid, name FROM themes ORDER BY tid";
	$result = $this->mdb2->query($sql);
	echo "\t<option value=''> ------ </option>\n";
	while ($row=$result->fetchRow()) {
		echo "\t<option value=\"" . $row[0] . "\">$row[1]</option>\n";
	}
  }

  function get_add_form_settings() {
	return array(
		array(
		  'type' => 'text',
		  'display_name' => 'Theme name:',
		  'id' => 'theme_name',
		  'name' => 'name',
		),
		array(
		  'type' => 'select',
		  'display_name' => 'Path:',
		  'id' => 'path',
		  'name' => 'path',
		),
		array(
			'type' => 'file',
			'display_name' => 'Upload a Preview Picture:',
			'id' => 'preview',
			'name' => 'preview'
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

  // $theme->create(array('createdby'=>$theme->username, 'updatedby'=>$theme->username, 'created'=>'NOW()'));
  function process_form() 
  {
	if(! isset($_FILES['preview']['size']) || $_FILES['preview']['size']==0 )	return false;

	$name = trim($_POST['name']);
	$path = trim($_POST['path']);
	$description = $_POST['description'];

	$tmpName  = $_FILES['preview']['tmp_name'];	
	$fileName = $_FILES['preview']['name'];

	// copy file to resources/$fileName.
	$out = fopen('./default/front/preview/' . DIRECTORY_SEPARATOR . $fileName, "wb");
	if ($out) {
		$in = fopen($tmpName, "rb");

		if ($in) {
			while ($buff = fread($in, 4096)) fwrite($out, $buff);
		}
		fclose($in);
		fclose($out);
	}
	// again, insert into DataBase.
	$fp      = fopen($tmpName, 'r');
	$content = fread($fp, filesize($tmpName));
	$content = addslashes($content);
	fclose($fp);
	
	$query = "insert into themes(name,path,description,filename,preview,createdby,created) 
		values('".$name . "', '" . $path . "', '".$description . "', '".$fileName."', '".$content."', '".$this->username."', now())";

    $affected = $this->mdb2->exec($query);
    if (PEAR::isError($affected)) {
      die($affected->getMessage().' line '.__FILE__.', '.$query);
    }
	return true;
  }
}

$theme = new ThemesClass();
if(! $theme->check_access()) {
  $theme->set_breakpoint();
  echo "<script>if(window.opener){window.opener.location.href='".LOGIN."';} else{window.parent.location.href='".LOGIN."';}</script>";exit;
}
$theme->get_table_info();
$theme->set_default_config(array('calender'=>true,'jvalidate'=>true));

if(isset($_GET['js_search_form'])) {
	$ary = $theme->get_search_form_settings();
	// $theme->get_search_form($ary);
	$theme->assign('search_form', $ary);	
	$theme->assign('config', $config);
	// $theme->print_array($config);
	$theme->display($config['templs']['search']); //'search.tpl.html'
}
elseif(isset($_GET['js_edit_form'])) {
	$ary = $theme->get_edit_form_settings();
	// $theme->get_edit_form($_GET['id'], $ary);
	$record = $theme->get_record($_GET['id']);

        if(isset($_GET['tr'])) $form_id = 'ef_'.$_GET['id'].'-'.$_GET['tr'];
        else $form_id = 'ef_'.$_GET['id'];

	$theme->assign('form_id', $form_id);	
	$theme->assign('record', $record);	
	$theme->assign('edit_form', $ary);	
	$theme->assign('config', $config);
	$theme->display($config['templs']['edit']); //'edit.tpl.html');
}
elseif(isset($_GET['js_add_form'])) {
	$ary = $theme->get_add_form_settings();
	// $theme->get_add_form($ary);
	$theme->assign('add_form', $ary);	
	$theme->assign('config', $config);
	$theme->display($config['templs']['add']); //'add.tpl.html'
}
elseif(isset($_POST['js_edit_column'])) {
	$ret = $theme->update_column(array('updatedby'=>$theme->username));
	echo json_encode($ret);
}
elseif(isset($_REQUEST['action'])) {
	switch($_REQUEST['action']) {
		case 'edit':
		  $ary = $theme->get_edit_form_settings();
		  echo json_encode($theme->edit_table($ary));
			break;
		case 'delete':
			$theme->delete($_GET['id']);
			break;
		case 'add':
			$theme->create(array('createdby'=>$theme->username, 'updatedby'=>$theme->username, 'created'=>'NOW()'));
			break;    
		default:
			break;
	}
}
else if( isset($_POST['search']) || (isset($_GET['page']) && isset($_GET['sort'])) || isset($_GET['page']) || isset($_GET['js_reload_list']) ) {
	if(isset($_POST['search'])) $theme->parse();

	$data = $theme->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $theme->get_header($theme->get_mappings());

	$pagination = $theme->draw( $data['current_page'], $data['total_pages'] );
	
	$theme->assign('config', $config);

	$theme->assign('header', $header);
	$theme->assign('data', $data);
	$theme->assign("pagination", $pagination);
	$tpl = $theme->get_html_template();
	$theme->display($tpl); // not use display, should use AJAX.
}
else if(isset($_POST['add_submit'])) {
	// $theme->create(array('createdby'=>$theme->username, 'updatedby'=>$theme->username, 'created'=>'NOW()'));
	$theme->process_form();
}
else {
	if (isset($_SESSION[$theme->self][$theme->session['sql']])) $_SESSION[$theme->self][$theme->session['sql']] = '';

	$total_rows = $theme->get_total_rows($theme->get_count_sql());

	$_SESSION[$theme->self][$theme->session['rows']] = $total_rows < 1 ? 1 : $total_rows;
	
	$data = $theme->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $theme->get_header($theme->get_mappings());

	$pagination = $theme->draw( $data['current_page'], $data['total_pages'] );
	
	$tpl = $theme->get_html_template();

	$theme->assign('config', $config);
	$theme->assign('header', $header);
	$theme->assign('data', $data);
	$theme->assign("pagination", $pagination);

	$theme->assign("template", $tpl);
	$theme->display($config['templs']['main']);
}
?>
