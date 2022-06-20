<?php

	$file=trim(isset($_POST['file']) ? $_POST['file'] : (isset($_GET['file']) ? $_GET['file'] : ''));

	$filename=strtolower(date("Y_m_d_H_i")."_".$couleur."V".$epodversion."_".$orientation."_".prepareTXT($nom).".png");

	header('Content-Type: image/png');
	header('Content-Disposition: attachment; filename="'.$filename.'"');
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: '.filesize($file));    // provide file size
	readfile($file);       // push it out

function prepareTXT($str) {
	return preg_replace("/[^a-z0-9\.]/", "_", strtolower($str));
}
