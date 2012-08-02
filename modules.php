<?php
session_start();
error_reporting(E_ALL);
define('SITEROOT', './');

ini_set('include_path',ini_get('include_path').PATH_SEPARATOR.SITEROOT.'configs/'.PATH_SEPARATOR.SITEROOT.'include/');
require_once("setting.inc.php");
require_once("config.inc.php");
require_once("ListAdvanced.inc.php");
global $config;


$config['tabs'] = array('1'=>'List Modules', '2'=>'Add Module', '3'=>'Pages - Modules');


class ModulesClass extends ListAdvanced
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
			'type' => 'text',
			'display_name' => 'URL:',
			'id' => 'url_s',
			'name' => 'url',
		), /*
		array(
		  'type' => 'select',
		  'display_name' => 'Division:',
		  'id' => 'division_s',
		  'name' => 'division',
		  'call_func' => 'get_divisions_options',
		),
		array(
			'type' => 'text',
			'display_name' => 'Description:',
			'id' => 'description_s',
			'name' => 'description',
		),
		*/
		array(
			'type' => 'text',
			'display_name' => 'Name:',
			'id' => 'name_s',
			'name' => 'name',
		),
		array(
			'type' => 'radio',
			'display_name' => 'Active:',
			'id' => 'active_s',
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
		array(
			'type' => 'date',
			'display_name' => 'Updated Date:',
			'id' => 'updated_s',
			'name' => 'updated',
			'size' => INPUT_SIZE,
		),
		array(
			'type' => 'text',
			'display_name' => 'Updated By:',
			'id' => 'updatedby_s',
			'name' => 'updatedby',
		),
	);
  }

  function get_edit_form_settings() {
	return array(
		array(
		  'type' => 'text',
		  'display_name' => 'Site:',
		  'name_value' => 'site_id',
		  'name' => 'site_id',
		),
		/*
		array(
		  'type' => 'select',
		  'display_name' => 'Division:',
		  'name' => 'division',
		  'call_func' => 'get_divisions_options',
		), */
		array(
			'type' => 'text',
			'display_name' => 'ID:',
			'name' => 'mid',
			'readonly' => 'readonly',
		),
		array(
			'type' => 'text',
			'display_name' => 'Name:',
			'name' => 'name',
		),
		array(
			'type' => 'text',
			'display_name' => 'URL:',
			'name' => 'url',
		),
		array(
			'type' => 'radio',
			'display_name' => 'Use URL:',
			'name' => 'url_flag',
			'lists' => array(
				'N' => 'No',
				'Y' => 'Yes',
			),
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
			'type' => 'radio',
			'display_name' => 'Display Left Column:',
			'name' => 'left1',
			'lists' => array(
				'N' => 'No',
				'Y' => 'Yes',
			),
		),
		array(
			'type' => 'radio',
			'display_name' => 'Display Right Column:',
			'name' => 'right3',
			'lists' => array(
				'N' => 'No',
				'Y' => 'Yes',
			),
		),
    array(
      'type' => 'radio',
      'display_name' => 'Display Submenu:',
      'name' => 'submenu',
      'lists' => array(
        'Y' => 'Yes',
        'N' => 'No',
      ),
    ),
		array(
			'type' => 'textarea',
			'display_name' => 'Description:',
			'name' => 'description',
		),
		/*
		array(
		  'type' => 'date',
		  'display_name' => 'Created Date:',
		  'name' => 'created',
		  'readonly' => 'readonly',
		  'size' => INPUT_SIZE,
		),
		array(
			'type' => 'date',
			'display_name' => 'Updated Date:',
			'name' => 'updated',
			'size' => INPUT_SIZE,
		), */
		array(
			'type' => 'text',
			'display_name' => 'Created By:',
			'name' => 'createdby',
			'readonly' => 'readonly',
		),
		array(
			'type' => 'text',
			'display_name' => 'Updated By:',
			'name' => 'updatedby',
		),
		array( 'type' => 'hidden', 'name' => 'mid', 'db_type' => 'int',)
	);
  }

  function get_add_form_settings() {
	return array(
		array(
		  'type' => 'text',
		  'display_name' => 'Module Name:',
		  'id' => 'module_name',
		  'name' => 'name',
		),
		array(
		  'type' => 'select',
		  'display_name' => 'To Which Site:',
		  'id' => 'site_id',
		  'name' => 'site_id',
		  'call_func' => 'get_sites_options',
		),      
		array(
		  'type' => 'text',
		  'display_name' => 'Module URL:',
		  'id' => 'url',
		  'name' => 'url',
		),
		array(
			'type' => 'radio',
			'display_name' => 'Use this URL:',
			'id' => 'url_flag',
			'name' => 'url_flag',
			'lists' => array(
				'N' => 'No',
				'Y' => 'Yes',
			),
			'checked' => 'N',
		),
		array(
		  'type' => 'textarea',
		  'display_name' => 'Description:',
		  'id' => 'description',
		  'name' => 'description',
		),
	  );
  }

	function select_users_modules($uid, $cid=0) {
		$sql = "SELECT um.mid, m.name FROM users_modules um inner join modules m on um.mid=m.mid and uid=".$uid;
		$str = '';
		$res = $this->mdb2->query($sql);
		while($row = $res->fetchRow()) {
			$str .= '<option value="'.$row[0].'">'.$row[1]."</option>\n";			
		}
		echo $str;
	}
	function select_modules($cid) {
		$sql = "SELECT mid, name FROM modules where active='Y' AND mid not in (SELECT mid FROM users_modules WHERE uid=".$this->userid.") ORDER BY name";
		$str = '';
		$res = $this->mdb2->query($sql);
		while ($row=$res->fetchRow()) {
			$str .= '<option value="'.$row[0].'">'.$row[1]."</option>\n";
		}
		echo $str;
	}
	function update_pages_modules() {
		$pid = $_POST['pages'];
		$mids = explode(",", $_POST['mids']);

		$sql = "DELETE FROM pages_modules WHERE pid=". $pid;
		$affected = $this->mdb2->exec($sql);
		if (PEAR::isError($affected)) {
			die($affected->getMessage());
		}
		foreach($mids as $mid) {
			$sql = "INSERT INTO pages_modules(pid, mid, createdby) VALUES(". $pid. ", ".$mid.", '".$this->username."')";
			echo $sql."\n";
			$affected = $this->mdb2->exec($sql);
			if (PEAR::isError($affected)) {
				continue;
			}
		}
		return true;
	}
	function update_users_modules() {
		$uid = $_POST['uid']?$_POST['uid']:$this->userid;
		$mids = explode(",", $_POST['mids']);

		$sql = "DELETE FROM users_modules WHERE uid=". $uid;
		$affected = $this->mdb2->exec($sql);
		if (PEAR::isError($affected)) {
			die($affected->getMessage());
		}
		foreach($mids as $mid) {
			$sql = "INSERT INTO users_modules(uid, mid) VALUES(". $uid. ", ". $mid . ")";
			echo $sql."\n";
			$affected = $this->mdb2->exec($sql);
			if (PEAR::isError($affected)) {
				continue;
			}
		}
		return true;
	}

  function get_site_name($query) {
	if(isset($query) && $query) {
		if (preg_match("/,\s*site_id,/", $query))
			$query = preg_replace("/site_id/", '(select name from sites where sites.site_id=modules.site_id) as site_id', $query);
		return $query;
	}
	return;
  }

}

