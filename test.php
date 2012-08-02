<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>db2312 - big5</title>
<script language="javascript" type="text/javascript" src="include/jquery-1.6.4.min.js"></script>
<script language="javascript" type="text/javascript" src="include/init.js"></script>
</head>
<body>
<div id="st" style="cursor: pointer;">切至繁体版</div>
<?php
//grant all on church.* to williamjxj@localhost identified by 'William1!'

$host = 'localhost';
$user = 'admin';
$pwd = '~@$^*)97531kjhgfdsa';
$db = 'admin';

  $link = mysql_connect($host, $user, $pwd);

  mysql_select_db($db);

  if( mysql_error() ) { print "Database ERROR: " . mysql_error(); }
  mysql_query("SET NAMES 'utf8'", $link);
  
  $sql = "select * from admin_users";
  $res = mysql_query($sql);
  echo "<ul>\n";
  while ($row = mysql_fetch_assoc($res)) {
  	echo "<li>";
  	foreach($row as $v) echo htmlspecialchars($v) . "\t";
	echo "</li>\n"; 
  }
  echo "</ul>\n";
  echo "<hr>\n";
  mdb2_test();
  
function mdb2_test()
{
 define('SITEROOT', './');
  require_once("configs/base.inc.php");
  $bc = new BaseClass();
  $sql = "select * from admin_users";
  $res = $bc->mdb2->query($sql);
  if (PEAR::isError($res)) {
	die($res->getMessage().'['.__LINE__.']:'.$sql);
  }
  while ($row=$res->fetchRow(MDB2_FETCHMODE_ASSOC)) {
   echo "<pre>"; print_r($row); echo "</pre>";
  }
}
?>
</body></html>