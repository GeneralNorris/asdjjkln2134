<?php

user_logout();
session_start();

$_SESSION['login'] 		= htmlentities($_POST['login']);
$_SESSION['password'] 	= md5($_POST['password']);
$_SESSION['ip'] 		= core_securechars($_SERVER['REMOTE_ADDR']);

$_SESSION['do_log_login'] = true;
user_check_login(true, 1, 0,1);

header("location: teilhabe.php?action=userProfile");


