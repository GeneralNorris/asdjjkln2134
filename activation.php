<?php
require_once("functions/kind.php");
require_once("functions/config.php");
require_once("conf.php");
require_once("functions/db.php");
require_once("functions/core.php");
require_once("functions/log.php");
require_once("functions/mail.php");
require_once("functions/user.php");
require_once("functions/tokens.php");
core_init();


if (isset($_GET["token"])&& preg_match('/^[0-9A-F]{40}$/i', $_GET["token"])) {
    $token = $_GET["token"];

    if(token_verify_confirmation($token)){
        echo "Email erfolreich Bestätigt";
        $_SESSION["confirmation"] = array("success" => true);
    }
}else{
    $_SESSION["confirmation"] = array("success" => false);
    echo "Keine Berechtigung";
}
header("location: index.php?action=activation_result");