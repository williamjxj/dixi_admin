<?php
require_once("ListBase.inc.php");

class ListAdvanced extends ListBase
{
  function __construct() {
    parent::__construct();
	/** smarty registerObject must register for all.
	 * So I don't need to register 1 by 1 for all apps with 'search', 'edit' and 'add'.
	 */
	$this->registerObject('obj',$this,null,false);
  }

  function get_record($id, $hash_flag=TRUE, $all_flag=TRUE)
  {
	$type = isset($this->table_array['type']) ? $this->table_array['type'] : 'int';

	if($all_flag) $query = "SELECT * FROM " .$this->table_array['table_name'];
	else $query = $this->get_magic_sql($this->get_mappings());
	
	// can't use select * from table.
	$sql = $query." where ".$this->table_array['primary_key'];
	
	if(strcmp($type, 'string')==0) $sql .= " = '".$id ."'";	
	else $sql .= " = ".$id;
	$result = $this->mdb2->query($sql);
	if($hash_flag)
		$row = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
	else 
		$row = $result->fetchRow();
	
	return $row;
  }
  
  function parse()
  {
	$where = '';
	$search_ary = $this->get_search_form_settings();

    foreach($_POST as $key=>$value) {
      if (empty($value) || preg_match("/^\s+$/", $value)) continue;
	  $value = trim($value); //clean.
	  if (preg_match("/search/i", $key)) continue;
	  if (preg_match("/YYYY-MM-DD/i", $value)) continue;

		foreach($search_ary as $item) {
			if($item['name'] == $key) {
				$type = isset($item['db_type']) ? $item['db_type'] : $item['type'];
				switch($type) {
					case 'int': // float is what type???
					case 'float':
				        $where .= " and ".$key.' = ' . $value;   
						break;
					case 'date':
					  	//$where .= " and str_to_date('".$key."', '%Y-%m-%d')='".$value."' ";
					  	$where .= " and ".$key." like '".$value."%' ";
						break;
					case 'radio':
						if($item['ignore'] == $value) continue;
						$where .= " and ".$key." = '".$value."' ";
						break;
					case 'checkbox':
					  	$where .= " and ".$key." = '".$value."' ";
						break;
					case 'select':
						$v = preg_replace("/.*\//", '', $value);
					  	$where .= " and ".$key." like '%".$v."' ";
						break;
					case 'text':
						// if search string include '?, %', esacpe.
						//if (preg_match('/\%/', $value)) $value = str_replace('%', "\%", $value);
						//if (preg_match('/\?/', $value)) $value = str_replace('?', '\?', $value);
        				$where .= " and ".$key." like '%" . $value . "%' ";        
						break;
					default:
						break;
				}
			}
		}
    }
    $where = $this->replace_str_once(" and ", " where ", $where);
    $sql = $this->get_count_sql() . $where;
    $total_rows = $this->get_total_rows($sql);
    $_SESSION[$this->self][$this->session['rows']] = $total_rows < 1 ? 1 : $total_rows;
	
    list($query, $header) = $this->get_config($this->get_mappings());
	
	if ($this->self=='modules' || $this->self=='contents' || $this->self=='resources') {
		$query = $this->convert_sql($query.$where);
		$_SESSION[$this->self][$this->session['sql']] = $query;
	}
	else 
		$_SESSION[$this->self][$this->session['sql']] = $query . $where;
	
	$this->update_action('SEARCH', $_SESSION[$this->self][$this->session['sql']]);
  }
 
 
  function convert_sql($sql)
  {
	preg_match("/order by.*(WHERE.*)(limit.*$|$)/i", $sql, $matches);
	if(isset($matches[1]) && $matches[1]) {
		$prefix = ($this->self=='modules') ? 'm.' : (($this->self=='contents')? 'ct.' : (($this->self=='resources')?'r.':''));
		if (preg_match('/\?/', $matches[1])) $matches[1] = str_replace('?', '\?', $matches[1]);
		$sql = preg_replace("/$matches[1]/", "", $sql);
		$t = preg_replace("/WHERE/i", ' AND', $matches[1]);
		$t = preg_replace("/AND\s+/i", "AND ".$prefix, $t);
		$sql = preg_replace("/UNION/", $t." UNION", $sql);
		$sql = preg_replace("/ORDER BY/", $t. " ORDER BY", $sql);
	}
	return $sql;	
  }

