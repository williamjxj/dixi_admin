<?php
define('SITEROOT', '../');

if(isset($_POST['keyword'])) {
	if(! empty($_POST['keyword'])) {
		$search_dirs = array('./');
		$search_suffix = "txt|ini|inc|conf|sql|html|htm";
		search_dir($search_dirs, $search_suffix);
	}
}
else get_search_form();
exit;

//////////////////////////////////
function search_dir($search_dirs, $search_suffix)
{
  $keyword = $_POST['keyword'];
  $skips = array('.', '..');
  foreach($search_dirs as $dir) {
	$handle = opendir($dir);
	echo "<dl>\n";
	while($file = readdir($handle)) {
		if(in_array($file, $skips)) continue;
		elseif(is_dir($dir."/".$file)) {
			echo "sub directory<br>\n";
			continue;
		}
		elseif(preg_match("/($search_suffix)$/i", $file)) { 
			$matches = array();
			$data = file_get_contents($dir."/".$file);
			if (preg_match_all("/((\s\S*){0,3})($keyword)((\s?\S*){0,3})/i",$data, $matches, PREG_SET_ORDER) ) {
				$num = count($matches);
				echo '<dt><a href="'.$dir.$file.'" target="_block" class="keyword">'.htmlspecialchars($file).'('.$num.")</a>&nbsp;&nbsp;(<a href='javascript:void(0);'>+</a>)</dt>\n<span style='display:none;'>\n";
				for ($i=0; $i<count($matches); $i++) {
					if(!empty($matches[$i][3]))
					printf("<dd><em>%s</em>, <b><font color='red'>%s</font></b>, <i>%s</i></dd>\n", $matches[$i][1], $matches[$i][3], $matches[$i][4]);
				}
				echo "</span>\n";
			}
			flush();
		}
	}
	echo "</dl>\n";
	closedir($handle);
?>
<script language="javascript" type="text/javascript">
$("dt a[href='javascript:void(0);']").each(function(index) {
	$(this).click(function(e){
		e.preventDefault();
		// alert($(this).attr('href') + ', '+ e.target.nodeName+','+$(this).html()); //A
		$(this).parent().next('span').toggle();
		if($(this).text()=='+') $(this).text('-');
		else $(this).text('+');
		return false;
	});
});
if(! $('#show').length) {
$show=$('<a></a>').attr({id:'show',href:'javascript:void(0);'}).html('<strong>Show All Details</strong>').click(function() {
	$('dl').find('span').show().end().find('a[href="javascript:void(0);"]').text('-');
});
$hide=$('<a></a>').attr({id:'hide',href:'javascript:void(0);'}).html('<strong>Hide All Details</strong>').click(function() {
	$('dl').find('span').hide().end().find('a[href="javascript:void(0);"]').text('+');
});
$('<div></div>').insertBefore($('#div_list')).append($show).append($('<span>&nbsp;&nbsp;&nbsp;&nbsp;</span>')).append($hide);
}
</script>
<?php
  }
}

function get_search_form() {
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>底细-查询开发过程技术细节</title>
<link rel="stylesheet" href="<?=SITEROOT;?>include/jqtransform/jqtransformplugin/jqtransform.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?=SITEROOT;?>include/jqtransform/demo.css" type="text/css" media="all" />
<script language="javascript" type="text/javascript" src="<?=SITEROOT;?>include/js/jquery-1.7.2.min.js"></script>
<script language="javascript" type="text/javascript" src="<?=SITEROOT;?>include/jqtransform/jqtransformplugin/jquery.jqtransform.js"></script>
<script language="javascript" type="text/javascript">
$(function(){
    $("#search_form.jqtransform").jqTransform({imgPath:'../include/jqtransform/jqtransformplugin/img/'});
	var form = '#search_form';
	$(form).submit(function(e) {
		e.preventDefault();
		if($('#keyword').val()) {
			$(form+' legend span').html(function() {
				var t = ' - <strong>[ ' + $('#keyword').val() + ' ]</strong> ';
				return t;
			});
		}
        $.ajax( {
            type: $(form).attr('method'),
            url: $(form).attr('action'),
            data: $(form).serialize(),
			beforeSend: function() {
				$('#submit').hide();
				$('#msg').show();
			},
            success: function(data) {
				$('#submit').show();
				$('#msg').hide();
				if(data)  $('#div_list').html(data);
				else $('#div_list').html('<b>No file matched.</b>');
				$('#keyword').focus();
            }
        });
		return false;
	});
	$('#keyword').click(function() {
		$(this).select();
	}).focus();
});
</script>
</head>
<body>
<div id="div_search">
  <form id="search_form" name="search_form" action="<?=$_SERVER['PHP_SELF'];?>" method="post" class="jqtransform">
    <fieldset>
    <legend>Search Notes<span></span></legend>
    <input type="text" id="keyword" name="keyword" />
    <input type="submit" id="submit" name="submit" value="Search!" /> <span>e.g: william, jquery, php</span>
    <span id="msg" name="msg" style="display: none"><img src="<?=SITEROOT;?>themes/default/images/spinner.gif" width="16" height="16" border="0"></span>
    </fieldset>
  </form>
</div>
<div id="div_list"></div>
</body>
</html>
<?php
}
?>
