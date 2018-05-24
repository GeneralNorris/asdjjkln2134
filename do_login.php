<?php
require_once("functions/core.php");
require_once("functions/passhash.php");
user_logout();
session_start();

$_SESSION['login'] 		= core_securechars($_POST['login']);
$_SESSION['password'] 	= md5($_POST['password']);
$_SESSION['ip'] 		= core_securechars($_SERVER['REMOTE_ADDR']);

$_SESSION['do_log_login'] = true;

header("location: portal.php?action=antraege");