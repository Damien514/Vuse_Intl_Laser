<?php

	header("Access-Control-Allow-Origin: *");

	$epod=trim(isset($_POST['epod']) ? $_POST['epod'] : (isset($_GET['epod']) ? $_GET['epod'] : ''));
	$choix=trim(isset($_POST['choix']) ? $_POST['choix'] : (isset($_GET['choix']) ? $_GET['choix'] : ''));
	$contenu=trim(isset($_POST['contenu']) ? $_POST['contenu'] : (isset($_GET['contenu']) ? $_GET['contenu'] : ''));
	$texte=trim(isset($_POST['texte']) ? $_POST['texte'] : (isset($_GET['texte']) ? $_GET['texte'] : ''));
	$position=trim(isset($_POST['position']) ? $_POST['position'] : (isset($_GET['position']) ? $_GET['position'] : ''));
	$orientation=trim(isset($_POST['orientation']) ? $_POST['orientation'] : (isset($_GET['orientation']) ? $_GET['orientation'] : ''));
	$epodversion=trim(isset($_POST['epodversion']) ? $_POST['epodversion'] : (isset($_GET['epodversion']) ? $_GET['epodversion'] : '1'));
	$token=trim(isset($_POST['token']) ? $_POST['token'] : (isset($_GET['token']) ? $_GET['token'] : ''));
	$storeid=trim(isset($_POST['storeid']) ? $_POST['storeid'] : (isset($_GET['storeid']) ? $_GET['storeid'] : ''));
	$directdownload=trim(isset($_POST['directdownload']) ? $_POST['directdownload'] : (isset($_GET['directdownload']) ? $_GET['directdownload'] : '0'));


	if ($storeid && $choix && $epod) {
		include_once('../settings.inc.php');

		mysqli_query($link, 'SET NAMES utf8mb4');

		if (strlen($token)!=4) {
			do {
				$token=g();
				$result = mysqli_query($link, "SELECT `id` FROM `generate` WHERE `token`='$token' AND `actif`='1' LIMIT 1;");
				$i=mysqli_num_rows($result);
			} while ($i==1);
		}

		$result=mysqli_query($link, "INSERT INTO `generate` (`epod`, `choix`, `contenu`, `texte`, `position`, `orientation`, `token`, `actif`, `zone`, `epodversion`, `popup`) VALUES ('".addslashes($epod)."', '".addslashes($choix)."', '".addslashes($contenu)."', '".addslashes($texte)."', '".addslashes($position)."', '".addslashes($orientation)."', '".addslashes($token)."' ,'1', '".addslashes($storeid)."', '".addslashes($epodversion)."', '".addslashes($popup)."') ;");

		if ($directdownload=='yes') echo mysqli_insert_id($link);
		else echo $token;
		exit;
	} else {
		die('Arguments missing.');
	}


	function g() {
		$characters = '3456789ABCDEFGHJKLMNPQRTWXY';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < 4; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
