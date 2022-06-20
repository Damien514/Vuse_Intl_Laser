<?php

	// DEMO
	// https://vl.apidom.com/_front_/generate.design.php?liste=%3B%3B%3B%3B%3B%3B1803_VUSE_Custom+Engraving_AUG14-Artboard+9.png%3B1803_VUSE_Custom+Engraving_AUG14-Artboard+10+_B.png%3B%3B%3B%3B%3B%3B%3B1803_VUSE_Custom+Engraving_AUG14-Artboard+13.png%3B%3B%3B%3B%3B%3B%3B%3B%3B%3B1803_VUSE_Custom+Engraving_AUG14-Artboard+10.png&orientation=F&couleur=black&nom=fsdfds&download=1

	//
	// PNG de 400 x 2175
	//


header ("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header ("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header ("Allow: GET, POST, OPTIONS, PUT, DELETE");

	$liste=trim(isset($_POST['liste']) ? $_POST['liste'] : (isset($_GET['liste']) ? $_GET['liste'] : ''));
	$test=trim(isset($_POST['test']) ? $_POST['test'] : (isset($_GET['test']) ? $_GET['test'] : '0'));
	$download=trim(isset($_POST['download']) ? $_POST['download'] : (isset($_GET['download']) ? $_GET['download'] : ''));
	$nom=trim(isset($_POST['nom']) ? $_POST['nom'] : (isset($_GET['nom']) ? $_GET['nom'] : ''));
	$couleur=trim(isset($_POST['couleur']) ? $_POST['couleur'] : (isset($_GET['couleur']) ? $_GET['couleur'] : ''));
	$orientation=trim(isset($_POST['orientation']) ? $_POST['orientation'] : (isset($_GET['orientation']) ? $_GET['orientation'] : 'F'));
	$epodversion=trim(isset($_POST['epodversion']) ? $_POST['epodversion'] : (isset($_GET['epodversion']) ? $_GET['epodversion'] : '1'));

	// On génère une image vide et transparente
	if ($test=='1') {
		$im=imagecreatefrompng('test-epod.png');
	} else {
		$im=imagecreatetruecolor(400,2160);
		imagesavealpha($im, true);
		imagealphablending($im, true);

		if ($download=='1') $white = imagecolorallocate($im, 255,255,255);
		else $white = imagecolorallocatealpha($im, 255,255,255,127);

		imagefill($im, 0, 0, $white);
	}

	$l=explode(';', $liste);

	// width : 130
	// width * 3 : 390
	// top : 130 + 15 - 18
	// left : 8

	$x=8;
	$y=145-18;

	foreach ($l as &$image) {
		if ($image!='') {
			$tile=imagecreatefrompng('../assets/design/'.$image);
			imagesavealpha($tile, true);
			imagecopyresampled($im, $tile, $x, $y, 0, 0, 128, 128, imagesx($tile), imagesy($tile));
		}
		$x+=128;
		if ($x>360) {
			$x=5;
			$y+=128;
		}
	}

	if ($download=='1') {
		$filename=strtolower(date("Y_m_d_H_i")."_".$couleur."V".$epodversion."_".$orientation."_".prepareTXT($nom).".png");
		header('Content-Disposition: attachment; filename="'.$filename.'"');
	}

	header('Content-Type: image/png');

	imagesavealpha($im, true);
	imagepng($im);
	imagedestroy($im);
	exit;

function prepareTXT($str) {
	return preg_replace("/[^a-z0-9\.]/", "_", strtolower($str));
}
