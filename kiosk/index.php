<?php

	include_once('../settings.inc.php');


	$result = mysqli_query($link, "SELECT * FROM `place` WHERE `id`='".addslashes($storeid)."' LIMIT 1 ;");
	$row = mysqli_fetch_array($result);
	$screensaver=$row['background'];
	$area=$row['area'];
	$audioengraving=$row['audioengraving'];

	$colours=$row['colours'];
	$customcss=$row['css'];
	if ($customcss) $customcss="<style>$customcss</style>";

	$r=explode(',',$colours);
	$nbcol=0;
	$defaultcol='';
	foreach ($r as &$c) {
		$avcol[$c] = 1;
		$nbcol++;
		if ($defaultcol=='') $defaultcol=$c;
	}

	if ($avcol['graphite']==1) $defaultcol='graphite';

	if ($magicinfo=='1') $directdownload='no';
	else $directdownload='yes';

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, user-scalable=no, initial-scale=1.0" />

	<title>VUSE Engraving Station</title>

	<link rel="stylesheet" type="text/css" href="../commun/css/keyboard/jkeyboard.css" />

	<link rel="stylesheet" type="text/css" href="../commun/css/base.css?<?=time()?>" />
	<link rel="stylesheet" type="text/css" href="./css/style_epod2.css?<?=time()?>" />
	<link rel="stylesheet" type="text/css" href="../commun/css/fonts/fonts.css" />

	<style>
		#screensaver {
			background: #000 url(screensaver/<?=$screensaver?>) left top/1080px 1920px fixed no-repeat;
		}

		<?php if ($debug!='0') { ?>
			*, *:hover {
				cursor: none;
			}

		<?php } ?>
	</style>

	<script src="../commun/js/jquery.js"></script>
	<script src="../commun/js/transit.js"></script>
	<script src="../commun/js/flickity.js"></script>
	<script src="../commun/js/fitty.min.js"></script>
	<script src="../commun/js/jkeyboard.js"></script>

	<script>
		const storeid='<?=$storeid?>', debug='<?=$debug?>', dureeScreensaver=60, directdownload='<?=$directdownload?>', nbcol='<?=$nbcol?>',defaultcol='<?=$defaultcol?>';

<?php

/* On récupère les éléments de gravure (dynamique) */

$oldcollection='';
$fich='';
$nom='';
$result = mysqli_query($link, "SELECT `collection`, `fichier`, `nom_en` FROM `icons` WHERE `active`='1' AND `epodversion`='2' AND `collection`!='20' AND (`area`='".$area."' OR `area`='') ORDER BY `collection`,`ordre`,`nom_en` ASC ;");

while($row = mysqli_fetch_array($result)) {
	extract($row);
	if (substr($fichier, 0,4)!='icon') {
		if ($collection!=$oldcollection) {
			$oldcollection=$collection;
			if ($collection=='5') {
				$fich.="'COMPLEMENTARY',";
				$nom.="'COMPLEMENTARY',";
			} else if ($collection=='30') {
				$fich.="'DESIGN',";
				$nom.="'DESIGN',";
			}
		}
		$fich.="'$fichier',";
		$nom.="'$nom_en',";
	}
}

$fich=substr($fich, 0, -1);
$nom=substr($nom, 0, -1);



$oldcollection='';
$fich2='';
$nom2='';
$result = mysqli_query($link, "SELECT `collection`, `fichier`, `nom_en` FROM `icons` WHERE `active`='1' AND `collection`!='30' AND `epodversion`='2' AND (`area`='".$area."' OR `area`='') ORDER BY `collection`,`ordre`,`nom_en` ASC ;");
while($row = mysqli_fetch_array($result)) {
	extract($row);
	if (substr($fichier, 0,6)!='design') {
		if ($collection!=$oldcollection) {
			$oldcollection=$collection;
			if ($collection=='5') {
				$fich2.="'COMPLEMENTARY',";
				$nom2.="'COMPLEMENTARY',";
			} else if ($collection=='20') {
				$fich2.="'ICONS',";
				$nom2.="'ICONS',";
			}
		}
		$fich2.="'$fichier',";
		$nom2.="'$nom_en',";
	}
}

$fich2=substr($fich2, 0, -1);
$nom2=substr($nom2, 0, -1);


$oldcollection='';
$fich3='';
$nom3='';
$result = mysqli_query($link, "SELECT `collection`, `fichier`, `nom_en` FROM `fonts` WHERE `active`='1' ORDER BY `collection`,`ordre`,`nom_en` ASC ;");
while($row = mysqli_fetch_array($result)) {
	extract($row);
		if ($collection!=$oldcollection) {
			$oldcollection=$collection;
			if ($collection=='10') {
				$fich3.="'COMPLEMENTARY',";
				$nom3.="'',";
			} else if ($collection=='20') {
				$fich3.="'SWIPE',";
				$nom3.="'',";
			}
		}
		$fich3.="'$fichier',";
		$nom3.="'$nom_en',";


}

