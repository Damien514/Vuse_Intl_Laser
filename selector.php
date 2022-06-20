<?php


/*


// UPDATE NATIVEFIER : npm install -g nativefier //

// nativefier 'https://vuse.airport.apidom.com/selector.php?ok=damien' -p windows -n "VUSE Kiosk" --background #000000 --disable-context-menu --disable-dev-tools --full-screen --browserwindow-options '{"title":"VUSE Kiosk","backgroundThrottling":false}' --single-instance --file-download-options '{"saveAs": false, "directory":"c:/VUSE-Live/Check-Orders"}' -i /Volumes/MacBook\ Pro/Users/damien/Documents/Apidom/Tech/WWW/vuse.us.engraving/icone_download.png --disable-old-build-warning-yesiknowitisinsecure --ignore-certificate --insecure --internal-urls ".*?\.apidom\.*?" --app-copyright "APIDOM Inc." --app-version "1.0"

*/


$ok=trim(isset($_POST['ok']) ? $_POST['ok'] : (isset($_GET['ok']) ? $_GET['ok'] : ''));
if ($ok!='damien') die('end.');

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, user-scalable=no, initial-scale=1.0" />

	<title>VUSE kiosk</title>

	<link rel="stylesheet" type="text/css" href="./commun/css/base.css?<?=time()?>" />
	<link rel="stylesheet" type="text/css" href="./commun/css/fonts/fonts.css" />

	<script src="./commun/js/jquery.js"></script>
	<script src="./commun/js/transit.js"></script>

	<style>

		.center {
			text-align: center;
		}

		span.bt {
			font-size: 30px;
			padding: 20px 50px;
			border-radius: 5px;
			width: 400px;
			border: 1px solid #fff;
			color: #fff;
			text-align: center;
			display: inline-block;
			background: rgba(0,0,0,.5);
		}

		div.bot {
			font-size:12px;
			position: fixed;
			text-align: center;
			bottom: 10px;
			width: 1080px;
		}
	</style>
	<script>
		function go(id,dbg) {
			window.location = '/kiosk/?debug='+dbg+'&storeid='+id;
		}
	</script>
</head>
<body class="center">
	<div class="bot">VUSE Kiosk loader - YHP / APIDOM Inc,</div>
	<br><br>
	<h1>SELECT<br>OPERATION MODE</h1>
	<br><br><br>
	<span class="bt" onclick="go(1,1)">Test mode (HD)</span>
	<br><br><br><br><br>
	<br><br><br><br><br>
	<span class="bt" onclick="go(2,1)">Production (HD)</span>
	<span class="bt" onclick="go(2,0)">Production (4K)</span>
	<br>
</body>
</html>
