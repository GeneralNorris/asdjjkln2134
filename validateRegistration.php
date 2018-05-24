<?php
/**
 * Created by PhpStorm.
 * User: lvarnholt
 * Date: 06.04.2018
 * Time: 13:29
 */
require_once("conf.php");
require_once("functions/db.php");
require_once("functions/core.php");
require_once("functions/geo.php");
require_once("functions/user.php");
require_once("functions/kind.php");
core_init();

$user = array();
$person= array();
$error = false;
foreach ($_POST as $key => $value) {
    @list($type, $subkey) = explode("_", $key);
    if ($type == "user") {
        $user[$subkey] = trim($value);
    }
    if ($type == "person") {
        $person[$subkey] = trim($value);
    }
}

$checkData = user_checkRegistrationData($user,$person);
switch ($checkData){
    case 1: break;
    case 2: echo "%plz_wrong";
    break;
    case 3: echo "%password_wrong";
    break;
    case 4: echo "%email_used";
    break;
    case 5: echo "%checkbox_empty";
    break;
    case 6: echo "%data_empty";
    break;
}

if($checkData == 1){
    if (filter_var($person["email"], FILTER_VALIDATE_EMAIL) && !user_mailIsUsed($person["email"])) {

    }else{
        if(user_mailIsUsed($person["email"])){
            echo "%email_used";
        }else{
            echo "%email_wrong";
        }
        $error = true;
    }
}else{
    $error = true;
}
if(!$error){
    echo "%success";
}