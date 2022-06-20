<?php

$storeid=trim(isset($_POST['storeid']) ? $_POST['storeid'] : (isset($_GET['storeid']) ? $_GET['storeid'] : '1'));
$debug=trim(isset($_POST['debug']) ? $_POST['debug'] : (isset($_GET['debug']) ? $_GET['debug'] : '0'));
$popup=addslashes(trim(isset($_POST['popup']) ? $_POST['popup'] : (isset($_GET['popup']) ? $_GET['popup'] : '')));
$magicinfo=trim(isset($_POST['magicinfo']) ? $_POST['magicinfo'] : (isset($_GET['magicinfo']) ? $_GET['magicinfo'] : ''));
$directdownload=trim(isset($_POST['directdownload']) ? $_POST['directdownload'] : (isset($_GET['directdownload']) ? $_GET['directdownload'] : 'no'));


$link = mysqli_connect('fcmkglnmysql.mysql.db', 'fcmkglnmysql', 'UtJ4C6Sh3VfBaY3C');
mysqli_select_db($link, 'fcmkglnmysql');
mysqli_query($link, 'SET NAMES utf8mb4');

