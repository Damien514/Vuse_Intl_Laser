<?php

	$zone=trim(isset($_POST['place_id']) ? $_POST['place_id'] : (isset($_GET['place_id']) ? $_GET['place_id'] : ''));
	$pin=strtoupper(trim(isset($_POST['pin']) ? $_POST['pin'] : (isset($_GET['pin']) ? $_GET['pin'] : '')));
	$token=trim(isset($_POST['token']) ? $_POST['token'] : (isset($_GET['token']) ? $_GET['token'] : ''));

	if (!$pin || !$zone) die("Error 1.");
	if (md5($zone.$pin)!=$token) die("Error 2.");
	include_once('../settings.inc.php');

	$result = mysqli_query($link, "SELECT * FROM `place` WHERE `id`='".addslashes($zone)."' AND `pincode`='".addslashes($pin)."' AND `active`='1'  LIMIT 1 ;");
	if (mysqli_num_rows($result)==0) die("Error 3.");

	$frontapp=0;

	$row = mysqli_fetch_array($result);

	$province=$row['province'];
	$nom=$row['nom'];

	include('../template.header.inc.php');
?>

	<script>document.title = "VUSE - <?=$nom?>";</script>
	<style>

		h3 {
			text-align: center;
			padding: 0;
		}

		.pastillevuse2 {
			margin: 10px 0;
		}

		div.legende {
			margin: 0 0 0 20px;
			display: inline-block;
			font-size: 14px;
			text-align: left;
		}

		div.containerepod {
			background: #ae8379;
			width: 40px;
			height: 217px;
			border-radius: 5px;
			box-shadow: 4px 4px 15px #000;
			display: inline-block;
			margin: 0 0 20px;
			transition: all 290ms;
		}

		div.containerepod.silver { background: #bbb; }
		div.containerepod.graphite { background: #666; }
		div.containerepod.black { background: #222; }
		div.containerepod.gold { background: #ad9d77; }
		div.containerepod.rosegold { background: #ae8379; }

		div.containerepod.emerald { background: #337815; }
		div.containerepod.glacier { background: #1D558B; }
		div.containerepod.citrine { background: #baa821; }
		div.containerepod.sandstone { background: #AB2625; }
		div.containerepod.pinksalt { background: #B85685; }

		div.containerepod img {
			width: 40px;
			height: 217px;
		}

		div.containerepod.black img,
		div.containerepod.graphite img {
			filter: invert(100%);
		}

		div.containerepod:hover {
			transform: translateY(-10px);
		}

		.liste {
			padding: 20px 0;
			background: #112;
			margin-bottom: 40px;
			border-top: 1px solid #fff;
			border-bottom: 1px solid #fff;
		}

		label {
			width: 110px;
		}

		span.titre {
			font-weight: 700;
			font-size: 20px;
			text-transform: uppercase;

		}
	</style>

	<div class="center">
		<img src="../commun/img/vuseXu.svp.svg" class="pastillevuse3">
		<h3>ENGRAVING STATION<br><span style="font-size:20px"><?=$nom?><a></a></h3>


		<div id="liste"></div>

	</div>

	<span style="position:fixed;bottom:2px;right:2px;font-size:10px;color:#999"><?=$nom?></span>

	<script>

	var oqp=0, temps;
	$('body').addClass('setup');

	function reload(id) {
		clearTimeout(temps);
		if (!oqp) {
			oqp=1;
			$("#liste").hide();
			$.ajax({
			url: './_ajax.php?token=<?=$token?>&zone=<?=$zone?>&action=liste&id='+id+'&rnd='+Math.random(),
				success: function(data) {
					var t=data.split(";;oOo;;");
					$("#liste").html(t[1]).fadeIn(250, function() {
						oqp=0;
						if (t[0]=='0') temps=setTimeout("reload()", 80000);
					});
				}
			});
		}
	}

	function done(id,nom) {

		$.confirm({
		    title: nom,
		    content: 'Job done?',
		    theme: 'supervan',
		    buttons: {
			   Yes: {
				   text: 'Yes',
				  action: function() {
					  reload(id);
					}
			   },
			   Cancel: {
				    text: 'Cancel',
				  action: function() { encours=0; }
			   }
		    }
		});
	}

	$(document).ready(function() {
		reload();
	});

	</script>
</body>
</html>
