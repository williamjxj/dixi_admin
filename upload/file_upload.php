<style type="text/css">
body {
	padding: 30px
}
form {
	display: block;
	margin: 20px auto;
	background: #eee;
	border-radius: 10px;
	padding: 15px
}
.progress {
	position:relative;
	width:400px;
	border: 1px solid #ddd;
	padding: 1px;
	border-radius: 3px;
}
.bar {
	background-color: #B4F5B4;
	width:0%;
	height:20px;
	border-radius: 3px;
}
.percent {
	position:absolute;
	display:inline-block;
	top:3px;
	left:48%;
}
</style>
<form action="process-cn.php" method="post" enctype="multipart/form-data">
  <input type="file" name="keywords_file">
  <br>
  <input type="submit" value="关键词文件上载"> （每个关键词一行）
</form>
<div class="progress">
  <div class="bar"></div >
  <div class="percent">0%</div >
</div>
<div id="status"></div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script>
<script type="text/javascript" src="http://malsup.github.com/jquery.form.js"></script>
<script>
(function() {
	var bar = $('.bar');
	var percent = $('.percent');
	var status = $('#status');
	   
	$('form').ajaxForm({
		beforeSend: function() {
			status.empty();
			var percentVal = '0%';
			bar.width(percentVal)
			percent.html(percentVal);
		},
		uploadProgress: function(event, position, total, percentComplete) {
			var percentVal = percentComplete + '%';
			bar.width(percentVal)
			percent.html(percentVal);
		},
		complete: function(xhr) {
			status.html(xhr.responseText);
		}
	});
})();       
</script>