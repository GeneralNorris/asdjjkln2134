<?php
//2010-07-12 
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin,contactor_manager', true);
require_once("functions/user.php");
$a = false;
$b = false;
$c = false;

$userID = (int) $_GET['deleteUserID'];

if(user_check_editpermission($userID)) {
    if(!user_hatAntrag($userID) || !user_hatGeschenke($userID)) {
        $b = true;
        if (user_hatKinder($userID)) {
            $b = user_deleteKinder($userID);
        }
        if($b) {
            $a = user_deleteByID($userID);
        }
    }else{
        $c = true;
    }
}
if($a && $b ){
    $_SESSION["userDelete"] = "success";
    log_add("userdelete", $userID);
}else if($c){
    $_SESSION["userDelete"] = "geschenke";
} else if(!$b){
    $_SESSION["userDelete"] = "kindFehler";
} else{
    $_SESSION["userDelete"] = "fehler";
}
if (!(@$_GET['ajax'] === "true")) {
	header("location: portal.php?action=user");
}