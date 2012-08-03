<?php /* Smarty version Smarty-3.0.4, created on 2012-08-03 13:53:26
         compiled from "./themes/default/templates/left.tpl.html" */ ?>
<?php /*%%SmartyHeaderCode:3030501c3a465a6220-86547891%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e2e0f32a2bfd875856705e73b26897d2ccbf8475' => 
    array (
      0 => './themes/default/templates/left.tpl.html',
      1 => 1344026105,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3030501c3a465a6220-86547891',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base target="mainFrame">
<link href="<?php echo (isset($_smarty_tpl->getVariable('config')->value['path']) ? $_smarty_tpl->getVariable('config')->value['path'] : null);?>
css/pams-style.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo (isset($_smarty_tpl->getVariable('config')->value['path']) ? $_smarty_tpl->getVariable('config')->value['path'] : null);?>
js/jquery-1.5.1.min.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo (isset($_smarty_tpl->getVariable('config')->value['path']) ? $_smarty_tpl->getVariable('config')->value['path'] : null);?>
js/pams-js.js"></script>
<script language="javascript" type="text/javascript">
$(document).ready(function() {
 jQuery.set_path = function(str) {
   var t=$('h1', window.parent.frames[0].document.body);
   var path="<?php echo (($tmp = @(isset($_smarty_tpl->getVariable('config')->value['header']['title']) ? $_smarty_tpl->getVariable('config')->value['header']['title'] : null))===null||$tmp==='' ? '基督教素里华人宣道会一家团契' : $tmp);?>
 - " + str;
   t.html(path);
 }
 $.set_path('Contents');
 $('ul.navList li:eq(0)').addClass('active');
 $('ul.navList li a').click(function(){
	$.set_path($(this).text());
 	$('ul.navList li').removeAttr('class');
	$(this).parent().addClass('active');
 });
});
</script>
</head>
<body>
<div class="sidebarBox" id="left_<?php echo (isset($_smarty_tpl->getVariable('config')->value['browser_id']) ? $_smarty_tpl->getVariable('config')->value['browser_id'] : null);?>
"> <br />
  <div class="sidebar-box">
    <div class="side-head"> 内容管理界面 </div>
    <div class="inner noPadding">
      <div class="navTitle nonActiveTitle">
        <p>内容部分</p>
      </div>
      <ul class="navList">
        <?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = (isset($_smarty_tpl->getVariable('config')->value['cores']) ? $_smarty_tpl->getVariable('config')->value['cores'] : null); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value']->key;
?>
        <li><a href="<?php echo (isset($_smarty_tpl->tpl_vars['value']->value) ? $_smarty_tpl->tpl_vars['value']->value : null);?>
"><?php echo (isset($_smarty_tpl->tpl_vars['key']->value) ? $_smarty_tpl->tpl_vars['key']->value : null);?>
</a></li>
        <?php }} ?>
      </ul>
      <div class="navTitle nonActiveTitle">
        <p>管理部分</p>
      </div>
      <ul class="navList">
        <?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = (isset($_smarty_tpl->getVariable('config')->value['manages']) ? $_smarty_tpl->getVariable('config')->value['manages'] : null); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value']->key;
?>
        <li><a href="<?php echo (isset($_smarty_tpl->tpl_vars['value']->value) ? $_smarty_tpl->tpl_vars['value']->value : null);?>
"><?php echo (isset($_smarty_tpl->tpl_vars['key']->value) ? $_smarty_tpl->tpl_vars['key']->value : null);?>
</a></li>
        <?php }} ?>
      </ul>
      <div class="navTitle nonActiveTitle">
        <p>辅助工具</p>
      </div>
      <ul class="navList">
        <?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = (isset($_smarty_tpl->getVariable('config')->value['tools']) ? $_smarty_tpl->getVariable('config')->value['tools'] : null); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if ($_smarty_tpl->_count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value']->key;
?>
        <li><a href="<?php echo (isset($_smarty_tpl->tpl_vars['value']->value) ? $_smarty_tpl->tpl_vars['value']->value : null);?>
"><?php echo (isset($_smarty_tpl->tpl_vars['key']->value) ? $_smarty_tpl->tpl_vars['key']->value : null);?>
</a></li>
        <?php }} ?>
      </ul>
    </div>
  </div>
</div>
</body>
</html>