  function edit_table($edit_form)
  {
	$id=''; $sql=''; $sql1='';
  if($this->table_array['primary_key']) $primary_key = $this->table_array['primary_key'];
  else return false;
  $primary_type = $this->table_array['type'];

	foreach($edit_form as $item) {
		$type = isset($item['db_type']) ? $item['db_type'] : $item['type'];
		switch($item['type']) {
      case 'hidden':
        $id = $_POST[$item['name']];
        if(isset($item['db_type']) && strcmp($item['db_type'], 'int')==0)
          $sql1 = " WHERE " . $primary_key . " = ". $id;          
        else
          $sql1 = " WHERE " . $primary_key . " = '". $id."'";
        break;
      case 'text':
        if($item['name'] == $primary_key) continue;
      case 'textarea':
      case 'radio':
      case 'checkbox':
      case 'select':
      default:
        if(isset($item['db_type']) && strcmp($item['db_type'], 'int')==0)
          $sql .= $item['name']." = ".trim($_POST[$item['name']]).", ";
        else
          $sql .= $item['name']." = '".$this->mdb2->escape(trim($_POST[$item['name']]))."', ";
				break;
		}
	}
  if(empty($id)) return "There is NO ID for this form."; //$this->error

	if(isset($_SESSION[PACKAGE]['username']) && $_SESSION[PACKAGE]['username']) {
		$updatedby = $_SESSION[PACKAGE]['username'];
		$sql .= "updatedby = '" .  $updatedby . "', ";
	}
	$sql = preg_replace("/,\s+$/", '', $sql);	

	$query="UPDATE " . $this->table_array['table_name'] . "  SET " . $sql . $sql1;

	//patch:
	if($this->self=='resources') {
		$query = preg_replace('/sname\s*=/', 'site_id=', $query);
		$query = preg_replace('/mname\s*=/', 'mid=', $query);
	}
	
	$affected = $this->mdb2->exec($query);
	if (PEAR::isError($affected)) {
		die($affected->getMessage().' [ line: '.__LINE__.'], '.$query);
	}

	$this->update_action('UPDATE', $query);
	
	if($this->self=='modules')
		$row = $this->get_record($id, FALSE, TRUE);
	else 
	  	$row = $this->get_record($id, FALSE, FALSE);
	return $row;
  }
  
/*$sql = "SELECT * from ".$this->table_array['table_name']." where ".$primary_key; if(strcmp($type, 'string')==0) $sql .= " = '".$id ."'";else $sql .= " = ".$id;*/
  function delete($id)
  {
    $primary_key = $this->table_array['primary_key'];
	$type = isset($this->table_array['type']) ? $this->table_array['type'] : 'int';

	$query = "DELETE FROM " . $this->table_array['table_name'] . " WHERE ".$primary_key;
	if(strcmp($type, 'string')==0) $query .= " = '".$id ."'";	
	else $query .= " = ".$id;

	$affected = $this->mdb2->exec($query);
	if (PEAR::isError($affected)) {
      die($affected->getMessage());
    }

	// update action tables.
	$this->update_action('DELETE', $query);
	
	return true;
  }

  function create($extra_ary=array())
  {    
    $sql1 = ''; $sql2='';
    $add_ary = $this->get_add_form_settings();

	$array = $this->array_unique_key($_POST);
	// echo "<pre>"; print_r($array); echo "</pre>";
    foreach($array as $key=>$value) {
      if ($key=="action" && $value=="add") continue;
      $value = trim($value); //clean.
      if (preg_match("/YYYY-MM-DD/i", $value)) $value='';

      foreach($add_ary as $item) {
	  	$pattern = "/,".$item['name'].",/"; //unique: password1,password with same name 'password'.
	  	if(preg_match($pattern, $sql1)) continue;
		
        if($item['name'] == $key) {
          $type = isset($item['db_type']) ? $item['db_type'] : $item['type'];
          switch($type) {
            case 'int': // float is what type???
            case 'float':
              $sql1 .= $key . ',';
              $sql2 .= $value . ',';
              break;
			case 'password':
            case 'text':
			case 'textarea':
              $sql1 .= $key . ',';
              $sql2 .= "'" . $this->mdb2->escape($value) . "',";
              break;
            case 'date':
            case 'radio':
            case 'checkbox':
            default: //file
              $sql1 .= $key . ',';
              $sql2 .= "'" . $value . "',";
              break;
          }
        }
      }
    }
	// patch for items:
	if ($this->self=='items' && isset($_POST['category']) && !empty($_POST['category'])) {
		$sql1 .= 'category,';
		$sql2 .= "'" . $_POST['category'] . "',";
	}

    if(is_array($extra_ary) && count($extra_ary)>0) {
      $str1='';$str2='';
      foreach($extra_ary as $k=>$v) {
        $str1 .= $k . ",";
		if(preg_match("/now()/i", $v))
	        $str2 .= ' ' . $v . ' ';
		else
			$str2 .= "'" . $v . "',"; // not ', ', it is ',', no space after comma.
      }
      $sql1 .= $str1;
      $sql2 .= $str2;
    }

    $sql1 = substr($sql1, 0, -1); //remove the last ','. 
    $sql2 = substr($sql2, 0, -1);      

    $query = "INSERT INTO ".$this->table_array['table_name']."(".$sql1.") VALUES(".$sql2.");";
	// echo $query;INSERT INTO key_search(keyword,include,exclude,createdby,created) VALUES('default','最新负面消息','积极正面','Adminadmin', NOW());

    $affected = $this->mdb2->exec($query);
    if (PEAR::isError($affected)) {
      die($affected->getMessage().' [ line '.__LINE__.' ], '.$query);
    }

	$last_id = $this->mdb2->lastInsertID();
	$this->update_action('INSERT', $query);
	return $last_id;
  }

  /** Every time Only 1 columns is updated.
   * Description: This is for CIBP health benefits management., id: 2, js_edit_column: 1
   */
  function update_column($extra_ary=array())
  {
	$primary_key = $this->table_array['primary_key'];
	$sql1 = " WHERE " . $primary_key . " = ". $_POST['id'];
	$sql = ''; $str = '';
	foreach($_POST as $k=>$v) {
		if(preg_match("/js_edit_column/", $k)) continue;
		if($k!='id') {
			$str = $this->mdb2->escape(trim($v));
			$sql = " $k='" . $str . "' ";
		}
	}
    if(is_array($extra_ary) && count($extra_ary)>0) {
      foreach($extra_ary as $k=>$v) {
        $sql .= ', ' . $k . "='" . $v . "' ";
      }
    }
	$query = "UPDATE " . $this->table_array['table_name'] . "  SET " . $sql . $sql1;
	
	echo __FILE__.'['.__LINE__.']'.$query."<br>\n";

	$affected = $this->mdb2->exec($query);
	if (PEAR::isError($affected)) {
		die($affected->getMessage().': ' . $query . ". line: " . __LINE__);
	}
	
	$this->update_action('UPDATE', $query);
	
	return $str;
  }

}
?>
