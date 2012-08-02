<?php
defined('PACKAGE') or define('PACKAGE', 'dixitruth_admin');
defined('SITEROOT') or define('SITEROOT', '/');
defined('DEBUG') or define('DEBUG', true);

//
define("LOGIN", SITEROOT."index.php"); // entry point.
define('ADMIN_USER', 'admin_users');

// DB
define("DBHOST", "localhost");
define('DBUSER', 'dixitruth');
define("DBPASS", "dixi123456");
define('DBNAME', 'dixi');

// default map file (Display Name <=> DB table columns), used to list all optional columns in 'R'(CRUD).
define("MAP_FILE", SITEROOT."configs/register_list.ini");
define('FLOG', SITEROOT.'logs/admin.log');

// entry points.
define("DEFAULT_TEMPLATE", "main.tpl.html");
define('DEFAULT_LIST', 'list.tpl.html');
define("DEFAULT_ASSIGN_TEMPLATE", 'assign.tpl.html');
    
define("PAGINATION", 'div_list');
define("ROWS_PER_PAGE", 30);
define("DCN", 2);  // for search & edit form: default column number
define("INPUT_SIZE", 28);

define('BREAKPOINT', SITEROOT.'.breakpoint.txt');
define ('PREVIEW', SITEROOT.'default/front/preview/');

//tinymce
defined('ALLOWTAGS') or define('ALLOWTAGS', '<a><p><strong><em><u><h1><h2><h3><h4><h5><h6><img><li><ol><ul><span><div><br><ins><del><table><tr><td><tbody><thead><tfoot><b><cufon><canvas><cufontext><object><param><embed><iframe><script><dl><dt><dd><blockquote><colgroup><col><hr>');

//多国语言定义:
define('EDIT', 'edit');
define('EMAIL', 'email');
define('VIEW', 'view');
define('PENSION', 'pension');
define('DELETE', 'delete');
define('RESOURCE', 'resources');
define('CONTENT', 'contents');
?>