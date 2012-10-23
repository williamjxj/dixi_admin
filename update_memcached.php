<?php
define('SITEROOT', './');
require_once(SITEROOT.'configs/mini-app.inc.php');

$s = new SyncMemcached();
$s->sync();

class SyncMemcached
{
	function __construct() {
		$this->cache = this->connect_memcached();
		$this->mdb2 = pear_connect_admin();
	}
	function connect_memcached() {
	    $m = new Memcached();
    	$m->addServer('localhost', 11211);
		return $m;
	}
	function sync() {
		$sql = "select kid, keyword, include, exclude from key_search order by kid";
		$res = $this->mdb2->query($sql);
		if (PEAR::isError($res)) {
				echo __FILE__.'['.__LINE__.']'.$sql;
				die($res->getMessage());
		}
		$pattern = "/\s+/";
		while ($row=$res->fetchRow(MDB2_FETCHMODE_ASSOC)) {
			//将多个空格转换为一个。
            $t1 = trim($row['keyword']);
            $t2 = preg_replace($pattern, ' ', trim($row['include']));
            $t3 = preg_replace($pattern, ' ', trim($row['exclude']));
			//以空格为分隔符，生成数组。
            $include = explode(' ', $t2);
            $exclude = explode(' ', $t3);
			//将数组存入memcached中。
			$item = array($include,$exclude);
			$this->cache->set($t1, $item);
		}		
	}
}

class SyncMongoDB
{

}
?>