$module = new ModulesClass();
if(! $module->check_access()) {
  $module->set_breakpoint();
  echo "<script>if(window.opener){window.opener.location.href='".LOGIN."';} else{window.parent.location.href='".LOGIN."';}</script>";exit;
}
$module->get_table_info();
$module->set_default_config(array('calender'=>true,'jvalidate'=>true));

if (isset($_GET['cid']) && isset($_GET['step'])) {
	switch($_GET['step']) {
	case 1:
		$module->select_available_modules($_GET['cid'], $_GET['site_id']);
		break;
	case 2:
		$uid = isset($_GET['uid']) ? $_GET['uid'] : $module->userid;
		$module->select_assigned_modules($_GET['cid'], $_GET['site_id']);
		break;
	default:
		break;
	}
}
// After click the submit.
elseif(isset($_POST['js_update'])) {
	$module->update_pages_modules();
}
// controller to dispatch the HTTP REQUESTS.
elseif(isset($_GET['js_search_form'])) {
	$ary = $module->get_search_form_settings();
	$module->assign('search_form', $ary);	
	$module->assign('config', $config);
	$module->display($config['templs']['search']);
}
elseif(isset($_GET['js_edit_form'])) {
	$ary = $module->get_edit_form_settings();
	$record = $module->get_record($_GET['id']);
	$module->assign('record', $record);	


        if(isset($_GET['tr'])) $module->assign('form_id', 'ef_'.$_GET['id'].'-'.$_GET['tr']);
        else $module->assign('form_id', 'ef_'.$_GET['id']);

	$module->assign('edit_form', $ary);	
	$module->assign('config', $config);
	$module->display($config['templs']['edit']);
}
elseif(isset($_GET['js_add_form'])) {
	$ary = $module->get_add_form_settings();
	$module->assign('add_form', $ary);	
	$module->assign('config', $config);
	$module->display($config['templs']['add']);
}
elseif(isset($_GET['js_assign_form'])) {
	$data = array();
	$data['userid'] = $module->userid;
	$data['username'] = $module->username;
	$data['sites'] = $module->get_sites_array();

	$module->assign('config', $config);
	$module->assign('data', $data);
	$module->display($config['templs']['assign_cm']); //'assign_pages-modules.tpl.html'
}
// jQuery.fn.load() uses $_POST.
elseif(isset($_POST['js_get_pages'])) {
	$module->get_pages_options($_POST['site_id']);
}
elseif(isset($_REQUEST['js_get_modules'])) {
	$module->get_modules_options($_REQUEST['site_id']);
}

