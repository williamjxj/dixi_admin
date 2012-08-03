<?php
session_start();
ob_start(); //Turn on output buffering.

define('SITEROOT', './');
require_once(SITEROOT.'/configs/setting.inc.php');
require_once(SITEROOT.'configs/base.inc.php');

$mdb2 = BaseClass::pear_connect_admin();

if (isset($_POST['xls']) && isset($_POST['from'])) {

	$self = $_POST['from'];
	$query = $_SESSION[$self][$self.'_sql'];
	$query = preg_replace("/limit.*(order by|$)/i", '', $query);

	$fp = fopen('php://output', 'w');
	if ($fp) {
		header('Content-Type: application/csv');
		header("Content-Disposition: attachment; filename=".$_POST['from'].'_'.date("Y-m-d").".csv");
		header('Pragma: no-cache');
		header('Expires: 0');

		// fputcsv($fp, explode(',',$_POST['headers']));
		
		$res = $mdb2->query($query);
		if (PEAR::isError($mdb2)) die($mdb2->getMessage());     
	
		fputcsv($fp, array_keys($res->getColumnNames()));
		//fputcsv($fp, array_walk(array_keys($res->getColumnNames()), 'strtoupper'));
		
		while ($row=$res->fetchRow(MDB2_FETCHMODE_ASSOC)) {
				fputcsv($fp, array_values($row));
		}
	}
}
else {
	header("Location: login.php");
}
?>
