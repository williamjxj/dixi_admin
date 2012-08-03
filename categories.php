<?php
// 乱码问题
session_start();
define('SITEROOT', './');
error_reporting(E_ALL);
echo "<pre>"; print_r($_SESSION); echo "</pre>";
//echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

//ini_set('include_path',ini_get('include_path').PATH_SEPARATOR.SITEROOT.'configs/'.PATH_SEPARATOR.SITEROOT.'include/');
require_once(SITEROOT.'/configs/setting.inc.php');
require_once(SITEROOT.'/configs/config.inc.php');
require_once(SITEROOT.'/configs/ListAdvanced.inc.php');

class CategoryClass extends ListAdvanced
{
  var $url, $self, $session;

  public function __construct() {
    parent::__construct();
	$this->table = 'categories';
  }

  function get_search_form_settings() {
	return array(
		array(
		  'type' => 'select',
		  'display_name' => '类别名称:',
		  'id' => 'cid_s',
		  'name' => 'cid',
		  'call_func' => 'get_category_options',
		  'db_type' => 'int',
		),
		array(
		  'type' => 'select',
		  'display_name' => '频率:',
		  'id' => 'frequency_s',
		  'name' => 'frequency',
		  'call_func' => 'get_frequency_options',
		  'db_type' => 'int',
		),
		array(
		  'type' => 'select',
		  'display_name' => '标签:',
		  'id' => 'tag_s',
		  'name' => 'tag',
		  'call_func' => 'get_tag_options',
		),
		array(
			'type' => 'text',
			'display_name' => '链接地址:',
			'id' => 'url_s',
			'name' => 'url',
		),
		array(
			'type' => 'date',
			'display_name' => '创建日期:',
			'id' => 'created_s',
			'name' => 'created',
			'size' => INPUT_SIZE,
		),
		array(
			'type' => 'text',
			'display_name' => '创建者:',
			'id' => 'createdby_s',
			'name' => 'createdby',
		),			
		array(
			'type' => 'radio',
			'display_name' => '状态:',
			'name' => 'active',
			'lists' => array(
				'N' => '禁止',
				'Y' => '活动',
				'A' => '所有',
			),
			'checked' => 'A',
			'ignore' => 'A',
		),
	);
  }

  function get_edit_form_settings() {
	return array(
		array(
			'type' => 'text',
			'display_name' => '类别名称:',
			'name' => 'name',
		),
		array(
			'type' => 'text',
			'display_name' => '链接地址:',
			'id' => 'url_s',
			'name' => 'url',
		),
		array(
			'type' => 'textarea',
			'display_name' => '描述:',
			'name' => 'description',
		),
		array(
			'type' => 'radio',
			'display_name' => '状态:',
			'name' => 'active',
			'lists' => array(
				'N' => '禁止',
				'Y' => '活动',
			),
		),
		array(
			'type' => 'hidden',
			'name' => 'cid',
			'db_type' => 'int',
		)
	);
  }

  function get_add_form_settings() {
	return array(
		array(
		  'type' => 'text',
		  'display_name' => '类别名称:',
		  'id' => 'name',
		  'name' => 'name',
		),
		array(
		  'type' => 'textarea',
		  'display_name' => '描述:',
		  'id' => 'description',
		  'name' => 'description',
		  'size' => INPUT_SIZE+10,
		),
		array(
		  'type' => 'text',
		  'display_name' => '链接地址:',
		  'id' => 'url',
		  'name' => 'url',
		),
	);
  }

  function get_category_options() {
	$sql = "SELECT cid, name, description FROM " . $this->table . " where active='Y' ORDER BY name";
	return 	$this->get_select_options($sql);
  }
  function get_frequency_options() {
	$sql = "SELECT distinct frequency FROM " . $this->table . " ORDER BY frequency";
	$res = $this->mdb2->query($sql);
	echo "\t<option value=''> --- 请选择 --- </option>\n";
	while ($row=$res->fetchRow()) {
		echo "\t".'<option value="'.$row[0].'" title="'.$row[0].'">'.htmlspecialchars($row[0])."</option>\n";
	}
  }
  function get_tag_options() {
	$sql = "SELECT distinct tag FROM " . $this->table . " ORDER BY tag";
	$res = $this->mdb2->query($sql);
	echo "\t<option value=''> --- 请选择 --- </option>\n";
	while ($row=$res->fetchRow()) {
		echo "\t".'<option value="'.$row[0].'" title="'.$row[0].'">'.htmlspecialchars($row[0])."</option>\n";
	}
  }
  
  // parse_ini 不支持多国字体。
  function get_header() {
  	return array(
		'CID' => 'cid',
		'名称' => 'name',
		'链接地址' => 'curl',
		'频率' => 'frequency',
		'标签' => 'tag',
		'状态' => 'active',
		'描述' => 'description',
		'创建' => 'created,createdby',
		'更新' => 'updated,updatedby',
	);
  }  
}

