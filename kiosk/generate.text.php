<?php

	// DEMO
	// https://vl.apidom.com/_front_/generate.text.php?font=Le_Murmure-Regular_web.ttf&angle=0&texte=Li&test=1&orientation=B
	// https://vl.apidom.com/_front_/generate.text.php?font=Le_Murmure-Regular_web.ttf&angle=90&texte=Li&test=1&orientation=F&positiontexte=

	//
	// PNG de 400 x 2175
	//


	// On charge la fonte demandée
	$f=trim(isset($_POST['font']) ? $_POST['font'] : (isset($_GET['font']) ? $_GET['font'] : ''));
	$angle=trim(isset($_POST['angle']) ? $_POST['angle'] : (isset($_GET['angle']) ? $_GET['angle'] : '0'));
	$texte=trim(isset($_POST['texte']) ? $_POST['texte'] : (isset($_GET['texte']) ? $_GET['texte'] : ''));
	$test=trim(isset($_POST['test']) ? $_POST['test'] : (isset($_GET['test']) ? $_GET['test'] : ''));
	$orientation=strtoupper(trim(isset($_POST['orientation']) ? $_POST['orientation'] : (isset($_GET['orientation']) ? $_GET['orientation'] : 'F')));
	$download=trim(isset($_POST['download']) ? $_POST['download'] : (isset($_GET['download']) ? $_GET['download'] : '0'));
	$nom=trim(isset($_POST['nom']) ? $_POST['nom'] : (isset($_GET['nom']) ? $_GET['nom'] : 'VUSE'));
	$couleur=trim(isset($_POST['couleur']) ? $_POST['couleur'] : (isset($_GET['couleur']) ? $_GET['couleur'] : ''));
	$epodversion=trim(isset($_POST['epodversion']) ? $_POST['epodversion'] : (isset($_GET['epodversion']) ? $_GET['epodversion'] : '1'));
	$positiontexte=strtoupper(trim(isset($_POST['positiontexte']) ? $_POST['positiontexte'] : (isset($_GET['positiontexte']) ? $_GET['positiontexte'] : '')));

	if ($f==='Abebedera') $f='A-Bebedera.ttf';
	else if ($f==='Ahamono') $f='AHAMONO-Monospaced.ttf';
	else if ($f==='Arabella') $f='Arabella.ttf';
	else if ($f==='Droid') $f='droid.ttf';
	else if ($f==='Fusterd') $f='Fusterd Brush Two.ttf';
	else if ($f==='Honeyscript') $f='HoneyScript-Light.ttf';
	else if ($f==='Indonesian') $f='Indonesian.ttf';
	else if ($f==='Le Murmure') $f='Le_Murmure-Regular_web.ttf';
	else if ($f==='Monument') $f='MonumentExtended-Regular.ttf';
	else if ($f==='Neo Writer') $f='Neo-Writer.ttf';

	// Gestion de la langue arabe

	if (is_arabic($texte)) {
		$f='arial.ttf';
		require('../_generators_/I18N/Arabic.php');
		$Arabic = new I18N_Arabic('Glyphs');
		$texte = $Arabic->utf8Glyphs($texte);
	}

	// On génère une image vide et transparente
	if ($test=='1') {	// Test sur une image de device
		if ($orientation=='F') $im=imagecreatefrompng('test-epod.png');
		else $im=imagecreatefrompng('test-epod-back.png');
		$black = imagecolorallocate($im, 255,255,255);

	} else if ($test=='2') {	// Test sur la grille de calibration
		$im=imagecreatefrompng('../assets/icons/design_test.png');
		$black = imagecolorallocate($im, 0, 0, 0);

	}else {
		$im=imagecreatetruecolor(400,2160);
		imagesavealpha($im, true);
		imagealphablending($im, false);
		$white = imagecolorallocatealpha($im, 255, 255, 255, 127);
		imagefill($im, 0, 0, $white);
		$black = imagecolorallocate($im, 0, 0, 0);
	}

	if ($texte) {
		if ($orientation=='F') $zonesafehauteur=930;
		else {
			if ($epodversion==2) $zonesafehauteur=750;
			else $zonesafehauteur=750;
		}

		if ($epodversion==2) $margetop=150+30;
		else $margetop=150+15;

		//if ($test=='1' && $angle=='0') $zonesafehauteur=300;

		//$widthhorizontal=300;
		//$widthvertical=150;


		// Texte un peu plus petit
		$widthhorizontal=280;
		$widthvertical=120;



		//if (!$font || !$angle || !$texte) exit;

		$font='../assets/fonts/'.$f;
		if (!file_exists($font)) {
			$font = str_replace(' ', '-', $f);
			if (!file_exists($font)) {
				echo "Font file not available ($f)";
				exit;
			}
		}

		// Taille font de départ. On commence petit...
		$t=12;
		$ok=0;

		// On ajuste nos dimensions;
		$zonesafehauteur-=$margetop;

		if ($angle=="H") $angle='0';
		else if ($angle=="V") $angle='90';
		if ($angle!='0') $angle='90';


		/// On cherche la bonne taille de caractères
		while ($ok<1) {
			$t+=.5;
			$dimensions = imagettfbbox($t, $angle, $font, $texte);

			$boxXCoords = array($dimensions[0], $dimensions[2], $dimensions[4], $dimensions[6]);
			$boxYCoords = array($dimensions[1], $dimensions[3], $dimensions[5], $dimensions[7]);
			$xsize = max($boxXCoords) - min($boxXCoords);
			$ysize = max($boxYCoords) - min($boxYCoords);

			if ($angle=='0' && ($xsize>$widthhorizontal || $ysize>$zonesafehauteur || $t>100)) $ok=1;
			else if ($angle=='90' && ($xsize>$widthvertical || $ysize>$zonesafehauteur || $t>100)) $ok=1;
			else {
				$w=$xsize;
				$h=$ysize;
				$taille=$t;
				$boxBaseX = abs(min($boxXCoords)-1);
				$boxBaseY = abs(min($boxYCoords)-1);
			}
		}

		// On trouve la baseline en se basant sur 5 caractères de test.
		$dimensions = imagettfbbox($taille, $angle, $font, 'soane');
		$boxXCoords = array($dimensions[0], $dimensions[2], $dimensions[4], $dimensions[6]);
		$boxYCoords = array($dimensions[1], $dimensions[3], $dimensions[5], $dimensions[7]);

		if ($angle==='90') {
			$w=max($boxXCoords) - min($boxXCoords);;
			$boxBaseX = abs(min($boxXCoords)-1);
		} else {
			$h=max($boxYCoords) - min($boxYCoords);
			$boxBaseY = abs(min($boxYCoords)-1);
		}





		$left=floor((400-$w)/2)+$boxBaseX;
		if ($angle=='90') {
			$top=$margetop+$h;
			if ($positiontexte=='R') {
				$left=400-30;
			} else if ($positiontexte=='L') {
				$left=40+$boxBaseX;
			}
		} else $top=floor(($zonesafehauteur-$h)/2)+$margetop+$boxBaseY;

		imagettftext($im, $taille, $angle, $left, $top, $black, $font, $texte);
		//imagerectangle ($im, $left , $top , $left+$w, $top+$h , $black );

	}

	if ($download=='1') {
		$filename=strtolower(date("Y_m_d_H_i")."_".$couleur."V".$epodversion."_".$orientation."_".prepareTXT($nom).".png");
		header('Content-Disposition: attachment; filename="'.$filename.'"');
	}

	header('Content-Type: image/png');
	imagepng($im);
	imagedestroy($im);

	exit;

function prepareTXT($str) {
	return preg_replace("/[^a-z0-9\.]/", "_", strtolower($str));
	}

function is_arabic($str) {
	 return preg_match('/([\p{Arabic}])+/u', $str);
}
