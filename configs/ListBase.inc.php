<?php
header('Content-Type: text/html; charset=utf-8');

require_once("base.inc.php");

class ListBase extends BaseClass
{
  var $url, $data, $ini_array, $table_array, $coltypes_array, $html, $project, $userid, $username;
  function __construct()
  {
	global $config;
	parent::__construct();
	$this->url = $_SERVER['PHP_SELF'];
	//$this->url = $_SERVER['SCRIPT_FILENAME'];//绝对路径,不work.
	$this->data = array();
	$this->ini_array = array();
	$this->table_array = array();
	$this->coltypes_array = array();
	$this->html = '';
	$this->ary1 = array('users', 'sites', 'levels');
	$this->username = isset($_SESSION[PACKAGE]['username'])?$_SESSION[PACKAGE]['username']:'demo';
	$this->userid = isset($_SESSION[PACKAGE]['userid'])?$_SESSION[PACKAGE]['userid']:2;

	if(isset($_COOKIE[$this->project]['path']) && (!empty($_COOKIE[$this->project]['path'])))
		$config['path'] = SITEROOT.'themes/'.$_COOKIE[$this->project]['path'].'/';
	else 
		$config['path'] = SITEROOT.'themes/default/';
	$config['self'] = $this->self;
	$config['title'] = ' [ '. strtoupper($this->self) . ' ] ';
  }
  /**
   * http://www.php.net/manual/en/language.oop5.overloading.php
   */
  public function __set($name, $value) {
  	$this->data[$name] = $value;
  }
  public function __get($name) {
  	if(array_key_exists($name, $this->data)) {
		return $this->data[$name];
	}
	$trace = debug_backtrace();
	trigger_error('Undefined property via __get(): ' . $name . ' in ' . $trace[0]['file'] . ' on line ' . $trace[0]['line'], E_USER_NOTICE);
	return null;
  }
  public function __isset($name) {
  	return isset($this->data[$name]);
  }
  public function __unset($name) {
  	unset($this->data[$name]);
  }

  function get_total_rows($sql) {
	$res = $this->mdb2->query($sql);
	if (PEAR::isError($res)) {
		die($res->getMessage().'['.__LINE__.']:'.$sql);
	}
	return $res->fetchOne();
  }
  
  // get [section] from [configs/register_list.ini].
  function get_mappings($section=NULL, $map_file=NULL)
  {
  	if(! $section) $section = strtolower($this->self);
	if(! $map_file) $map_file = MAP_FILE;
	if(isset($this->map_file)) $map_file = $this->map_file;
	
  	if (count($this->ini_array)==0) {
		if (file_exists($map_file)) {
			$this->ini_array = parse_ini_file($map_file, true);
		}
		else {
			die('No MAPPINGS FILE: ['.$map_file.']: ' . __FILE__ .'->'. __LINE__);
		}
	}

	if($section) {
		if(array_key_exists($section, $this->ini_array))
			return $this->ini_array[$section];
		else {
			//echo "register_list.ini文件中没有入口项 ->" . $section;
			//$this->print_array($this->ini_array);
			return;
		}
	}
	else {
		die("Section not exists: [".$this->self.'], ['.$map_file.']: ' . __FILE__ .'->'. __LINE__);
	}
  }

  function get_table_info() {
  	if (count($this->table_array)>0)
		return $this->table_array;
	$this->table_array = $this->get_mappings($this->self.'_table_information');
	return $this->table_array;
  }
  
  function get_coltypes_info() {
  	if (count($this->coltypes_array)>0)
		return $this->coltypes_array;
	$this->coltypes_array =  $this->get_mappings($this->self.'_column_types');
	return $this->coltypes_array;
  }

  function get_header($section_info=NULL)
  {
	// 'new_key'=>'new key': replace '_' with ' '.
    $nk = ''; $th_ary = array();
    foreach($section_info as $k => $v) {
      $nk = strtr($k, '_', ' ');
      if (preg_match("/,/", $v))
        $th_ary[$nk] = preg_replace("/,.*$/", '', $v);
      else
        $th_ary[$nk] = trim($v);
    }
    return $th_ary;
  }
  
