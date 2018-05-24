<?php
/**
 * Created by PhpStorm.
 * User: lvarnholt
 * Date: 30.11.2017
 * Time: 16:59
 */
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin', true);

$error = false;
$spendeId = false;

$spender = array();
print_r("<pre>");
print_r($_POST);
foreach ($_POST as $key => $value) {
    @list($type, $subkey) = explode("_", $key);

    if($type == "spender"){
        $spender[$subkey] = trim($value);
    }
}
if (empty($spender["name"])) {
    core_print_message(0, "Fehler: Spender-Name", "Bitte einen gültigen Namen eingeben");
    $error = true;
}
if (empty($spender["vorname"])) {
    core_print_message(0, "Fehler: Spender-Vorname", "Bitte einen gültigen vornamen eingeben");
    $error = true;
}
if(empty($_POST["checkedGeschenke"])){
    core_print_message(0, "Fehler: Geschenke", "Bitte zu spendende Geschenke auswählen");
    $error = true;
}
if(!$error){
    if(count($_POST["checkedGeschenke"]) > 0){
        foreach ($_POST["checkedGeschenke"] as $geschenkId){
            geschenk_setFreigabe($geschenkId);
        }
        $spendeId = spende_add($spender, $_POST["checkedGeschenke"]);
    }
}

if($spendeId !== false){
    log_add('spendeadd', core_securechars($_SERVER['REMOTE_ADDR']).",".serialize($_POST));
    $_SESSION["spende_add"] = array("spende_id" => $spendeId, "status_ok" => true);
//    mail_send_spendeAdd($spendeId);
}else if(count($_POST['geschenke']) == 0){
    $_SESSION["spende_add"] = array("spende_id" => $spendeId, "noPresent" => true);
}else {
    $_SESSION["spende_add"] = array("spende_id" => $spendeId, "faulty" => true);
}

header("location: portal.php?action=spenden");