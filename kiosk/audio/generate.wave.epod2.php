<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

$filename=time().'_'.rand(0,99999);

move_uploaded_file($_FILES['file']['tmp_name'], 'generated/'.$filename.'.wav');

//include 'generate.design.php';

include 'generate.design.test.epod2.php';


generateAudio($filename,'1',1);
generateAudio($filename,'4',2);
generateAudio($filename,'0',3);
generateAudio($filename,'5',4);
generateAudio($filename,'300',5);
generateAudio($filename,'301',6);
generateAudio($filename,'302',7);

// On efface le fichier audio
unlink('generated/'.$filename.".wav");

echo $filename;
exit;
