<?php
session_start();
define('SITEROOT', './');
require_once(SITEROOT.'configs/mini-app.inc.php');

class Login
{
	var $project;
	function __construct(){
		$this->project = 'dixitruth_admin';
		$this->path = SITEROOT.'login/';
		$this->mdb2 = pear_connect_admin();
	}
	public function initial() {
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>底细,真相,还原真相,反映实际情况</title>
<link rel="stylesheet" type="text/css" href="<?=$this->path;?>login.css"/>
<link rel="stylesheet" type="text/css" href="<?=SITEROOT;?>include/validationEngine/validationEngine.jquery.css" />
<script type="text/javascript" src="<?=SITEROOT;?>include/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="<?=SITEROOT;?>include/js/cookie.js"></script>
<script type="text/javascript" src="<?=SITEROOT;?>include/validationEngine/jquery.validationEngine-en.js"></script>
<script type="text/javascript" src="<?=SITEROOT;?>include/validationEngine/jquery.validationEngine.js"></script>
<script type="text/javascript">
$(function() {
var form = $('#login_form');
form.validationEngine();
$(form).submit(function(event){
	event.preventDefault();
	if (form.validationEngine({returnIsValid:true})) {
		$.ajax({
			type: $(this).attr('method'),
			url: $(this).attr('action'),
			data: $(this).serialize(),
            dataType: 'json',
			beforeSend: function() {
				$('#submit').hide();
				$('#msg').show();
			},
			success: function(data) {
				if(data instanceof Object) {
					document.location.href='./index.php';
				}
				else {
					$('#submit').show();
					$('#msg').hide();
					if ($('#div1').length>0) {
						$('#div1').show();
					} else {
						$('<div></div>').attr({'id':'div1','class':'noUser'}).html("No such user, Please try again.").insertAfter(form);
						$('#username').select().focus();	
					}
					if(data instanceof Array) {
						alert(data);
					}
				}
			}
		});
	}
	return false;
});
if( $.cookie("dixitruth_admin[username]") && $.cookie("dixitruth_admin[userpass]") ) {
	$('#username').val($.cookie("dixitruth_admin[username]"));
	$('#password').val($.cookie("dixitruth_admin[userpass]"));	
	$('#rememberme').attr('checked', true);
}
else {
	$('#rememberme').attr('checked', false);
}
$('#username').select().focus();	
});
</script>
</head>
<body>
<div class="dixilogo"></div>
<div class="headerText">
  <h1>底细,真相,还原真相,反映实际情况</h1>
</div>
<div id="div_login">
  <form id="login_form" action="<?=$_SERVER['PHP_SELF'];?>" method="post">
    <div class="user">
      <label class="userTitle" for="username">用户名：</label>
      <input class="validate[required,length[4,20]] userInput" id="username" name="username" type="text"   onfocus="this.select();" />
    </div>
    <div class="pass">
      <label class="passTitle" for="password">口令：</label>
      <input class="validate[required,length[4,20]] passInput" type="password" id="password" name="password" />
    </div>
    <div class="rememberLoginWrap">
      <div class="rememfor">
        <label class="remember" for="rememberme">
        <input id="rememberme" name="rememberme" type="checkbox" value="" class="checkbox" />
        Remember me</label>
      </div>
      <div class="loginButton">
        <input type="submit" id="submit" value="Login" />
        <span id="msg" name="msg" style="display: none"> <img src="<?=$this->path;?>images/spinner.gif" width="16" height="16" border="0"> </span> </div>
    </div>
  </form>
</div>
<?
	}
	
	function check_user()
	{
		$username = $this->mdb2->escape(trim($_POST['username']));
		$password =  $_POST['password'];
		$rememberme = isset($_POST['rememberme']) ? true : false;

		$query = "SELECT * FROM admin_users WHERE username='".$username."' AND password='".$password."'";
		
		$res = $this->mdb2->query($query);
		if (PEAR::isError($res)) {
			echo __FILE__.'['.__LINE__.']'.$query;
			die($res->getMessage());
		}
		$total = $res->numRows();
		if ($total>0) {
			$username = ucfirst(strtolower($username));
			if($rememberme) {
				$expire = time() + 1728000; // Expire in 20 days
				setcookie($this->project.'['.$this->get_username().']', $username, $expire);
				setcookie($this->project.'['.$this->get_userpass().']', $password, $expire);
			}
			else {
				setcookie($this->project.'['.$this->get_username().']', NULL);
				setcookie($this->project.'['.$this->get_userpass().']', NULL);
			}
			$row = $res->fetchRow(MDB2_FETCHMODE_ASSOC);
			$_SESSION[$this->project][$this->get_username()] = $username;
			$_SESSION[$this->project][$this->get_userid()] = $row['uid'];
			$_SESSION[$this->project][$this->get_userlevel()] = $row['level'];
			$this->update_login_info($username, $row['uid']);
			return $row;
		}
		$res->free();
		return false;
	}

	function update_login_info($username, $uid)  {
		$ip = $this->get_real_ip();
		$browser = $this->get_browser();
		$session = session_id();
		$query = "insert into login_info(uid,ip,browser,username,session,count,login_time,logout,logout_time, expired)
		  values(".$uid.", '".$ip."', '".$this->mdb2->escape($browser)."', '".$username."', '".$session."', 1, NULL, 'N', '', NOW() + INTERVAL 10 HOUR)
		  on duplicate key update
		  count = count+1,
		  login_time = NULL,
		  expired = NOW() + INTERVAL 10 HOUR,
		  session = '".$session."', 
		  logout='N',
		  logout_time=''";
		$affected = $this->mdb2->exec($query);
		if (PEAR::isError($affected)) {
			die($affected->getMessage());
		}
	}
	
	function update_logout_info() {
		$query = "update login_info set logout='Y', logout_time=NULL, session=NULL where session='".session_id()."'";
		$affected = $this->mdb2->exec($query);
		if (PEAR::isError($affected)) {
			die($affected->getMessage());
		}
	}
  
	//Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13  
	private function get_browser() {
		return $_SERVER["HTTP_USER_AGENT"]; 
	}
  
	private function get_real_ip() {
		if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
		  $ip=$_SERVER['HTTP_CLIENT_IP'];
		elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
		  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		else
		  $ip=$_SERVER['REMOTE_ADDR'];
		return $ip;
	}
	
	// dixitruth['userid'];
	private function get_userid() {
		return 'userid';
	}
	// dixitruth['username'];
	private function get_username() {
		return 'username';
	}
	// dixitruth['userpass'];
	private function get_userpass() {
		return 'userpass';
	}
	private function get_userlevel() {
		return 'userlevel';
	}

}


//////////////////////////

$login = new Login();

if (isset($_POST['username']) && isset($_POST['password'])) {
	$ret = $login->check_user();
	if($ret) echo json_encode($ret);
}
elseif(isset($_GET['logout'])) {
	$login->update_logout_info();
	session_unset();
	session_destroy();
	$login->initial();
	echo "</body>\n</html>\n";
	exit;
}
else {
	$login->initial();
	echo "</body>\n</html>\n";
}
?>
