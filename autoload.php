<?php

function __autoload($class_name) {
  ini_set('include_path',ini_get('include_path').PATH_SEPARATOR.SITEROOT.'configs/'.PATH_SEPARATOR.SITEROOT.'include/');
  require_once("setting.inc.php");
  require_once("config.inc.php");
  require_once("ListAdvanced.inc.php");

}

?>