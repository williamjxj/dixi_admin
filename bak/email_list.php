<?php
session_start();
error_reporting(E_ALL);
define('SITEROOT', './');

ini_set('include_path',ini_get('include_path').PATH_SEPARATOR.SITEROOT.'configs/'.PATH_SEPARATOR.SITEROOT.'include/');
require_once("setting.inc.php");
require_once("config.inc.php");
require_once("ListAdvanced.inc.php");
global $config;

$config['tabs'] = array('1'=>'List Emails');


class EmailsClass extends ListAdvanced
{
  var $url, $self, $session;
  public function __construct() {
    parent::__construct();
  }

  function get_emails_asc()
    $query = "SELECT email FROM onefamily ORDER BY email";
	$res = $this->mdb2->query($query);
    while ($row = $res->fetchRow()) {
      $emails .= $row[0] . ',';
    }
	return preg_replace("/,$/", '', $emails);
  }
  function get_emails_desc()
    $query = "SELECT email FROM onefamily ORDER BY email DESC";
	$res = $this->mdb2->query($query);
    while ($row = $res->fetchRow()) {
      $emails .= $row[0] . ',';
    }
	return preg_replace("/,$/", '', $emails);
  }

}

$email = new EmailsClass();
if(! $email->check_access()) {
  $email->set_breakpoint();
  echo "<script>if(window.opener){window.opener.location.href='".LOGIN."';} else{window.parent.location.href='".LOGIN."';}</script>";exit;
}

	
	$header = $email->get_header($email->get_mappings());

	$pagination = $email->draw( $data['current_page'], $data['total_pages'] );
	
	$tpl = $email->get_html_template();

	$email->assign('config', $config);
	$email->assign('header', $header);
	$email->assign('data', $data);

	$email->assign("template", $tpl);
	$email->display($config['templs']['main']);
}
?>
