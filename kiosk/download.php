<?php

	$couleur=trim(isset($_POST['couleur']) ? $_POST['couleur'] : (isset($_GET['couleur']) ? $_GET['couleur'] : ''));
	$nom=trim(isset($_POST['nom']) ? $_POST['nom'] : (isset($_GET['nom']) ? $_GET['nom'] : ''));
	$orientation=trim(isset($_POST['orientation']) ? $_POST['orientation'] : (isset($_GET['orientation']) ? $_GET['orientation'] : ''));
	$epodversion=trim(isset($_POST['epodversion']) ? $_POST['epodversion'] : (isset($_GET['epodversion']) ? $_GET['epodversion'] : '1'));

	$file=trim(isset($_POST['file']) ? $_POST['file'] : (isset($_GET['file']) ? $_GET['file'] : ''));
	$file="../assets/icons/$file";

	$filename=strtolower(date("Y_m_d_H_i")."_".$couleur."V".$epodversion."_".$orientation."_".prepareTXT($nom).".png");

	header('Content-Type: image/png');
	header('Content-Disposition: attachment; filename="'.$filename.'"');
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: '.filesize($file));    // provide file size
	readfile($file);       // push it out

function prepareTXT($str) {
	return preg_replace("/[^a-z0-9\.]/", "_", strtolower($str));
}