//////////////////////////////////////////

global $config;
$config['tabs'] = array('1'=>'类别列表', '2'=>'添加类别');

//$t = parse_ini_string(file_get_contents(SITEROOT."configs/register_list.ini"), true);
//$t = parse_ini_file(SITEROOT."configs/register_list.ini", true);

$cate = new CategoryClass();

if(! $cate->check_access()) {
  $cate->set_breakpoint();
  echo "<script>if(window.opener){window.opener.location.href='".LOGIN."';} else{window.parent.location.href='".LOGIN."';}</script>";
  exit;
}

$cate->get_table_info();
$cate->set_default_config(array('calender'=>true,'jvalidate'=>true));

if (isset($_GET['cid'])) {
	switch($_GET['step']) {
	case 1:
		$cate->select_pages($_GET['cid']);
		break;
	case 2:
		$uid = isset($_GET['uid']) ? $_GET['uid'] : $this->userid;
		$cate->select_users_pages($uid);
		break;
	default:
		break;
	}
}
elseif(isset($_POST['update'])) {
	$ac->update_pages();
}
elseif(isset($_GET['js_search_form'])) {
	$ary = $cate->get_search_form_settings();
	$cate->assign('search_form', $ary);	
	$cate->assign('config', $config);
	$cate->display($config['templs']['search']);
}
elseif(isset($_GET['js_edit_form'])) {
	$ary = $cate->get_edit_form_settings();
	$record = $cate->get_record($_GET['id']);
	$cate->assign('record', $record);	
	if(isset($_GET['tr'])) $cate->assign('form_id', 'ef_'.$_GET['id'].'-'.$_GET['tr']);
	else $cate->assign('form_id', 'ef_'.$_GET['id']);
	$cate->assign('edit_form', $ary);	
	$cate->assign('config', $config);
	$cate->display($config['templs']['edit']);
}
elseif(isset($_GET['js_add_form'])) {
	$ary = $cate->get_add_form_settings();
	$cate->assign('add_form', $ary);	
	$cate->assign('config', $config);
	$cate->display($config['templs']['add']);
}
elseif(isset($_POST['js_edit_column'])) {
	$ret = $cate->update_column(array('updatedby'=>$cate->username));
	echo json_encode($ret);
}
elseif(isset($_REQUEST['action'])) {
	switch($_REQUEST['action']) {
		case 'edit':
		  $ary = $cate->get_edit_form_settings();
		  echo json_encode($cate->edit_table($ary));
			break;
		case 'delete':
			$cate->delete($_GET['id']);
			break;
		case 'add':
			$last_cid = $cate->create(array('createdby'=>$cate->username, 'updatedby'=>$cate->username, 'created'=>'NOW()'));
                        $query = "UPDATE category AS p, (SELECT name FROM categories WHERE cid=".$_POST['cid']." ) AS s SET p.sname = s.name WHERE cid=".$last_cid;
                        $affected = $cate->mdb2->exec($query);
                        if (PEAR::isError($affected)) {
                                die($affected->getMessage().': ' . $query . ". line: " . __LINE__);
                        }
			break;    
		default:
			break;
	}
}
else if( isset($_POST['search']) || (isset($_GET['page']) && isset($_GET['sort'])) || isset($_GET['page']) || isset($_GET['js_reload_list']) ) {
	if(isset($_POST['search'])) $cate->parse();

	$data = $cate->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $cate->get_header(); //$cate->get_mappings();

	$pagination = $cate->draw( $data['current_page'], $data['total_pages'] );
	
	$cate->assign('config', $config);
	$cate->assign('header', $header);
	$cate->assign('data', $data);
	$cate->assign("pagination", $pagination);
	$tpl = $cate->get_html_template();
	$cate->display($tpl); // not use display, should use AJAX.
}
// default: list data.
else {
	if (isset($_SESSION[$cate->self][$cate->session['sql']])) $_SESSION[$cate->self][$cate->session['sql']] = '';

	$total_rows = $cate->get_total_rows($cate->get_count_sql());

	$_SESSION[$cate->self][$cate->session['rows']] = $total_rows < 1 ? 1 : $total_rows;
	
	$data = $cate->list_all();
	$data['options'] = array(EDIT, DELETE);
	
	$header = $cate->get_header(); //$cate->get_mappings();

	$pagination = $cate->draw( $data['current_page'], $data['total_pages'] );
	
	$tpl = $cate->get_html_template();

	$cate->assign('config', $config);
	$cate->assign('header', $header);
	$cate->assign('data', $data);
	$cate->assign("pagination", $pagination);

	$cate->assign("template", $tpl);
	$cate->display($config['templs']['main']);
}
?>