elseif(isset($_POST['js_edit_column'])) {
	$ret = $module->update_column(array('updatedby'=>$module->username));
	echo json_encode($ret);
}
elseif(isset($_REQUEST['action'])) {
	switch($_REQUEST['action']) {
		case 'edit':
		  $ary = $module->get_edit_form_settings();
		  echo json_encode($module->edit_table($ary));
			break;
		case 'delete':
			$module->delete($_GET['id']);
			break;
		case 'add':
			// patch: from ListAdvanced.inc.php: line 259.
			$last_mid = $module->create(array('createdby'=>$module->username,'updatedby'=>$module->username,'created'=>'NOW()'));
			$query = "UPDATE modules AS m, (SELECT name FROM sites WHERE site_id=".$_POST['site_id']." ) AS s SET m.sname = s.name WHERE mid=".$last_mid; // $module->mdb2->lastInsertID();
echo "<br>\n"; echo $query; echo "<br>\n";
			$affected = $module->mdb2->exec($query);
			if (PEAR::isError($affected)) {
				die($affected->getMessage().': ' . $query . ". line: " . __LINE__);
			}
			break;    
		default:
			break;
	}
}
else if( isset($_POST['search']) || (isset($_GET['page']) && isset($_GET['sort'])) || isset($_GET['page']) || isset($_GET['js_reload_list']) ) {
	if(isset($_POST['search'])) $module->parse();

	$data = $module->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $module->get_header($module->get_mappings());

	$pagination = $module->draw( $data['current_page'], $data['total_pages'] );
	
	$module->assign('config', $config);
	$module->assign('header', $header);
	$module->assign('data', $data);
	$module->assign("pagination", $pagination);
	$tpl = $module->get_html_template();
	$module->display($tpl); // not use display, should use AJAX.
}
// default: list data.
else {
	if (isset($_SESSION[$module->self][$module->session['sql']])) $_SESSION[$module->self][$module->session['sql']] = '';

	$total_rows = $module->get_total_rows($module->get_count_sql());

	$_SESSION[$module->self][$module->session['rows']] = $total_rows < 1 ? 1 : $total_rows;
	
	$data = $module->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $module->get_header($module->get_mappings());

	$pagination = $module->draw( $data['current_page'], $data['total_pages'] );
	
	$tpl = $module->get_html_template();

	$module->assign('config', $config);
	$module->assign('header', $header);
	$module->assign('data', $data);
	$module->assign("pagination", $pagination);

	$module->assign("template", $tpl);
	$module->display($config['templs']['main']);
}
?>
