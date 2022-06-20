<?php

$days =7;
$path = './generated/';
$filetypes_to_delete = array('wav','png');

if ($handle = opendir($path)) {
    while (false !== ($file = readdir($handle))) {
	   if (is_file($path.$file)) {
		  $file_info = pathinfo($path.$file);
		  if (isset($file_info['extension']) && in_array(strtolower($file_info['extension']), $filetypes_to_delete))   {
			 if (filemtime($path.$file) < ( time() - ( $days * 24 * 60 * 60 ) ) ) {
				unlink($path.$file);
			 }
		  }
	   }
    }
}
