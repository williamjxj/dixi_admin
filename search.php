<?php
$my_server ="http://".getenv("SERVER_NAME").":".getenv("SERVER_PORT");
$my_root='.'; //$my_root = getenv("DOCUMENT_ROOT"); 
$s_dirs = array("/apps","/configs"); 
$s_skip = array("..",".","templates_c", "themes", "mysql", "include", ".settings", "cache"); 
$s_files = "html|htm|HTM|HTML|php3|php4|php|txt|sql"; 
$min_chars = "3"; 
$max_chars = "30"; 
$default_val = "Searchphrase"; 
$limit_hits = array("5","10","25","50","100"); 
$message_1 = "Invalid Searchterm!"; 
$message_2 = "Please enter at least '$min_chars', highest '$max_chars' characters."; 
$message_3= "Your searchresult for:"; 
$message_4 = "Sorry, no hits."; 
$message_5 = "results"; 
$message_6 = "Match case"; 
$no_title = "Untiteled"; 
$limit_extracts_extracts = ""; 
$byte_size = "51200";

function search_form($_GET, $limit_hits, $default_val, $message_5, $message_6, $PHP_SELF) {
	@$keyword=$_GET['keyword'];
	@$case=$_GET['case'];
	@$limit=$_GET['limit'];
	echo
	"<form action=\"$PHP_SELF\" method=\"GET\">\n",
	"<input type=\"hidden\" value=\"SEARCH\" name=\"action\">\n",
	"<input type=\"text\" name=\"keyword\" class=\"text\" size=\"10\"  maxlength=\"30\" value=\"";
	if(!$keyword)
		echo "$default_val";
	else
		echo str_replace("&amp;","&",htmlentities($keyword));
	echo "\" ";
	echo "onFocus=\" if (value == '";
	if(!$keyword)
		echo "$default_val"; 
	else
		echo str_replace("&amp;","&",htmlentities($keyword));
	echo "') {value=''}\" onBlur=\"if (value == '') {value='";
	if(!$keyword)
		echo "$default_val"; 
	else
		echo str_replace("&amp;","&",htmlentities($keyword));
	echo "'}\"> ";
	$j=count($limit_hits);
	if($j==1)
		echo "<input type=\"hidden\" value=\"".$limit_hits[0]."\" name=\"limit\">";
	elseif($j>1) {
		echo
		"<select name=\"limit\" class=\"select\">\n";
		for($i=0;$i<$j;$i++) {
			echo "<option value=\"".$limit_hits[$i]."\"";
			if($limit==$limit_hits[$i])
				echo "SELECTED";
			echo ">".$limit_hits[$i]." $message_5</option>\n";
			}
		echo "</select> ";
		}
	echo
	"<input type=\"submit\" value=\"OK\" class=\"button\">\n",
	"<br>\n",
	"<span class=\"checkbox\">$message_6</span> <input type=\"checkbox\" name=\"case\" value=\"true\" class=\"checkbox\"";
	if($case)
		echo " CHECKED";
	echo
	">\n",
	"<br>\n",
	"<a href=\"http://www.terraserver.de/\" class=\"ts\" target=\"_blank\">Powered by terraserver.de/search</a>",
	"</form>\n";
	}



function search_headline($_GET, $message_3) {
	@$keyword=$_GET['keyword'];
	@$action=$_GET['action'];
	if($action == "SEARCH") 
		echo "<h1 class=\"result\">$message_3 '<i>".htmlentities(stripslashes($keyword))."</i>'</h1>";
	}



function search_error($_GET, $min_chars, $max_chars, $message_1, $message_2, $limit_hits) {
	global $_GET;
	@$keyword=$_GET['keyword'];
	@$action=$_GET['action'];
	@$limit=$_GET['limit'];
	if($action == "SEARCH") { 
		if(strlen($keyword)<$min_chars||strlen($keyword)>$max_chars||!in_array ($limit, $limit_hits)) { 
			echo "<p class=\"result\"><b>$message_1</b><br>$message_2</p>";
			$_GET['action'] = "ERROR"; 
			}
		}
	}



function search_dir($my_server, $my_root, $s_dirs, $s_files, $s_skip, $message_1, $message_2, $no_title, $limit_extracts, $byte_size, $_GET) {
//echo "<pre>";print_r($_GET);echo "<pre>";
//echo "<pre>";print_r($s_dirs);echo "<pre>";echo $my_root;
	global $count_hits;
	@$keyword=$_GET['keyword'];
	@$action=$_GET['action'];
	@$limit=$_GET['limit'];
	@$case=$_GET['case'];
	if($action == "SEARCH") { 
		foreach($s_dirs as $dir) { 
			$handle = @opendir($my_root.$dir);
			echo $my_root.$dir."<br>\n";
			while($file = @readdir($handle)) {
				echo $file."<br>\n";
				if(in_array($file, $s_skip)) { 
					continue;
					}
				elseif($count_hits>=$limit) {
					break; 
					}
				elseif(is_dir($my_root.$dir."/".$file)) { 
					$s_dirs = array("$dir/$file");
					search_dir($my_server, $my_root, $s_dirs, $s_files, $s_skip, $message_1, $message_2, $no_title, $limit_extracts, $byte_size, $_GET); 
					}
				elseif(preg_match("/($s_files)$/i", $file)) { 
					$fd=fopen($my_root.$dir."/".$file,"r");
					$text=fread($fd, $byte_size); 
					$keyword_html = htmlentities($keyword);
					if($case) { 
						$do=strstr($text, $keyword)||strstr($text, $keyword_html);
						}
					else {
						$do=stristr($text, $keyword)||stristr($text, $keyword_html);
						}
					if($do)	{
						$count_hits++; 
						if(preg_match_all("=<title[^>]*>(.*)</title>=siU", $text, $titel)) { 
							if(!$titel[1][0]) 
								$link_title=$no_title; 
							else
								$link_title=$titel[1][0];  
							}
						else {
							$link_title=$no_title; 
							}
						echo "<a href=\"$my_server$dir/$file\" target=\"_self\" class=\"result\">$count_hits.  $link_title</a><br>"; 
						$auszug = strip_tags($text);
						$keyword = preg_quote($keyword); 
						$keyword = str_replace("/","\/","$keyword");
						$keyword_html = preg_quote($keyword_html); 
						$keyword_html = str_replace("/","\/","$keyword_html");
						echo "<span class=\"extract\">";
						if(preg_match_all("/((\s\S*){0,3})($keyword|$keyword_html)((\s?\S*){0,3})/i", $auszug, $match, PREG_SET_ORDER)); {
							if(!$limit_extracts)
								$number=count($match);
							else
								$number=$limit_extracts;
							for ($h=0;$h<$number;$h++) { 
								if (!empty($match[$h][3]))
									printf("<i><b>..</b> %s<b>%s</b>%s <b>..</b></i>", $match[$h][1], $match[$h][3], $match[$h][4]);
								}
							}
						echo "</span><br><br>";
						flush();
						}
					fclose($fd);
					}
				}
	  		@closedir($handle);
			}
		}
	}



