<?php
require_once ("functions/tokens.php");
if (isset($_GET["token"]) && preg_match('/^[0-9A-F]{40}$/i', $_GET["token"])) {
	$token = $_GET["token"];
}
else {
	throw new Exception("Valid token not provided.");
}

if($userId = token_verify($token)){
	user_update_mailChecked($userId,1);
	mail_send_mailConfirmed($userId);
	if(user_isActivated($userId)){
	    mail_send_accountActivated($userId);
    }
	$_SESSION["confirmation"] = array("person_id" => $personId, "success" => true);
}else {

	$_SESSION["confirmation"] = array("person_id" => $personId, "success" => false);
}

header("location: index.php");