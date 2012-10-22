<?php
header ("content-type: text/html; charset=utf-8");

$m = new Memcached();

$m->addServer('localhost', 11211);

//  $m->getDelayed(array('keyword', 'include', 'exclude'), true);
$m->getDelayed(array('Ã÷ÐÇ'), true);

echo "<pre>";
print_r($m->fetchAll());
echo "</pre>";
?>
