<?php
define("DBHOST", "localhost");
define('DBUSER', 'dixitruth');
define("DBPASS", "dixi123456");
define('DBNAME', 'dixi');

require_once('MDB2.php');

function pear_connect_admin() 
{
	$dsn = array (
		'phptype' => 'mysqli',
		'username' => DBUSER,
		'password' => DBPASS,
		'hostspec' => DBHOST,
		'database' => DBNAME
	);
	$options = array(
		'debug'       => 2,
		'persistent'  => true,
		'portability' => MDB2_PORTABILITY_ALL,
	);
	$mdb2 = MDB2::factory($dsn, $options);
	if (PEAR::isError($mdb2)) {
		die($mdb2->getMessage());
	}
	$mdb2->query("SET NAMES 'utf8'");
	return $mdb2;
}
	
?>
