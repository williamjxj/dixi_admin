<?php
session_start();
error_reporting(E_ALL);
define('SITEROOT', './');

ini_set('include_path',ini_get('include_path').PATH_SEPARATOR.SITEROOT.'configs/'.PATH_SEPARATOR.SITEROOT.'include/');
require_once("setting.inc.php");
require_once("config.inc.php");
require_once("ListAdvanced.inc.php");
global $config;

$config['tabs'] = array('1'=>'List', '2'=>'Add', '3'=> 'Update', '4'=>'Module - Contents');
$config['WYSIWYG'] = true;

class ContentsClass extends ListAdvanced
{
  var $url, $self, $session, $allowedTags;
  public function __construct() {
    parent::__construct();
    //$this->allowedTags = '<a><p><strong><em><u><h1><h2><h3><h4><h5><h6><img><li><ol><ul><span><div><br><ins><del><table><tr><td><tbody><thead><tfoot><b>';  
	$this->allowedTags = ALLOWTAGS;
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
		  'type' => 'select',
		  'display_name' => 'Module:',
		  'id' => 'mid_s',
		  'name' => 'mid',
		  'call_func' => 'get_modules_options',
		  'db_type' => 'int',
		),
		array(
		  'type' => 'select',
		  'display_name' => 'Division:',
		  'id' => 'division_s',
		  'name' => 'division',
		  'call_func' => 'get_divisions_options',
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
			'type' => 'text',
			'display_name' => 'Linked Name:',
			'id' => 'linknamet_s',
			'name' => 'linkname',
		),
		array(
			'type' => 'text',
			'display_name' => 'Author:',
			'id' => 'author_s',
			'name' => 'author',
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
		array(
			'type' => 'text',
			'display_name' => 'Created By:',
			'id' => 'createdby_s',
			'name' => 'createdby',
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
		  'name' => 'sname',
		),
		array(
		  'type' => 'text',
		  'display_name' => 'Module:',
		  'name_value' => 'mid',
		  'name' => 'mname',
		),
		array(
		  'type' => 'select',
		  'display_name' => 'Division:',
          'name_value' => 'division',
		  'name' => 'division',
		  'call_func' => 'get_divisions_options',
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
			'type' => 'text',
			'display_name' => 'Linked Name:',
			'name' => 'linkname',
		),
		array(
			'type' => 'text',
			'display_name' => 'Author:',
			'name' => 'author',
		),
		array(
			'type' => 'textarea',
			'display_name' => 'Notes:',
			'name' => 'notes',
		),/*
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
		),
		array(
			'type' => 'text',
			'display_name' => 'Updated By:',
			'name' => 'updatedby',
		),
		array(
			'type' => 'hidden',
			'name' => 'cid',
			'db_type' => 'int',
		)
	);
  }

  function get_contents_options_by_sid($site_id){
	$sql = "SELECT cid, linkname, author, (SELECT name FROM modules m WHERE m.mid=c.mid) AS mname, division FROM contents c WHERE site_id=" . $site_id . " ORDER BY linkname";
	return 	$this->get_select_options($sql);
  }
  function get_contents_options_by_mid($module_id){
	$sql = "SELECT cid, linkname, author, (SELECT name FROM modules m WHERE m.mid=c.mid) AS mname, division FROM contents c WHERE mid=" . $module_id . " ORDER BY linkname";
	return 	$this->get_select_options($sql);
  }  
  function get_contents_options_by_division($module_id, $division){
  	if($division) {
		$sql = "SELECT cid, concat(linkname,'[',mid,']','[',division,']') as linkname, author, (SELECT name FROM modules m WHERE m.mid=c.mid) AS mname, division FROM contents c WHERE mid=" . $module_id . " AND division='".$division."' ORDER BY linkname";
		return 	$this->get_select_options($sql);
	}
	else
		return $this->get_contents_options_by_mid($module_id);
  }  

  function update_modules_contents()
  {
	$mid = intval($_POST['modules']);
	$cids = explode(",", $_POST['mids']);

	$sql = "DELETE FROM contents WHERE mid=". $mid." AND weight<250";
	$affected = $this->mdb2->exec($sql);
	if (PEAR::isError($affected)) {
		echo $affected->getMessage().' line: ' . __LINE__; echo "[".$sql."]\n";
	}
	foreach($cids as $cid) {
		$sql = "INSERT INTO contents(
			linkname,author,notes,content,site_id,mid,division,weight,active,createdby,created) 		
		SELECT
			linkname,author,notes,content,site_id,".$mid.",division,weight,'Y','".$this->username."',NOW()
		FROM contents
		WHERE cid = ".$cid;
		echo $sql."\n";
		$affected = $this->mdb2->exec($sql);
		if (PEAR::isError($affected)) {
			continue;
		}
	}
	return true;
  }

  function update_contents_resources()
  {
	$cid = $_POST['cid'];
	$mids = explode(",", $_POST['mids']);

	$sql = "DELETE FROM contents_resources WHERE cid=". $cid;
	$affected = $this->mdb2->exec($sql);
	if (PEAR::isError($affected)) {
		die($affected->getMessage());
	}
	foreach($mids as $rid) {
		$sql = "INSERT INTO contents_resources(cid, rid, createdby) VALUES(". $cid. ", ".$rid.", '".$this->username."')";
		echo $sql."\n";
		$affected = $this->mdb2->exec($sql);
		if (PEAR::isError($affected)) {
			continue;
		}
	}
	return true;
  }

	/**
	$content = $_POST['content'];
	$content = $this->mdb2->escape($content);
	$site_id = $_POST['sname']; $mid = $_POST['mname'];
	site_id  = " . $site_id . ", " .  "mid      = " . $mid . ", " .
	*/
  function edit($username)
  {
	$division = isset($_POST['division'])?$_POST['division']:'';
	$cid = $_POST['cid'];
	$active = $_POST['active'];

	if (get_magic_quotes_gpc()) {
		$linkname = trim($_POST['linkname']);
		$author = trim($_POST['author']);
		$notes = trim($_POST['notes']);
	}
	else {
		$linkname = $this->mdb2->escape(trim($_POST['linkname']));
		$author = $this->mdb2->escape(trim($_POST['author']));
		$notes = $this->mdb2->escape(trim($_POST['notes']));
	}

	$query = "UPDATE contents set linkname = '" . $linkname . "', " .
		 "author    = '" . $author ."', " .
		 "notes    = '" . $notes . "', " .
		 "active = '" . $active . "', " .
		 "division = '" . $division . "', updatedby='".$username."' WHERE cid =  " . $cid; 

	$affected = $this->mdb2->exec($query);
	if (PEAR::isError($affected)) {
		die($affected->getMessage(). ' line: [' . __LINE__ . ']. ' . $query);
	}

	$query = "SELECT * FROM contents WHERE cid=" . $cid;
	$row = $this->mdb2->queryRow($query, '', MDB2_FETCHMODE_ASSOC);

	$ary = array();
	$ary['cid'] = $cid;
	$ary['linkname'] = $row['linkname'];
	$ary['author'] = $row['author'];
	$ary['notes'] = $row['notes'];
	$ary['division'] = $row['division'];
	$ary['content'] = $row['content'];
	$encodedArray = array_map("utf8_encode", $ary);
	return $encodedArray;
  }

  // Quotes a string so it can be safely used in a query. It will quote the text so it can safely be used within a query. 
  function add()
  {
	$site_id = $_POST['sites'];
	$mid = $_POST['modules'];
	$division = isset($_POST['divisions'])?$_POST['divisions']:'';
	if(isset($_POST['content'])) $content = $_POST['content'];
	elseif(isset($_POST['elm3'])) $content = $_POST['elm3'];

	if (get_magic_quotes_gpc()) {
		$linkname = trim($_POST['input_linkname_3']);
		$author = trim($_POST['input_title_3']);
		$notes = trim($_POST['input_notes_3']);
	}
	else {
		$linkname = $this->mdb2->escape(trim($_POST['input_linkname_3']));
		$author = $this->mdb2->escape(trim($_POST['input_title_3']));
		$notes = $this->mdb2->escape(trim($_POST['input_notes_3']));
		$content = $this->mdb2->escape($content);
	}
	//http://www.tinymce.com/wiki.php/How-to_implement_TinyMCE_in_PHP: $content = nl2br(htmlentities(stripslashes(trim($_POST['content']))));
	// <cufon...>not work.
	$content = strip_tags($content,$this->allowedTags);

	$query = "INSERT INTO contents (linkname, author, notes, content, createdby, created, updatedby,site_id,mid,division,sname,mname)
		VALUES('".$linkname."', '".$author."', '".$notes."', '" . $content . "', '" . $this->username . "', NOW(), '".$this->username."', ".
		$site_id . ", " . $mid . ", '" . $division . "', '".$this->get_sname_from_sid($site_id)."', '".$this->get_mname_from_mid($mid)."')";

	$affected = $this->mdb2->exec($query);
	if (PEAR::isError($affected)) {
		die($affected->getMessage().' line: ' . __LINE__.$query);
	}
	$id = $this->mdb2->lastInsertID();
	$query = "SELECT * FROM contents WHERE cid=" . $id;
	$row = $this->mdb2->queryRow($query, '', MDB2_FETCHMODE_ASSOC);

	$ary = array();
	$ary['cid'] = $id;
	$ary['linkname'] = $row['linkname'];
	$ary['author'] = $row['author'];
	$ary['notes'] = $row['notes'];
	$ary['content'] = $row['content'];
	$ary['site_id'] = $row['site_id'];
	$ary['mid'] = $row['mid'];
	$ary['division'] = $row['division'];
	$encodedArray = array_map("utf8_encode", $ary);
	return $encodedArray;
  }

  function edit_wysiwyg_table()
  {
	$site_id = $_POST['sites'];
	$mid = $_POST['modules'];
	$division = $_POST['divisions'];
	$cid = $_POST['id'];
	if(isset($_POST['content'])) $content = $_POST['content'];
	else foreach($_POST as $k=>$v) if(preg_match("/^elm_/", $k)) $content = $_POST[$k];

	if (get_magic_quotes_gpc()) {
		$linkname = trim($_POST['input_linkname_3']);
		$author = trim($_POST['input_title_3']);
		$notes = trim($_POST['input_notes_3']);
	}
	else {
		$linkname = $this->mdb2->escape(trim($_POST['input_linkname_3']));
		$author = $this->mdb2->escape(trim($_POST['input_title_3']));
		$notes = $this->mdb2->escape(trim($_POST['input_notes_3']));
		$content = addslashes($content);
		//$content = $this->mdb2->escape(trim($_POST['content'])); if using escape, <> becomes &lt;&gt; not correct.
	}

	//$content = $this->mdb2->escape(strip_tags(stripslashes($content),$this->allowedTags));
	$content = strip_tags($content, $this->allowedTags);

	$query = "UPDATE contents set linkname = '" . $linkname . "', " .
		 "author    = '" . $author ."', " .
		 "notes    = '" . $notes . "', " .
		 "content  = '" . $content . "', " .
		 "site_id  = " . $site_id . ", " .
		 "mid      = " . $mid . ", " .
		 "division = '" . $division . "', updatedby = '".$this->username."' WHERE cid =  " . $cid; 

	$affected = $this->mdb2->exec($query);
	if (PEAR::isError($affected)) {
		die($affected->getMessage() . ' line: ' . __LINE__);
	}

	$query = "SELECT * FROM contents WHERE cid=" . $cid;
	$row = $this->mdb2->queryRow($query, '', MDB2_FETCHMODE_ASSOC);
	$this->print_array($row);

	$ary = array();
	$ary['cid'] = $cid;
	$ary['linkname'] = $row['linkname'];
	$ary['author'] = $row['author'];
	$ary['notes'] = $row['notes'];
	$ary['content'] = $row['content'];
	$encodedArray = array_map("utf8_encode", $ary);
	return $encodedArray;
  }

  // use 'name' instead of 'id' to display 'site'.
  function get_site_name($query) {
	if(isset($query) && $query) {
		if (preg_match("/,\s*site_id,/", $query))
			$query = preg_replace("/site_id/", '(select name from sites where sites.site_id=contents.site_id) as site_id', $query);
		return $query;
	}
	return;
  }
  function get_module_name($query) {
	if(isset($query) && $query) {
		if (preg_match("/,\s*mid,/", $query))
			$query = preg_replace("/mid/", '(select name from modules where modules.mid=contents.mid) as mid', $query);
		return $query;
	}
	return;
  }

  function get_available_resources($cid) {
  	$sql = "SELECT rid, author, file FROM resources WHERE rid NOT IN (SELECT rid from contents_resources cr WHERE cid = " . $cid . ") AND active='Y' ORDER BY author";
	return 	$this->get_select_array($sql);
  }
  function get_assigned_resources($cid) {
  	$sql = "SELECT r.rid, author, file FROM resources r INNER JOIN contents_resources cr WHERE r.rid=cr.rid AND cr.cid = " . $cid . " AND active='Y' ORDER BY author";
	return 	$this->get_select_array($sql);
  }
    
  function get_content_record($cid) {
  	$ary = array();	
  	// $query = "SELECT * FROM vw_contents WHERE cid=".$cid;
  	$query = "SELECT * FROM contents WHERE cid=".$cid;

	$result = $this->mdb2->query($query);
	$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);

	$ary['cid'] = $cid;
	$ary['linkname'] = $row['linkname'];
	$ary['author'] = $row['author'];
	$ary['notes'] = $row['notes'];
	$ary['content'] = $row['content'];
	$ary['site_id'] = $row['site_id'];
	$ary['sname'] = $row['sname'];
	$ary['mid'] = $row['mid'];
	$ary['mname'] = $row['mname'];
	$ary['division'] = $row['division'];
	$encodedArray = array_map("utf8_encode", $ary);
	return $encodedArray;
  }
  // different with resources.php.
  function select_assigned_contents($mid, $site_id)
  {
	$sqlc = "SELECT cid, linkname, author, (SELECT name FROM modules m WHERE m.mid=c.mid) AS mname, division  FROM contents c WHERE active='Y' AND mid=".$mid." AND site_id=".$site_id." ORDER BY linkname";
	return 	$this->get_select_options($sqlc, false);
  }  
  function select_available_contents($mid, $site_id)
  {
	$sqlc = "SELECT cid, linkname, author, (SELECT name FROM modules m WHERE m.mid=c.mid) AS mname, division FROM contents c WHERE active='Y' AND mid!=".$mid." AND site_id=".$site_id." ORDER BY linkname";
	return 	$this->get_select_options($sqlc, false);
  }

  function get_emails($cid)
  {
    $num = rand(1,10);
    if ($num % 2 == 0 ) 
	  $order = " ORDER BY email";
    else
      $order = " ORDER BY email DESC";
	
	$emails = '';  
    $query = "SELECT email FROM onefamily " . $order;
	$res = $this->mdb2->query($query);
    while ($row = $res->fetchRow()) {
      $emails .= $row[0] . ',';
    }
	return preg_replace("/,$/", '', $emails);
  }

  /**
  [cid] => 33
    [linkname] => ...
    [author] => OneFamily
    [notes] => ...
    [content] =>  ...
	  [site_id] => 1
    [sname] => SurreyOneFamily
    [mid] => 1
    [mname] => 
    [division] => 
    [weight] => 0
    [active] => Y
    [createdby] => OneFamily
    [created] => 2012-02-12 22:06:17
    [updatedby] => OneFamily
    [updated] => 2012-02-15 15:03:52	
	*/
  function send_email($cid) 
  {
	$query = "SELECT * FROM contents WHERE cid=" . $cid;	

	$row = $this->mdb2->queryRow($query, '', MDB2_FETCHMODE_ASSOC);

    // by default send_email_flag='N', means this content is not sent email yet.
    if($row['send_email_flag']=='Y') {
	  echo "该信息已经发送过了,请不要重复发送. This content have already sent as email, no sending email anymore.";
	  return false;
	}

    /*
	   $message = "\n\nBrowser Info: " . $_SERVER["HTTP_USER_AGENT"] .
                "\nIP: " . $_SERVER["REMOTE_ADDR"] .
                "\n\nDate: " . date("Y-m-d h:i:s");
	 */
	$message = "邮件来自 http://surreyonefamily.ca ";
	$message .= "\n日期: " . date("Y-m-d h:i:s");
    $message .= $row['content'];

    $siteEmails = $this->get_emails($cid);
    // echo "<pre>";print_r($siteEmails); echo "</pre>";
	// $siteEmails = "williamjxj@hotmail.com,jxjwilliam@gmail.com";
    $emailTitle = '素里华人宣道会一家团契';

    if(! mail($siteEmails, $emailTitle, $message, 'admin@surreyonefamil.ca'))
      echo '邮件没有成功发送.';
	  
	// After sent, set flag='Y' means no re-send.
	$query = "UPDATE contents SET send_email_flag = 'Y' WHERE cid = " . $cid;
	$affected = $this->mdb2->exec($query);
    if (PEAR::isError($affected)) {
      die($affected->getMessage());
    }
    return true;
  }

}

//////////////////////////////////
$cont = new ContentsClass();
if(! $cont->check_access()) {
  $cont->set_breakpoint();
  echo "<script>if(window.opener){window.opener.location.href='".LOGIN."';} else{window.parent.location.href='".LOGIN."';}</script>";exit;
}
$cont->get_table_info();
$cont->set_default_config(array('WYSIWYG'=>true,'dvalidate'=>true,'calender'=>true));

// controller to dispatch the HTTP REQUESTS.
if(isset($_GET['js_search_form'])) {
	$ary = $cont->get_search_form_settings();
	$cont->assign('search_form', $ary);	
	$cont->assign('config', $config);
	$cont->display($config['templs']['search']);
}
elseif(isset($_GET['js_get_record'])) {
	$ret = $cont->get_content_record($_GET['cid']);
	echo json_encode($ret);
}
elseif(isset($_GET['js_edit_form_3'])) {
  $data = array();
  if(!isset($data['self'])) $data['self'] = $cont->self;

  if(isset($_GET['id']))
	$record = $cont->get_record($_GET['id']);
  else
  	$record = array();

  $data['contents'] = $cont->get_contents_array();
  $data['sites'] = $cont->get_sites_array();
  $data['modules'] = $cont->get_modules_array();
  $data['divisions'] = $cont->get_divisions_array();
  $data['record'] = $record;
  
  $cont->assign('config', $config);
  $cont->assign("data", $data);
  $cont->display($config['templs']['edit_tinymce']);
}
elseif(isset($_GET['js_edit_form_4'])) {
  $data = array();
  if(!isset($data['self'])) $data['self'] = $cont->self;
  if(isset($_GET['id']))
	$record = $cont->get_record($_GET['id']);
  else
  	$record = array();

  $data['contents'] = $cont->get_contents_array();
  $data['record'] = $record;
  if(isset($_GET['id'])) {
	  $data['resources1'] = $cont->get_available_resources($_GET['id']);
	  $data['resources2'] = $cont->get_assigned_resources($_GET['id']);
  }
  $data['sites'] = $cont->get_sites_array();
  $data['modules'] = $cont->get_modules_array();
  $data['divisions'] = $cont->get_divisions_array();  
  $cont->assign('config', $config);
  $cont->assign("data", $data);
  $cont->display($config['templs']['assign_mc']); //'assign_modules-contents.tpl.html'
}
elseif(isset($_GET['js_edit_form_5'])) {
  $data = array();
  if(!isset($data['self'])) $data['self'] = $cont->self;
  if(isset($_GET['id']))
	$record = $cont->get_record($_GET['id']);
  else
  	$record = array();

  $data['contents'] = $cont->get_contents_array();
  $data['record'] = $record;
  if(isset($_GET['id'])) {
	  $data['resources1'] = $cont->get_available_resources($_GET['id']);
	  $data['resources2'] = $cont->get_assigned_resources($_GET['id']);
  }
  $data['sites'] = $cont->get_sites_array();
  $data['modules'] = $cont->get_modules_array();
  $data['divisions'] = $cont->get_divisions_array();  
  $cont->assign('config', $config);
  $cont->assign("data", $data);
  $cont->display($config['templs']['assign_rc']); //'assign_resources-contents.tpl.html'
}
elseif(isset($_GET['js_edit_form'])) {
	$ary = $cont->get_edit_form_settings();
	// $cont->get_edit_form($_GET['id'], $ary);
	$record = $cont->get_record($_GET['id']);
	$cont->assign('record', $record);	

        if(isset($_GET['tr'])) $cont->assign('form_id', 'ef_'.$_GET['id'].'-'.$_GET['tr']);
        else $cont->assign('form_id', 'ef_'.$_GET['id']);
 
	$cont->assign('edit_form', $ary);	
	$cont->assign('config', $config);
	$cont->display($config['templs']['edit']);
}
elseif(isset($_GET['js_add_form'])) {
	//$ary = $cont->get_add_form_settings();
	//$cont->get_add_form($ary);
  $data['sites'] = $cont->get_sites_array();
  //$data['modules'] = $cont->get_modules_array();
  $data['divisions'] = $cont->get_divisions_array();
  
  $cont->assign('config', $config);
  $cont->assign("data", $data);
  $cont->display($config['templs']['add_tinymce']); //'add_tinymce.tpl.html'
}
elseif(isset($_REQUEST['js_get_pages'])) {
	$cont->get_pages_options();
}
elseif(isset($_REQUEST['js_get_modules'])) {
	$cont->get_modules_options($_REQUEST['site_id']);
}

elseif(isset($_REQUEST['js_get_divisions'])) {
	$sid = isset($_REQUEST['site_id']) ? $_REQUEST['site_id'] : '';
	$cont->get_divisions_options($sid);
}
elseif(isset($_REQUEST['js_get_contents_by_sid'])) {
	$cont->get_contents_options_by_sid($_REQUEST['site_id']);
}
elseif(isset($_REQUEST['js_get_contents_by_mid'])) {
	$cont->get_contents_options_by_mid($_REQUEST['mid']);
}
elseif(isset($_REQUEST['js_get_contents_by_division'])) {
	$cont->get_contents_options_by_division($_REQUEST['mid'], $_REQUEST['division']);
}
elseif(isset($_GET['js_assign_form'])) {
	$data = array();
	$data['sites'] = $cont->get_sites_array();
	$cont->assign('config', $config);
	$cont->assign('data', $data);
	$cont->display($config['templs']['assign_rc']); //'assign_resources-contents.tpl.html'
}
elseif(isset($_POST['js_update'])) {
	$cont->update_contents_resources();
}
elseif(isset($_POST['js_update_modules_contents'])) {
	$cont->update_modules_contents();
}
elseif(isset($_POST['js_edit_column'])) {
	$ret = $cont->update_column(array('updatedby'=>$cont->username));
	echo json_encode($ret);
}
elseif(isset($_REQUEST['action'])) {
	switch($_REQUEST['action']) {
		case 'edit':
		  echo json_encode($cont->edit($cont->username));
			break;
		case 'delete':
			$cont->delete($_GET['id']);
			break;
		case 'add':
			$cont->create(array('username'=>$cont->username));
			break;    
		default:
			break;
	}
}
elseif (isset($_GET['step'])) {
	//1,2 for modules-contents, 3,4 for contents-resources
	switch($_GET['step']) {
	case 1:
		$cont->select_assigned_contents($_GET['mid'], $_GET['site_id']);
		break;
	case 2:
		$cont->select_available_contents($_GET['mid'], $_GET['site_id']);
		break;
	case 3:
		$cont->select_assigned_resources($_GET['cid'], $_GET['site_id']);
		break;
	case 4:
		$cont->select_available_resources($_GET['cid'], $_GET['site_id']);
		break;
	default:
		break;
	}
}
/*
deprecated: form serialize() with tinymce. escape not work: change '<>' to '&lt;&gt;'.
elseif(isset($_POST['content']) && isset($_POST['js_save'])) {
	$ret = $cont->add();
	echo json_encode($ret);
}*/
// not elm3, it is elm_cid.
elseif(isset($_POST['add_submit'])) {
	$ret = $cont->add();
	echo json_encode($ret);
}
// use pure form submit.
elseif(isset($_POST['edit_submit'])) {
	$ret = $cont->edit_wysiwyg_table();
	echo json_encode($ret);
}
else if( isset($_POST['search']) || (isset($_GET['page']) && isset($_GET['sort'])) || isset($_GET['page']) || isset($_GET['js_reload_list']) ) {
	if(isset($_POST['search'])) $cont->parse();

	$data = $cont->list_all();
	// $data['options'] = array(VIEW, EDIT, RESOURCE, DELETE);
	$data['options'] = array(VIEW, EDIT, EMAIL, DELETE);
	
	$header = $cont->get_header($cont->get_mappings());

	$pagination = $cont->draw( $data['current_page'], $data['total_pages'] );
	
	$cont->assign('config', $config);
	$cont->assign('header', $header);
	$cont->assign('data', $data);
	$cont->assign("pagination", $pagination);
	$tpl = $cont->get_html_template();
	$cont->display($tpl); // not use display, should use AJAX.
}
elseif(isset($_REQUEST['js_send_mail'])) {
	$cont->send_email($_REQUEST['id']);
}
else {
	if (isset($_SESSION[$cont->self][$cont->session['sql']])) $_SESSION[$cont->self][$cont->session['sql']] = '';

	$total_rows = $cont->get_total_rows($cont->get_count_sql());

	$_SESSION[$cont->self][$cont->session['rows']] = $total_rows < 1 ? 1 : $total_rows;
	
	$data = $cont->list_all();
	$data['options'] = array(VIEW, EDIT, EMAIL, DELETE);
	
	$header = $cont->get_header($cont->get_mappings());

	$pagination = $cont->draw( $data['current_page'], $data['total_pages'] );
	
	$tpl = $cont->get_html_template();

	$cont->assign('config', $config);
	$cont->assign('header', $header);
	$cont->assign('data', $data);
	$cont->assign("pagination", $pagination);

	$cont->assign("template", $tpl);
	$cont->display($config['templs']['main']);
}
?>
