<?php

	$id=trim(isset($_POST['id']) ? $_POST['id'] : (isset($_GET['id']) ? $_GET['id'] : ''));

	include_once('../settings.inc.php');



	$result = mysqli_query($link, "SELECT * FROM `generate` WHERE `actif`='1x' AND `id`='".addslashes($id)."' LIMIT 1 ;");
	$nb=mysqli_num_rows($result);



	if ($nb==1) {

		$row = mysqli_fetch_array($result);
		extract($row);

		$updateid=$id;

		// Si c'est pas BACK, ce sera FRONT
		if ($position!='B') $position='F';

		// Pas de couleur ? Ce sera slate !
		if ($epod=='') $epod='slate';

		if ($choix=='icons' || $choix=='design') {
			$choix='sneakpeek';

			// Si c'est un PNG on est bon
			if (substr($contenu, -4)!='.png') {
				$rq = mysqli_query($link, "SELECT `fichier` FROM `icons` WHERE `nom_en` LIKE '".addslashes($contenu)."' LIMIT 1 ;");
				$r = mysqli_fetch_array($rq);
				$contenu=$r['fichier'];
			}
		} else if ($choix=='texte') {

			// Si c'est un TTF on est bon
			if (substr($contenu, -4)!='.ttf') $contenu.='.ttf';
		}

		$result = mysqli_query($link, "INSERT INTO `liste` (`epod`, `epodversion`, `choix`, `texte`, `contenu`, `position`, `orientation`, `nom`, `zone`, `fait`, `popup`) VALUES ('".addslashes($epod)."', 1, '".addslashes($choix)."', '".addslashes($texte)."', '".addslashes($contenu)."', '".addslashes($position)."', '".addslashes($orientation)."', '".addslashes($token)."', '".addslashes($storeid)."','1', '".addslashes($popup)."');");
		$id=mysqli_insert_id($link);

		$result = mysqli_query($link, "UPDATE `generate` SET `actif`='0', reponse='added to engrave list' WHERE `id`='$updateid' LIMIT 1 ;");


		$result = mysqli_query($link, "SELECT * FROM `liste` WHERE `id`='".addslashes($id)."' LIMIT 1 ;");

		if (mysqli_num_rows($result)==0) die('No asset.');

		$row = mysqli_fetch_array($result);
		extract($row);

		$param="&orientation=".urlencode($position)."&couleur=".urlencode($epod)."&nom=".urlencode($nom)."&epodversion=".urlencode($epodversion)."&".time();

		if ($choix=="texte") $url="./generate.text.php?font=".urlencode($contenu)."&positiontexte=$positiontexte&angle=$orientation&texte=".urlencode($texte);
		else if ($choix=="sneakpeek") $url="./download.php?file=".urlencode($contenu);
		else if ($choix=="audio") $url="./generate.audio.php?file=".urlencode($contenu);

		if ($url) {
			$result = mysqli_query($link, "UPDATE `liste` SET `fait`='1' WHERE `id`='".addslashes($id)."' LIMIT 1 ;");
			header('Location: '.$url.$param.'&download=1');
		}
	}
