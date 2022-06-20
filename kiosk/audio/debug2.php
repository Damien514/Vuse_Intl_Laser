<?php

$filename='e11ad6beb620985a7f3f';

include 'generate.design.test.epod2.php';


generateAudio($filename,'1',1);
echo '1 generated.<br>';


generateAudio($filename,'4',2);
echo '2 generated.<br>';

generateAudio($filename,'0',3);
echo '3 generated.<br>';
generateAudio($filename,'5',4);
echo '4 generated.<br>';
generateAudio($filename,'300',5);
echo '5 generated.<br>';
generateAudio($filename,'301',6);
echo '6 generated.<br>';
generateAudio($filename,'302',7);
echo '7 generated.<br>';




echo $filename;
exit;

