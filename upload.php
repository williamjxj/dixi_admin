<?php
session_start();
define('SITEROOT', '.');
require_once(SITEROOT.'/configs/base.inc.php');

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$mdb2 = BaseClass::pear_connect_admin();

if(isset($_POST['sites'])) {
	$t = get_sname_from_sid($_POST['sites'], $mdb2);
}
else {
	$t = 'generic';
}
//define('DIRECTORY_SEPARATOR', '/');
//$targetDir = SITEROOT . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . $t; SITEROOT .  '/resources/' . $t . '/';
$targetDir = './resources/' . $t . '/';
if(!file_exists($targetDir)) mkdir($targetDir);

@set_time_limit(5 * 60);

$chunk = isset($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;
$chunks = isset($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;
$fileName = isset($_FILES['file']['name']) ? $_FILES['file']['name'] : (isset($_REQUEST["name"]) ? $_REQUEST["name"] : '');

if ($chunks < 2 && file_exists($targetDir . $fileName)) {
	$ext = strrpos($fileName, '.');
	$fileName_a = substr($fileName, 0, $ext);
	$fileName_b = substr($fileName, $ext);

	$count = 1;
	while (file_exists($targetDir . $fileName_a . '_' . $count . $fileName_b))
		$count++;

	$fileName = $fileName_a . '_' . $count . $fileName_b;
}

// if (!file_exists($targetDir)) @mkdir($targetDir);
if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
	$contentType = $_SERVER["HTTP_CONTENT_TYPE"];

if (isset($_SERVER["CONTENT_TYPE"]))
	$contentType = $_SERVER["CONTENT_TYPE"];

if (strpos($contentType, "multipart") !== false) {
	if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
		// Open temp file
		$out = fopen($targetDir . $fileName, $chunk == 0 ? "wb" : "ab");
		if ($out) {
			// Read binary input stream and append it to temp file
			$in = fopen($_FILES['file']['tmp_name'], "rb");

			if ($in) {
				while ($buff = fread($in, 4096))
					fwrite($out, $buff);
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
			fclose($in);
			fclose($out);
			@unlink($_FILES['file']['tmp_name']);
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
	} else
		die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
} else {
	// Open temp file
	$out = fopen($targetDir . $fileName, $chunk == 0 ? "wb" : "ab");
	if ($out) {
		// Read binary input stream and append it to temp file
		$in = fopen("php://input", "rb");

		if ($in) {
			while ($buff = fread($in, 4096))
				fwrite($out, $buff);
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

		fclose($in);
		fclose($out);
	} else
		die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
}

// if (!file_exists(RESOURCES_DIR)) mkdir(RESOURCES_DIR) or die ("Permission not allowed");
if( isset($_FILES) && is_array($_FILES) && count($_FILES)>0 ) {
	process_form($mdb2, $targetDir);
}

////////////////////////////////////////
function process_form($mdb2, $rdir)
{
	$h = array();
	$h['author'] = $mdb2->escape(trim($_POST['author']));
	$h['notes'] = $_POST['notes']?$mdb2->escape(trim($_POST['notes'])):'';
	$h['uploader'] = isset($_SESSION['admin']['username']) ? $_SESSION['admin']['username'] : 'admin';

	$site_id = $_POST['sites'];
	$mid = $_POST['modules'];
	$division = $_POST['divisions'];
	
	$h['file'] = $_FILES['file']['name'];
	$h['type'] = $_FILES['file']['type'];
	$h['size'] = (int)$_FILES['file']['size'];

	$query = "INSERT INTO resources(file,type,size,path,author,notes,createdby, created, updatedby, site_id,mid,division,sname,mname) VALUES(
	  '".$h['file']."', '".$h['type']."', ".$h['size'].", '".$rdir."', '".$h['author']."', '".$h['notes']."', '".$_SESSION['admin']['username']."', NOW(), '".$admin."', ".$site_id.", ".$mid.", '". $division . "', '".get_sname_from_sid($site_id, $mdb2)."', '".get_mname_from_mid($mid, $mdb2)."')";

	$affected = $mdb2->exec($query);
	if (PEAR::isError($affected)) {
		die($affected->getMessage().'[ line '.__LINE__.' ]'.$query);
	}
	return true;
}
// select one cell
function get_file($file, $mdb2)
{
  $site_id = $_POST['sites'];
  $mid = $_POST['modules'];

  $sql1 = "SELECT count(*) FROM resources WHERE file='".$mdb2->escape($file)."' AND mid=".$mid." AND site_id=".$site_id;
  echo $sql."<br>\n"; // strange err: missing ) in parenthetical
  $total = $mdb2->queryOne($sql1);
  if($total>0) return true;
  return false;
}

function get_sname_from_sid($site_id, $mdb2)
{
  $sql = "SELECT name FROM sites WHERE site_id=".$site_id;
  $result = $mdb2->queryOne($sql);
  return preg_replace("/\s/", '_', strtolower($result));
}


  function get_mname_from_mid($mid, $mdb2)
  {
  	$sql = "SELECT name FROM modules WHERE mid=".$mid;
	$mname = $mdb2->queryOne($sql);
	return $mname;
  }

?>