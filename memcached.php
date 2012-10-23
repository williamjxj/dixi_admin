<div style="margin:20px;padding:10px">
<form method="get" id="mem_form" action="<?=$_SERVER['PHP_SELF'];?>">
  <input type="text" name="key" />
  <input type="submit" name="submit" value="Submit" />
</form>
</div>
<script type="text/javascript">
$(function() {
	$('#mem_form').submit(function(e) {
		var data = $(this).serialize();
		$.post($(this).attr('action'), data, function(data) {
			$('#main3').html(data).css('marginLeft', '10px');
		});
		e.preventDefault();
		return false;
	});
});
</script>
<?php
if(!empty($_POST['key'])) {

	$m = new Memcached(); //memcached
	$m->addServer('localhost', 11211);

	$got = $m->get($_POST['key']); //utf8_encode();mb_detect_encoding();
	if (! $got) {
		if($m->getResultCode() == Memcached::RES_NOTFOUND) echo "NOT FOUND<br>\n";
		else echo "FOUND, but error[". $t . "]<br>\n";
	}
	else {
		$ary = array(
			'key' => implode(' ', $got['keyword']),
			'include' => implode(' ', $got['include']),
			'exclude' => implode(' ', $got['exclude']),
		);
		echo "<pre>"; print_r($ary); echo "</pre>";
	}
	echo "<hr>\n";
}
getMemcacheKeys(); // memcache

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
