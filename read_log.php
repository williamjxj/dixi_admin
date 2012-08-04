<?php
// http://tekkie.flashbit.net/php/tail-functionality-in-php
// full path to text file
define("TEXT_FILE", './logs/admin.log');
// number of lines to read from the end of file
define("LINES_COUNT", 30);

if(isset($_GET['js_refresh'])) {
	get_output();
}
else init();

exit;
///////////////////////

function init() {
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>底细,真相 LOG FILE</title>
<script language="javascript" type="text/javascript" src="include/jquery-1.6.4.js"></script>
<script language="javascript" type="text/javascript" src="include/doTimeout.js"></script>
<script language="javascript" type="text/javascript">
$(document).ready(function() {
	$('#refresh').click(function() {
		$('#log').load('<?=$_SERVER['PHP_SELF'];?>?js_refresh=1').hide().fadeIn(100);
	});
	/*setTimeout( function() {
		$('#log').load('<?=$_SERVER['PHP_SELF'];?>?js_refresh=1').hide().fadeIn(100);
	}, 5000);	*/
	$.doTimeout(5000, function() {
		$('#log').load('<?=$_SERVER['PHP_SELF'];?>?js_refresh=1').hide().fadeIn(100);
		return true;	
	});
});
</script>
</head>
<body>
<input type="button" id="refresh" value="Refresh" />
<div>
<textarea id="log" style="height:600px; width:90%"><?php get_output(); ?></textarea>
</div>
</body>
</html>
<?
}

function get_env() {
	if(isset($_SERVER['SERVER_SOFTWARE'])) {
		if(preg_match('/Win32/i', $_SERVER['SERVER_SOFTWARE'])) return 'Windows';
		return 'Unix';
	}
}

function get_output()
{
	if(get_env()=='Windows') {
	  $fsize = round(filesize(TEXT_FILE)/1024/1024,2); //megabytes
	  $lines = read_file(TEXT_FILE, LINES_COUNT);
	  foreach ($lines as $line) {
		echo htmlspecialchars($line);
	  }
	}
	else {
		// Execute command via shell and return the complete output as a string.
		$cmd = 'tail -n ' . LINES_COUNT . ' ' . TEXT_FILE;
		$output = shell_exec($cmd);
		echo htmlspecialchars($output);
	}
}

function read_file($file, $lines) 
{
    $handle = fopen($file, "r");
    $linecounter = $lines;
    $pos = -2;
    $beginning = false;
    $text = array();
    while ($linecounter > 0) {
        $t = " ";
        while ($t != "\n") {
            if(fseek($handle, $pos, SEEK_END) == -1) {
                $beginning = true; 
                break; 
            }
            $t = fgetc($handle);
            $pos --;
        }
        $linecounter --;
        if ($beginning) {
            rewind($handle);
        }
        $text[$lines-$linecounter-1] = fgets($handle);
        if ($beginning) break;
    }
    fclose ($handle);
    return array_reverse($text);
}
?>