function search_no_hits($_GET, $count_hits, $message_4) {
	@$action=$_GET['action'];
	if($action == "SEARCH" && $count_hits<1) 
		echo "<p class=\"result\">$message_4</p>";
	}

?>

<!DOCTYPE HTML>
<html>
<head>
<style type="text/css">
input.text  {
	font-family : verdana, arial,helvetica,sans-serif;
	color : #000000;
	font-weight : normal;
	font-size : 12px;
	text-decoration : none;
	width : 120px;
}

input.button  {
	font-family : verdana, arial,helvetica,sans-serif;
	color : #000000;
	font-weight : normal;
	font-size : 12px;
	text-decoration : none;
}

input.checkbox  {
	font-family : verdana, arial,helvetica,sans-serif;
	color : #000000;
	font-weight : normal;
	font-size : 12px;
	text-decoration : none;
}

span.checkbox  {
	font-family : verdana, arial,helvetica,sans-serif;
	color : #000000;
	font-weight : normal;
	font-size : 11px;
	text-decoration : none;
}

select.select  {
	font-family : verdana, arial,helvetica,sans-serif;
	color : #000000;
	font-weight : normal;
	font-size : 12px;
	text-decoration : none;
}

h1.result  {
	font-family : verdana, arial,helvetica,sans-serif;
	color : #000000;
	font-weight : bold;
	font-size : 14px;
	text-decoration : none;
}

p.result  {
	font-family : verdana, arial,helvetica,sans-serif;
	color : #000000;
	font-weight : normal;
	font-size : 12px;
	text-decoration : none;
}

a.result:link  {
	font-family : verdana, arial,helvetica,sans-serif;
	color : #03629C;
	font-weight : bold;
	font-size : 12px;
	text-decoration : none;
}

a.result:visited  {
	font-family : verdana, arial,helvetica,sans-serif;
	color : #03629C;
	font-weight : bold;
	font-size : 12px;
	text-decoration : none;
}

a.result:active  {
	font-family : verdana, arial,helvetica,sans-serif;
	color : #9D9D9D;
	font-weight : bold;
	font-size : 12px;
	text-decoration : underline;
}

a.result:hover  {
	font-family : verdana, arial,helvetica,sans-serif;
	color : #9D9D9D;
	font-weight : bold;
	font-size : 12px;
	text-decoration : underline;
}

span.extract  {
	font-family : verdana, arial,helvetica,sans-serif;
	color : #000000;
	font-weight : normal;
	font-size : 11px;
	text-decoration : none;
}

a.ts:link  {
	font-family : verdana, arial,helvetica,sans-serif;
	color : #03629C;
	font-weight : normal;
	font-size : 9px;
	text-decoration : none;
}

a.ts:visited  {
	font-family : verdana, arial,helvetica,sans-serif;
	color : #03629C;
	font-weight : normal;
	font-size : 9px;
	text-decoration : none;
}

a.ts:active  {
	font-family : verdana, arial,helvetica,sans-serif;
	color : #9D9D9D;
	font-weight : normal;
	font-size : 9px;
	text-decoration : underline;
}

a.ts:hover  {
	font-family : verdana, arial,helvetica,sans-serif;
	color : #9D9D9D;
	font-weight : normal;
	font-size : 9px;
	text-decoration : underline;
}

</style>
	<title>terraserver.de/search</title>
</head>
<body bgcolor="#FFFFFF" text="#000000" link="#03629C" vlink="#03629C" alink="#9D9D9D">
<table border="0" cellspacing="1" cellpadding="0" bgcolor="#03629C">
  <tr align="left" valign="top">
	<td>
	  <table border="0" cellspacing="0" cellpadding="3" bgcolor="#FFFFFF">
		<tr align="left" valign="top">
		  <td>
<?

search_form($_GET, $limit_hits, $default_val, $message_5, $message_6, $PHP_SELF);
?>
		  </td>
		</tr>
	  </table>	
	</td>
  </tr>
</table>
<?

search_headline($_GET, $message_3);

search_error($_GET, $min_chars, $max_chars, $message_1, $message_2, $limit_hits);

search_dir($my_server, $my_root, $s_dirs, $s_files, $s_skip, $message_1, $message_2, $no_title, $limit_extracts, $byte_size, $_GET);

search_no_hits($_GET, $count_hits, $message_4);
?>
</body>
</html>