  /**
   * $_SESSION[$this->session['magic_sql']] = $this->get_magic_sql($section_info);
   * select concat(address,',',city,',',province,',',postalcode) as Address
   */
  function get_magic_sql($section_info)
  {
  	$sql = ''; $a = array();
	foreach($section_info as $v) {
		if (preg_match("/,/", $v)) {
			$a = explode(",", $v);
			$m = $a[0];
			$sql .= 'concat(';
			foreach($a as $t) $sql .= $t.", ',', ";
			$sql .= ") as $m, ";
		}
		else $sql .= $v . ', ';
	}
	if (preg_match("/,\s*$/", $sql)) $sql = preg_replace('/,\s*$/', '', $sql);
	if (preg_match("/,\s*\)/", $sql)) $sql = preg_replace("/,\s*\)/", ')', $sql);
	
	$query = "SELECT " . $sql . " FROM " . $this->table_array['table_name'];
	return $query;
  }
  function get_magic_sql_1($section_info)
  {
	$nk=''; $sql=''; $ary=array();
	foreach($section_info as $k => $v) {
		$nk = strtr($k, '_', ' ');
		if (preg_match("/,/", $v))
			$ary[$nk] = explode(",", $v);
		else
			$ary[$nk] = $v;
	}
	return $ary;
  }
  
  function get_config($section_info) 
  {
	$nk=''; $sql=''; $ary=array();
	foreach($section_info as $k => $v) {
		$nk = strtr($k, '_', ' ');
		if (preg_match("/,/", $v))
			$ary[$nk] = explode(",", $v);
		else
			$ary[$nk] = $v;
		$sql .= $v . ', ';
	}

	if($this->self=='resources') {
		$query = "SELECT * FROM resources r WHERE 1=1 ORDER BY rid DESC";
	}
	else {
		if (preg_match("/,\s*$/", $sql)) $sql = preg_replace('/,\s*$/', '', $sql);
	
		$query = "SELECT " . $sql . " FROM " . $this->table_array['table_name'];
	}

	if(in_array($this->self, $this->ary1)) {
		if (isset($userlevel)) $query .= $userlevel;
	}
	elseif ($this->self=='pages') {
                $al = $this->get_sites_by_level($_SESSION[PACKAGE]['userlevel']);
		if(count($al)>0) {
			$usersites = " WHERE site_id IN (" . implode(',',$al) . ")";
			$query .= $usersites;
		}
	}
  	return array($query, $ary);
  }

  function list_all()
  {
	if (isset($_SESSION[$this->self][$this->session['sql']]) && !empty($_SESSION[$this->self][$this->session['sql']])) {
		$query = $_SESSION[$this->self][$this->session['sql']];
		$ary = $this->get_magic_sql_1($this->get_mappings());
	}
	else {
		$section_info = $this->get_mappings();
		list($query, $ary) = $this->get_config($section_info);
	}
	
	$page = isset($_GET['page']) ? $_GET['page'] : 1;
	$total_pages = ceil($_SESSION[$this->self][$this->session['rows']]/ROWS_PER_PAGE);
	if ( $page > $total_pages ) $page = $total_pages;
	$row_no = ((int)$page-1)*ROWS_PER_PAGE;
	
	if (preg_match("/limit /i", $query)) {
		$query = preg_replace("/limit.*$/i", '', $query);
	}
	
	if (! preg_match("/order by/i", $query)) {
		if(isset( $this->table_array['primary_key'])) {
			$primary_key = $this->table_array['primary_key'];
			$query .= " order by " . $primary_key . " desc "; // default is primary_key.
		}
	}

	if (isset($_GET['sort'])) {
		$new_order = $_GET['sort'];
		if (preg_match("/order by/i", $query)) {
			$query = preg_replace("/order by.*$/i", " order by " . $new_order, $query);
		}
		else {
			$query .= " order by " . $new_order;
		}
	}
	// 5. generate new $query, assign it to SESSION.
	$query .= " limit  " . $row_no . "," . ROWS_PER_PAGE;
  
	$_SESSION[$this->self][$this->session['sql']] = $query;
	
	$res = $this->mdb2->query($query);
	if (PEAR::isError($this->mdb2)) die($this->mdb2->getMessage().' [line '.__LINE__.']: '. $query);     

	// echo __FILE__.'['.__LINE__.']'.$query."<br>\n";

	$h = $res->getColumnNames();
	if (PEAR::isError($h)) {
		die($h->getMessage().' [line '.__LINE__.']: '. $query);
	}
	
	$data = array();
	$count = 0;

	while ($row=$res->fetchRow(MDB2_FETCHMODE_ASSOC)) {
		$data[$count]['no'] = ++ $row_no;
		foreach($ary as $k=>$v) {
			if(is_array($v)) {
				$tt='';
				foreach($v as $t) {
					$t = trim($t); 
					if(array_key_exists($t, $h)) {
						$tt .= $row["$t"] . ', ';
					}
				}
				$data[$count][$k] = $this->format($tt);
			}
			else {
				if(preg_match("/id/i", $k)) $data[$count][$k] = $row[$v];
				else {
					if($k=='Weight') $data[$count][$k] = $row[$v];
					elseif($k=='Types') $data[$count][$k] = $this->get_ftype($row[$v]); //for $this->self='resources'.
					else $data[$count][$k] = $row[strtolower($v)]; //htmlentities($row[strtolower($v)]);
				}
			}
		}
		$count ++;
	}
	$output = array();

	$output['self'] = $this->self;
	$output['col_types'] = $this->get_coltypes_info();
	if(isset($this->table_array['pkey'])) $output['pkey'] = $this->table_array['pkey'];
	$output['current_page'] = $page;
	$output['total_pages'] = $total_pages;
	$output['total_rows'] = $_SESSION[$this->self][$this->session['rows']];
	$output['list'] = $data;
	if(isset($_GET['sort'])) $output['sort'] =  $_GET['sort'];

	return $output;
  }

