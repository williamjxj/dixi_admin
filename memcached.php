<?php
header ("content-type: text/html; charset=utf-8");

$m = new Memcached();

$m->addServer('localhost', 11211);

$t = $t = iconv('UTF-8', 'UTF-8//TRANSLIT', 'Ã÷ÐÇ');
//  $m->getDelayed(array('keyword', 'include', 'exclude'), true);
$m->getDelayed(array($t), true);

echo "<pre>";
print_r($m->fetchAll());
echo "</pre>";
?>
