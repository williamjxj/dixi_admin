<?php
header ("content-type: text/html; charset=utf-8");

//memcache
getMemcacheKeys();

//memcached
$m = new Memcached();
$m->addServer('localhost', 11211);

if (!($m->get('Ã÷ÐÇ'))) {
//if (!($m->get('key2'))) {
	if($m->getResultCode() == Memcached::RES_NOTFOUND) {
		echo '11111';
	}
	else {
		echo '2222';
	}
}
else {
		echo '3333';
}

return;

//$t = iconv('UTF-8', 'UTF-8//TRANSLIT', 'Ã÷ÐÇ');
//$m->getDelayed(array($t), true);
$m->getDelayed(array('Ã÷ÐÇ'), true);
echo "<pre>"; print_r($m->fetchAll()); echo "</pre>";

return;

$m->getDelayed(array('abc efg'), true);
echo "<pre>"; print_r($m->fetchAll()); echo "</pre>";

//$result = $m->getMulti(array('keyword', 'include', 'exclude'), $cas);
//echo "<pre>"; var_dump($result, $cas); echo "</pre>";



function getMemcacheKeys() {
	$memcache = new Memcache;
	$memcache->connect('127.0.0.1', 11211) 
	or die ("Could not connect to memcache server");

	$list = array();
	$allSlabs = $memcache->getExtendedStats('slabs');
	$items = $memcache->getExtendedStats('items');
	foreach($allSlabs as $server => $slabs) {
		foreach($slabs AS $slabId => $slabMeta) {
			$cdump = $memcache->getExtendedStats('cachedump',(int)$slabId);
			foreach($cdump AS $keys => $arrVal) {
				if (!is_array($arrVal)) continue;
				foreach($arrVal AS $k => $v) {                   
					echo $k ."<br/>\n";
					//echo $k .' - '.date('H:i d.m.Y',$v[1]).'<br />';
				}
			}
		}
	}
} 
?>