$fich3=substr($fich3, 0, -1);
$nom3=substr($nom3, 0, -1);



$fich4='';
$result = mysqli_query($link, "SELECT `fichier` FROM `design` WHERE `active`='1' ORDER BY `collection`,`ordre`,`nom_en` ASC ;");
while($row = mysqli_fetch_array($result)) {
	extract($row);
	$fich4.="'$fichier',";
}
$fich4=substr($fich4, 0, -1);

?>

		const elementsEpod=[<?=$fich?>], elementsEpodName=[<?=$nom?>], miniicons=[<?=$fich2?>], miniiconsName = [<?=$nom2?>], polices=[<?=$fich3?>], policesname=[<?=$nom3?>], customLIST=[<?=$fich4?>];

	</script>

	</head>
	<body bgcolor="#000000" class="en debug<?=$debug?>">

		<!-- Clavier virtuel -->
		<div id="keyboard"></div>
		<img id="vuseXu" src="../commun/img/vuseXu.svp.svg" />

		<div id="main" class="page">

			<div id="epod" class="epod">
				<div id="mainepod">
					<div class="epod epodlayer"></div>
				</div>
			</div>

			<div id="iconescouleur" class="hide">
				<div class='carousel-cell icons' style='background:none!important'></div>
				<div class='carousel-cell icons' style='background:none!important'></div>

				<?php if ($avcol['graphite']==1) { ?>
				<div id="ic_graphite" class='icons' onclick="changeEpodCouleur('graphite')">
					<div class="icons_skin" style="background:#595656 url('../commun/img/epod2/graphite.png') center 450px"></div>
					<div class="icons_skin_active"></div>
					<div class="textelegende">Graphite</div>
				</div>
				<?php } ?>

				<?php if ($avcol['black']==1) { ?>
				<div id="ic_black" class='icons' onclick="changeEpodCouleur('black')">
					<div class="icons_skin" style="background:#2a2f2e url('../commun/img/epod2/black.png') center 450px"></div>
					<div class="icons_skin_active"></div>
					<div class="textelegende"><span class="en">Black</span></div>
				</div>
				<?php } ?>

				<?php if ($avcol['silver']==1) { ?>
				<div id="ic_silver" class='icons' onclick="changeEpodCouleur('silver')">
					<div class="icons_skin" style="background:#9a9b9c url('../commun/img/epod2/silver.png') center 450px"></div>
					<div class="icons_skin_active"></div>
					<div class="textelegende"><span class="en">Silver</span></div>
				</div>
				<?php } ?>

				<?php if ($avcol['gold']==1) { ?>
				<div id="ic_gold" class='icons' onclick="changeEpodCouleur('gold')">
					<div class="icons_skin" style="background:#ad9d77 url('../commun/img/epod2/gold.png') center 450px"></div>
					<div class="icons_skin_active"></div>
					<div class="textelegende"><span class="en">Gold</span></div>
				</div>
				<?php } ?>

				<?php if ($avcol['rosegold']==1) { ?>
				<div id="ic_rosegold" class='icons' onclick="changeEpodCouleur('rosegold')">
					<div class="icons_skin" style="background:#a18381 url('../commun/img/epod2/rosegold.png') center 450px"></div>
					<div class="icons_skin_active"></div>
					<div class="textelegende"><span class="en">Rose Gold</span></div>
				</div>
				<?php } ?>

				<?php if ($avcol['darkminst']==1) { ?>
				<div id="ic_darkminst" class='icons' onclick="changeEpodCouleur('darkminst')">
					<div class="icons_skin" style="background:#254daa url('../commun/img/epod2/darkminst.png') center 450px"></div>
					<div class="icons_skin_active"></div>
					<div class="textelegende"><span class="en">Blue</span></div>
				</div>
				<?php } ?>

				<?php if ($avcol['cyan']==1) { ?>
				<div id="ic_cyan" class='icons' onclick="changeEpodCouleur('cyan')">
					<div class="icons_skin" style="background:#68959b url('../commun/img/epod2/cyan.png') center 450px"></div>
					<div class="icons_skin_active"></div>
					<div class="textelegende"><span class="en">Aqua</span></div>
				</div>
				<?php } ?>

				<?php if ($avcol['red']==1) { ?>
				<div id="ic_red" class='icons' onclick="changeEpodCouleur('red')">
					<div class="icons_skin" style="background:#e42727 url('../commun/img/epod2/red.png') center 450px"></div>
					<div class="icons_skin_active"></div>
					<div class="textelegende"><span class="en">Red</span></div>
				</div>
				<?php } ?>

				<div class='carousel-cell icons' style='background:none!important'></div>
				<div class='carousel-cell icons' style='background:none!important'></div>

			</div>

			<div class="titre"></div>

			<!-- Sélection du type de gravure (Design uniquement) -->
			<div id="typedegravure" class="hide">
				<div class='icons' onclick="selectType('design')">
					<div class='icons_skin' style='background: url(https://vuse.ca.engraving.apidom.com/assets/icons/design_aro_V2.png) center -212px/114px no-repeat!important;
    filter: invert(1);'></div>
					<div class="textelegende"><span class="en">Patterns</span></div>
				</div>

				<div class='icons' onclick="selectType('icons')">
					<div class='icons_skin' style='background: url(https://vuse.ca.engraving.apidom.com/assets/icons/icon_brella_V2.png) center -212px/114px no-repeat!important;
    filter: invert(1);'></div>
					<div class="textelegende"><span class="en">Mini Icons</span></div>
				</div>

				<div class='icons' onclick="selectType('texte')" style="background-position-y: 1200px!important;">
					<div class="icons_fonts" style="font-family:'TrimWeb';height:80px;padding-top:80px">
						<span class='fit3'>Abc</span>
					</div>
					<div class="textelegende">Custom text</div>
				</div>

				<div class='icons' onclick="selectType('custom')" style="background-position-y: 1200px!important;">
					<div class='icons_skin' style="text-align: center; -ms-filter: invert(100%); -webkit-filter: invert(100%); filter: invert(100%);margin-top:80px">
						<img src="./img/custom1.png" width="36" height="36" >
						<img src="./img/custom2.png" width="36" height="36" >
						<img src="./img/custom3.png" width="36" height="36" >
					</div>
					<div class="textelegende"><span class="en">Customize</span></div>
				</div>
				<?php if ($audioengraving=='1') { ?>
				<div class='icons' onclick="selectType('audiosignature')">
					<div class='icons_skin' style='background:url(./img/audio.png) center center/166px auto!important'></div>
					<div class="textelegende">Audio Signature</div>
				</div>
				<?php } ?>

			</div>

			<!-- Bouton continuer -->
			<div id="boutonNEXT" class="boutonNEXT hide">
				<span class="en" onclick="nextSTEP()">NEXT ›</span>
			</div>
			<div id="boutonPREV" class="boutonPREV hide">
				<span class="en" onclick="backSTEP()">‹ BACK</span>
			</div>
			<div id="boutonSUBMIT" class="boutonNEXT hide">
				<span class="en" onclick="submitDESIGN()">SUBMIT ›</span>
			</div>
			<!-- Sélection des éléments -->
			<div id="icones_skin" class="hide"></div>
			<div id="miniicons" class="hide"></div>

			<div id="custom" class="center hide"></div>
			<div id="grille"></div>

			<!-- Sélection des polices -->
			<div id="icones_polices" class="hide"></div>
			<div id="orientation_polices" class="hide">
				<span class="bouton horizontal" onclick='switchOrientation(0)'>Vertical</span>
				<span class="bouton vertical" onclick='switchOrientation(1)' style='display:none'>Horizontal</span>
				<span class="bouton front" onclick='switchSide(1)'><span class="en">Back side</span></span>
				<span class="bouton back off" onclick='switchSide(0)' style='display:none'><span class="en">Front side</span></span>
			</div>
			<div id="textemenu" class="hide">
				<span class="bouton" onclick="afficheFonts()"><span class='en'>Fonts</span></span>
				<span class="bouton" onclick="afficheOrientation()">Orientation</span>
				<span class="bouton" onclick="afficheKeyboard()"><span class='en'>Keyboard</span></span>
			</div>
			<div id="champstexte"><span></span></div>

		</div>


		<div id="thanks_v2" class="page">
			<h1><span class="en outline">Engraving in progress</span></h1>
			<h2><span class="en">Wait while your device is being engraved.</span><br><br></h2>
			<br><br><br>
			<h2><span class="en">If you want, you can add a custom text on the back of your device.</span></h2>
			<br><br>
			<span class="bouton end" onclick="addTextOnBack()"><span class="en">Add a text</span></span><br><br>
			<span class="bouton end" onclick="Screensaver()"><span class="en">I'm fine</span></span>
		</div>



		<div id="thanks" class="page">
			<h1><span class="en outline">Engraving in progress</span></h1>
			<h2><span class="en">Wait while your device is being engraved.</span><br><br></h2>
			<br><br><br>
			<span class="bouton end" onclick="Screensaver()"><span class="en">Ok</span></span>
		</div>

		<input type="text" disabled readonly onfocus="this.blur()" style="display: none" value="" id="letexte" />

		<?php

		if ($audioengraving=='1') {
			include_once('audio/include_epod_audio_epod2.php');
		}

		?>

		<div id="debug" style="background: transparent;position:fixed; top:0; left:0; min-width:200px; min-height:200px; font-size:60px;color:#f00;display: inline-block;z-index: 749999997" onclick="window.location.reload(true);"></div>
		<div id="screensaver" onclick="stopScreensaver()"></div>

		<?=$customcss?>
		<script src="./js/js_epod2.js?<?=time()?>"></script>
	</body>
</html>