  // setlocale(LC_MONETARY, 'en_US'); echo money_format('%i', $number) . "\n";
  function format($str, $flag=true)
  {
	//if (preg_match("/(\s|,|\t)/", $str)) return 'N/A'; //only include 'space' or ','.
	if (empty($str) || preg_match("/^\s+$/",$str)) return 'N / A'; //only has 'space'.
	$s = '';

	// does str_replace() is quicker than preg_replace() ?
	if(preg_match("/\s+00:00:00/", $str)) {
		// $s = preg_replace("/\s+00:00:00/", '', $str);
		$s = trim(str_replace("00:00:00", '', $str));
	}
	else if (preg_match("/\w/", $str)) {
		$s = htmlspecialchars(ucwords(strtolower($str)));
		if(preg_match("/,\s*$/", $s)) $s = preg_replace("/,\s*$/", '', $s);
	}
	return $s;
  }

	function draw($current_page, $total_pages) 
	{
		$plinks = array(); $links = array(); $slinks = array(); $queryURL = '';

		if (count($_GET)) {
			foreach ($_GET as $key => $value) {
				if ($key != 'page') $queryURL .= '&'.$key.'='.$value;
			}
		}
		if (($total_pages) > 1) {
			if ($current_page != 1) {
				$plinks[] = ' <a href="?page=1'.$queryURL.'">&laquo;&laquo; First </a> ';
				$plinks[] = ' <a href="?page='.($current_page - 1).$queryURL.'">&laquo; Prev</a> ';
			}
			// Assign all the page numbers & links to the array
			for ($j = ($current_page-5); $j < ($current_page+5); $j++) {
			  if($j<1) continue;
			  if($j>$total_pages) break;
			  if ($current_page == $j) {
				$links[] = ' <a class="selected">'.$j.'</a> '; // If we are on the same page as the current item
			  } else {
				$links[] = ' <a href="?page='.$j.$queryURL.'">'.$j.'</a> '; // add the link to the array
			  }
			}
			// Assign the 'next page' if we are not on the last page
			if ($current_page < $total_pages) {
				$slinks[] = ' <a href="?page='.($current_page + 1).$queryURL.'"> Next &raquo; </a> ';
				$slinks[] = ' <a href="?page='.($total_pages).$queryURL.'"> Last &raquo;&raquo; </a> ';
			}
	        return implode(' ', $plinks).implode(' ', $links).implode(' ', $slinks);
		}
	}

  function get_html_template($assign=NULL)
  {
  	global $config;
  	if($assign) $this->html = $config['path'].'/templates/'.$assign;
  	elseif(! $this->html) $this->html = $config['path'].'/templates/'.DEFAULT_LIST;
	return $this->html;
  }
  
  function get_count_sql() {
  	return "SELECT count(*) FROM " . $this->table_array['table_name'];

	$res = $this->mdb2->query($sql);
	if (PEAR::isError($res)) {
		die($res->getMessage());
	}
	return $res->fetchOne();

  }

