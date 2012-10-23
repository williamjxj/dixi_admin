<?php
header("content-type: text/html; charset=utf-8");
require_once('../fmxw/scraper_search.php');
?>

<form method="get">
  <input type="text" name="key" />
  <input type="submit" name="submit" value="Submit" />
</form>
<?php
if(!empty($_GET['submit'])) {

	$m = new Memcached(); //memcached
	$m->addServer('localhost', 11211);

	$got = $m->get($_GET['key']); //utf8_encode();mb_detect_encoding();
	// echo "<pre>"; print_r($got); echo "</pre>";

	if (! $got) {
		if($m->getResultCode() == Memcached::RES_NOTFOUND) {
			echo "NOT FOUND<br>\n";
		}
		else {
			echo "FOUND, but error[". $t . "]<br>\n";
		}
	}
	else {
		$ary = array(
			'key' => implode(' ', $got['keyword']),
			'include' => implode(' ', $got['include']),
			'exclude' => implode(' ', $got['exclude']),
		);
		echo "<pre>"; print_r($ary); echo "</pre>";
	}
	backend_scrape(trim($_GET['key']));
}
else {
	getMemcacheKeys(); // memcache
}

return;

function getMemcacheKeys() {
	$memcache = new Memcache;
	$memcache->connect('localhost', 11211) 
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
