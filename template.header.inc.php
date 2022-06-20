
<?php

	if (!isset($beta)) $beta='';
	if (!isset($os)) $os='';
	if (!isset($frontapp)) $frontapp=0;
	if (!isset($appname) || !$appname) $appname="VUSE";
	if (!isset($langue)) $langue="en";
	if (!isset($region)) $region="roc";
	if (!isset($headertags)) $headertags="$langue $region $os";

	$debug=time();

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="content-Type" content="text/html; charset=utf-8" />

	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<meta http-equiv="Content-Language" content="en" />

	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="application-mobile-web-app-title" content="<?=$appname?>">
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />

	<link rel="apple-touch-icon" sizes="167x167" href="/img/icone.png" />

	<title><?=$appname?></title>

	<script src="../commun/js/jquery.js"></script>
	<script src="../commun/js/transit.js"></script>

	<script src="/js/is.js"></script>

	<link rel="stylesheet" href="/commun/css/fonts/fonts.css" media="all">

	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css">
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>



	<link href="/_admin_/style.css?<?=$debug?>" rel="stylesheet" type="text/css" />


</head>
<body class="<?=$headertags?>" id="apidom">
