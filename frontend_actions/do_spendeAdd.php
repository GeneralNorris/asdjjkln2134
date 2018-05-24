<?php
//ACHTUNG! dieses Script läuft ohne Autentifizierung

//TODO: Etwas gegen Spammer tun! http://www.1ngo.de/web/captcha-spam.html

if(config_get("spenden_input_enabled",0) == 0){
	header("location: index.php");
}


$spendeId = false;
require_once("functions/spende.php");

$spender = array();
foreach ($_POST as $key => $value) {
    @list($type, $subkey) = explode("_", $key);

    if($type == "spender"){
        $spender[$subkey] = trim($value);
    }
}


//Nur einen Antrag erstellen, wenn Antragsteller die notwendigen felder ausgefüllt hat
if(! (empty($spender["name"])
    || empty($spender["vorname"])
    || empty($spender["plz"])
    || empty($spender["ort"])
    || empty($spender["strasse"])
    || empty($spender["email"])
    || !isset($_POST["geschenke"]))
){

    //prüfen ob Email-Adresse etwas "korrekt" aussehendes sein könnte :)
    //if (filter_var($spender["email"], FILTER_VALIDATE_EMAIL)) {

    //Nur einen Antrag erstellen, wenn mindestens ein  Kind ausgefüllt wurde.
    //reindex
    if(count($_POST["geschenke"]) > 0){
        $spendeId = spende_add($spender, $_POST["geschenke"]);
    }

    //}
}



//Umleitung auf ergebnisseite und Mailversandt
if($spendeId !== false){
	log_add('spendeadd', core_securechars($_SERVER['REMOTE_ADDR']).",".serialize($_POST));
	$_SESSION["spende_add"] = array("spende_id" => $spendeId, "status_ok" => true);
	mail_send_spendeAdd($spendeId);
}else if(count($_POST['geschenke']) == 0){
    $_SESSION["spende_add"] = array("spende_id" => $spendeId, "noPresent" => true);
}else {
	$_SESSION["spende_add"] = array("spende_id" => $spendeId, "faulty" => true);
}

header("location: index.php?action=spende_add_result");




