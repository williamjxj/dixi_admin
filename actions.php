<?php
session_start();
error_reporting(E_ALL);
define ('SITEROOT', './');

ini_set('include_path',ini_get('include_path').PATH_SEPARATOR.SITEROOT.'configs/'.PATH_SEPARATOR.SITEROOT.'include/');
require_once("setting.inc.php");
require_once("config.inc.php");
require_once("ListAdvanced.inc.php");
global $config;


class ActionsClass extends ListAdvanced
{
  var $url, $self, $session;
  public function __construct() {
    parent::__construct();
  }

  function get_search_form_settings() {
	return array(
		array(
			'type' => 'text',
			'display_name' => 'Username:',
			'id' => 'username_s',
			'name' => 'username',
		),
		array(
			'type' => 'select',
			'display_name' => 'Keyword:',
			'id' => 'keyword_s',
			'name' => 'keyword',
			'call_func' => 'get_keyword_selection',			
		),
		array(
			'type' => 'text',
			'display_name' => 'Actions:',
			'id' => 'action_s',
			'name' => 'action',
		),
		array(
			'type' => 'date',
			'display_name' => 'Date:',
			'id' => 'date_s',
			'name' => 'date',
			'size' => INPUT_SIZE,
		),
	);
  }
  
  function get_keyword_selection() {
?>	
	<option value="delete">DELETE</option>
	<option value="insert">INSERT</option>
	<option value="search">SEARCH</option>
	<option value="update">UPDATE</option>
<?php
  }
  
}

// actions table has column 'action' which is conflicted with 'action' column, so put 'search' request ahead of 'action'.
$act = new ActionsClass() or die("Can't generate the instance.");
if(! $act->check_access()) {
  $act->set_breakpoint();
  echo "<script>if(window.opener){window.opener.location.href='".LOGIN."';} else{window.parent.location.href='".LOGIN."';}</script>";exit;
}
$act->get_table_info();

if(isset($_GET['js_search_form'])) {
	$ary = $act->get_search_form_settings();
	$act->assign('search_form', $ary);	
	$act->assign('config', $config);
	$act->display($config['templs']['search']);
}
else if( isset($_POST['search']) || (isset($_GET['page']) && isset($_GET['sort'])) || isset($_GET['page']) ) {
	if(isset($_POST['search'])) $act->parse();

	$data = $act->list_all();
	$data['options'] = array(DELETE);
	
	$header = $act->get_header($act->get_mappings());
	$pagination = $act->draw( $data['current_page'], $data['total_pages'] );
	
	$act->assign('config', $config);
	$act->assign('header', $header);
	$act->assign('data', $data);
	$act->assign("pagination", $pagination);
	$tpl = $act->get_html_template();
	$act->display($tpl); // not use display, should use AJAX.
}
elseif(isset($_REQUEST['action'])) {
	switch($_REQUEST['action']) {
		case 'delete':
			$act->delete($_GET['id']);
			break;
		default:
			break;
	}
}
else {
	if (isset($_SESSION[$act->self][$act->session['sql']])) $_SESSION[$act->self][$act->session['sql']] = '';

	$total_rows = $act->get_total_rows($act->get_count_sql());

	$_SESSION[$act->self][$act->session['rows']] = $total_rows < 1 ? 1 : $total_rows;
	
	$data = $act->list_all();
	$data['options'] = array(DELETE);
	
	$header = $act->get_header($act->get_mappings());
	$pagination = $act->draw( $data['current_page'], $data['total_pages'] );

	$act->assign('config', $config);
	$act->assign('header', $header);
	$act->assign('data', $data);
	$act->assign("pagination", $pagination);
	$tpl = $act->get_html_template();
	$act->assign("template", $tpl);
	
	$act->display($config['templs']['main']);
}
?>