  function array_unique_key($array) {
    $result = array();
    foreach (array_unique(array_keys($array)) as $tvalue) {
        $result[$tvalue] = $array[$tvalue];
    }
    return $result;
  }

  ///////////////////////////////////////////////
  function get_select_options($sql, $line_flag=TRUE)
  {
  	if(!isset($sql) || empty($sql)) return;
	$res = $this->mdb2->query($sql);

	if (PEAR::isError($res)) {
		$this->print_array($res->getMessage().'file['. __FILE__ .'], line['.__LINE__.']: '.$sql);
	}

	if($line_flag) echo "\t<option value=''> --- 请选择 --- </option>\n";

	while ($row=$res->fetchRow()) {
		echo "\t".'<option value="'.$row[0].'" title="'.(isset($row[2])?htmlspecialchars($row[2]):$row[1]).'">'.$this->format_option($row)."</option>\n";
	}
  }

  function format_option($row) {
  	if(isset($row[3]) && isset($row[4]))
		$str = sprintf("%-35s - [%s] - [%s]", $row[1], $row[3], $row[4]);
	elseif(isset($row[3]))
		$str = sprintf("%-35s - [%s]", $row[1], $row[3]);
	else
		$str = sprintf("%s", $row[1]);
	//return $this->escape($str);
	return str_replace(' ', '&nbsp;', $str);
  }

  function sprintf_nbsp() {
   $args = func_get_args();
   return str_replace(' ', '&nbsp;', vsprintf(array_shift($args), array_values($args)));
  }
  
  function get_sites_options()
  {
	$sql = "SELECT site_id, name, description FROM sites where active='Y' ORDER BY name";
	return 	$this->get_select_options($sql);
  }
  function get_pages_options($site_id=1)
  {
	$sql = "SELECT pid, name, description, (SELECT name FROM sites s WHERE s.site_id=p.site_id) AS sname FROM pages p WHERE active='Y' and site_id=".$site_id." ORDER BY name";
	return 	$this->get_select_options($sql);
  }
  function get_modules_options($site_id=1)
  {
	$sql = "SELECT m.mid, m.name, m.description, p.name FROM modules m 
		LEFT JOIN pages_modules pm ON (pm.mid = m.mid)
		LEFT JOIN pages p ON (p.pid = pm.pid) 
		WHERE m.active='Y' and m.site_id=".$site_id." ORDER BY m.name";
	return 	$this->get_select_options($sql);
  }


  ///////////////////////////////////////////////
  function get_select_array($sql) {
  	if(!isset($sql) || empty($sql)) return;
	$res = $this->mdb2->queryAll($sql);  //while($row=$res->fetchRow()) $ary[$row[0]]=$row[1];
	if (PEAR::isError($res)) die($res->getMessage());
	return $res;
  }
  function get_sites_array() {
	$sql = "SELECT site_id, name, description FROM sites where active='Y' ORDER BY name";
	return 	$this->get_select_array($sql);
  }
  function get_pages_array($site_id=1) {
	$sql = "SELECT pid, name, description FROM pages WHERE active='Y' and site_id=".$site_id." ORDER BY name";
	return 	$this->get_select_array($sql);
  }
  function get_modules_array($site_id=1) {
	$sql = "SELECT mid, name, description FROM modules WHERE active='Y' and site_id=".$site_id." ORDER BY name";
	return 	$this->get_select_array($sql);
  }
  function get_modules_sites_array() {
	$sql = "SELECT mid, concat(m.name,' - [',s.name,']') as name, m.description FROM modules m inner join sites s where m.site_id=s.site_id and m.active='Y' ORDER BY m.name";
	return 	$this->get_select_array($sql);
  }

  function get_contents_array() {
	$sql = "SELECT cid, concat(title,' - [',category,'] - [',item,']') as name FROM contents ORDER BY title";
	return 	$this->get_select_array($sql);
  }


/////////////////////////

