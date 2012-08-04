<?php
session_start();
define('SITEROOT', '../');
require_once(SITEROOT.'/configs/setting.inc.php');
require_once(SITEROOT.'/configs/base.inc.php');

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$mdb2 = BaseClass::pear_connect_admin();

if(! isset($_FILES['keywords_file']['size']) || $_FILES['keywords_file']['size']==0 ) {
	echo "无法识别此文件，退出。";
	return false;
}

$fname  = $_FILES['keywords_file']['tmp_name'];	
$fileName = $_FILES['keywords_file']['name'];
$username = '管理员';

if ($fname) {
  //获取文件的编码方式
  $contents = file_get_contents($fname);
  $encoding = mb_detect_encoding($contents, array('GB2312','GBK','UTF-16','UCS-2','UTF-8','BIG5','ASCII'));
	

  $fp=fopen($fname,"r");//以只读的方式打开文件
  $text = "";
  $num = 0;

/*
  if(!(feof($fp))) {
	  $num++;
	  $str = trim(fgets($fp));
	  if ($encoding != false) {
		  $str = iconv($encoding, 'UTF-8', $str);
		  if ($str != "" and $str != NULL) {
			  $text = $str;
		  }
	  }
	  else {
		  $str = mb_convert_encoding ( $str, 'UTF-8','Unicode');
		  if ($str != "" and $str != NULL) {
			  $text = $str;
		  }
	  }
  }
  */
  $count1 = 0; $count2 = 0; $count3 = 0;
  while(!(feof($fp))) {
	  $str = '';
	  $str = trim(fgets($fp));

	  if ($encoding != false) {
		  $str = iconv($encoding, 'UTF-8', $str);
		  if ($str != "" and $str != NULL) {
			  $text = $str;
		  }
		  else continue; //空行
	  }
	  else {
		  $str = mb_convert_encoding ( $str, 'UTF-8','Unicode');
		  if ($str != "" and $str != NULL) {
			  $text = $str;
		  }
		  else  continue; //空行
	  }
	$count1 ++;
	$query = "insert into keywords(keyword,createdby,created) 
		values('".$text . "', '".$username."', now())";
	
	$affected = $mdb2->exec($query);
	if (PEAR::isError($affected)) {
		$count2 ++;
		//如果失败，比如重复的关键词，提示，继续下面的插入，不退出。
		//最好输出到日志文件。
		//echo "<pre>" . $affected->getMessage() . ' [' . __FILE__ . ']: ' . $query . "</pre>";
	}
	else $count3 ++;
  }

  echo "文件 [$fileName] 上载插入完毕，尝试插入( $count1 )个关键词，插入成功( $count3 )，插入不成功( $count2 )->数据库中已存在该关键词。共传输 [" . $_FILES['keywords_file']['size'] . "] 字节。";
}

?>
