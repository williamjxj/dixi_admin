<?php
session_start();
define('SITEROOT', './');
include_once(SITEROOT.'configs/setting.inc.php');
include_once(SITEROOT.'configs/config.inc.php');
require_once(SITEROOT.'configs/base.inc.php');

global $config;
if(isset($_COOKIE['admin']['path']) && (!empty($_COOKIE['admin']['path'])))
	$config['path'] = SITEROOT.'themes/'.$_COOKIE['admin']['path'].'/';
else 
	$config['path'] = SITEROOT.'themes/default/';
$config['list'] = get_list_defs($config['path']);

class Login extends BaseClass
{
	var $project;
	function __construct(){
		parent::__construct();
		$this->project = $this->get_basename();
	}
	function get_basename() {
		return basename(getcwd());
	}
	function get_userid() {
		return 'userid';
		return $this->project.'_userid'; // return 'demo_userid.
	}
	function get_username() {
		return 'username';
		return $this->project.'_username'; // return 'demo_username.
	}
	function get_userpass() {
		return 'userpass';
		return $this->project.'_userpass'; // return 'demo_userpass, for cookie.
	}
	function get_userlevel() {
		return 'userlevel';
		return $this->project.'_userlevel'; // return 'demo_userlevel for left.tpl.html.
	}
	function initial() {
	global $config;
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$config['header']['title'];?></title>
<link rel="stylesheet" type="text/css" href="css/login.css"/>
<link rel="stylesheet" type="text/css" href="<?=$config['path'];?>css/pams-style.css"/>
<link rel="stylesheet" type="text/css" href="<?=$config['path'];?>css/validationEngine.jquery.css" />
<script language="javascript" type="text/javascript" src="<?=$config['path'];?>js/jquery-1.5.1.min.js"></script>
<script language="javascript" type="text/javascript" src="<?=$config['path'];?>js/cookie.js"></script>
<script language="javascript" type="text/javascript" src="<?=$config['path'];?>js/jquery.validationEngine-en.js"></script>
<script language="javascript" type="text/javascript" src="<?=$config['path'];?>js/jquery.validationEngine.js"></script>
<script language="javascript" type="text/javascript" src="include/init.js"></script>
<script language="javascript" type="text/javascript">
$(document).ready(function() {
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
					document.location.href='index.php';
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
if( $.cookie("demo[username]") && $.cookie("demo[userpass]") ) {
	$('#username').val($.cookie("demo[username]"));
	$('#password').val($.cookie("demo[userpass]"));	
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
    <div id="upper">
        <div class="pamslogo"></div>
    </div>
    <div class="headerText">
        <h1><?=$config['header']['title'];?></h1>
    </div>
    <!--div id="st" style="cursor: pointer;">切至繁体版</div-->
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
              <span id="msg" name="msg" style="display: none"><?=$config['list']['wait1'];?></span>
              <!--<input type="reset" value="Reset" />-->
          </div>
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

		$query = "SELECT * FROM ". ADMIN_USER . " WHERE username='".$username."' AND password='".$password."'";
		
		$res = $this->mdb2->query($query);
		if (PEAR::isError($res)) {
			die($res->getMessage().__LINE__.$query);
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
	$session = $this->get_session();
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
	$query = "update login_info set logout='Y', logout_time=NULL, session=NULL where session='".$this->get_session()."'";
	$affected = $this->mdb2->exec($query);
	if (PEAR::isError($affected)) {
		die($affected->getMessage());
	}
  }
  
  function get_browser() {
	return $_SERVER["HTTP_USER_AGENT"]; //Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.2.13) Gecko/20101203 Firefox/3.6.13
  }
  function get_real_ip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    else
      $ip=$_SERVER['REMOTE_ADDR'];
    return $ip;
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