  function get_keyword_options() {
	$sql = "SELECT kid, name, description FROM " . $this->table . " where active='Y' ORDER BY name";
	return 	$this->get_select_options($sql);
  }
  function get_group_options() {
?>
<option value=0> --- 请选择 --- </option>
<option value="1" title="组别1">组别 1</option>
<option value="2" title="组别1">组别 2</option>
<option value="3" title="组别1">组别 3</option>
<option value="4" title="组别1">组别 4</option>
<option value="5" title="组别1">组别 5</option>
<option value="6" title="组别1">组别 6</option>
<?php
  }
  function get_frequency_options() {
	$sql = "SELECT distinct frequency FROM " . $this->table . " ORDER BY frequency";
	$res = $this->mdb2->query($sql);
	echo "\t<option value=''> --- 请选择 --- </option>\n";
	while ($row=$res->fetchRow()) {
		echo "\t".'<option value="'.$row[0].'" title="'.$row[0].'">'.htmlspecialchars($row[0])."</option>\n";
	}
  }
  function get_tag_options() {
	$sql = "SELECT distinct tag FROM " . $this->table . " ORDER BY tag";
	$res = $this->mdb2->query($sql);
	echo "\t<option value=''> --- 请选择 --- </option>\n";
	while ($row=$res->fetchRow()) {
		echo "\t".'<option value="'.$row[0].'" title="'.$row[0].'">'.htmlspecialchars($row[0])."</option>\n";
	}
  }

//////////////////////////
  function select_assigned_modules($pid, $site_id)
  {
  	
	$sqlm = "SELECT m.mid, m.name, m.description, p.name FROM modules m INNER JOIN pages_modules pm INNER JOIN pages p ON (p.pid=pm.pid AND pm.mid=m.mid) WHERE pm.mid = m.mid  AND m.site_id=".$site_id." AND m.active='Y' AND p.pid=".$pid;
	if($pid) $sqlm .= "	AND pm.pid=".$pid;
	$sqlm .= " ORDER BY m.name ";
	return 	$this->get_select_options($sqlm, false);
  }  
  function select_available_modules($pid, $site_id)
  {
	$sqlm = "SELECT m.mid, m.name, m.description, p.name FROM modules m 
		LEFT JOIN pages_modules pm	ON (pm.mid = m.mid)
		LEFT JOIN pages p ON (p.pid = pm.pid) 
		WHERE m.active='Y' AND m.mid NOT IN (SELECT mid from pages_modules WHERE pid=".$pid.") AND m.site_id=".$site_id." ORDER BY m.name";
	return 	$this->get_select_options($sqlm, false);
  }

  function update_action($keyword, $query)
  {
  	$ary = array('reports', 'tracks', 'wordpress');
  	if (in_array($this->self, $ary)) return;
	$browser = $this->get_browser_name();
	$ip = $this->get_ip();
	$userid =  isset($this->userid) ? $this->userid : 1;
	$username =  isset($this->username) ? $this->username : 'Unknown';
	$file = $this->self;
	$datetime = date("D M d H:i:s, Y");
	
	$action = '['.$datetime.'] ['.$file.'] ['.$username.'] ['.$ip.'] ['.$browser."]: $query";
    $sql = "INSERT INTO actions(uid,username,keyword,action,date) VALUES(".$userid.", '".$username."', '".$keyword."', '".$this->mdb2->escape($action)."', NOW())";
	$affected = $this->mdb2->exec($sql);
	if (PEAR::isError($affected)) {
		echo $affected->getMessage() . __FILE__ . __LINE__. ','.$sql;
	}
  }

  function get_level() {
	$sql = "SELECT level, name FROM levels ORDER BY name";
	$result = $this->mdb2->query($sql);
	echo "\t<option value=''> --- Select --- </option>\n";
	while ($row=$result->fetchRow()) {
		echo "\t<option value=\"" . $row[0] . "\">$row[1]</option>\n";
	}
  }
  function get_sites_by_level($level) {
	$sql = "SELECT site_id FROM sites WHERE level = " . $level;
	$result = $this->mdb2->query($sql);
if ($this->self=='track') {echo "<pre>"; print_r($this->mdb2); echo $sql; echo "</pre>";}
	$ary = array();
	while ($row=$result->fetchRow()) {
		$ary[] = $row[0];
	}
	return $ary;
  }

  function get_sname_from_sid($site_id)
  {
  	$sql = "SELECT name FROM sites WHERE site_id=".$site_id;
	$sname = $this->mdb2->queryOne($sql);
	return $sname;
  }
  function get_mname_from_mid($mid)
  {
  	$sql = "SELECT name FROM modules WHERE mid=".$mid;
	$mname = $this->mdb2->queryOne($sql);
	return $mname;
  }
}
?>
