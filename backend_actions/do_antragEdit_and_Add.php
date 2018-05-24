<?php
//2010-07-12
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin,mitarbeiter', true);
require_once("functions/antrag.php");

if(@empty($_POST["antragId"]) || (!@empty($_POST["antragId"]) && $_POST["antragId"] == "ADD")){
	$antragId = "ADD";
	log_add('antragadd', core_securechars($_SERVER['REMOTE_ADDR']).",".serialize($_POST));
} else {
	$antragId = (int)$_POST["antragId"];
	log_add('antragedit', core_securechars($_SERVER['REMOTE_ADDR']).",".serialize($_POST));
}

 
$antragsteller = array();

foreach ($_POST as $key => $value) {

	@list($type, $subkey) = explode("_", $key);
	
	if($type == "antragsteller"){
		$antragsteller[$subkey] = trim($value);
	}
}


//Nur einen Antrag erstellen, wenn Antragsteller die notwendigen felder ausgefüllt hat //TODO: Weitere Felder bessere inhaltsprüfubng? Dann aber in funtion packen
if(! (empty($antragsteller["name"]) 
|| empty($antragsteller["vorname"])
|| empty($antragsteller["plz"])
|| empty($antragsteller["email"])
|| empty($antragsteller["ort"])
|| empty($antragsteller["strasse"]))
){
		if($antragId == "ADD"){
		    //Wenn Neuer Antrag, Email Adresse auf die des Erstellenden Benutzers setzen.
//            $userID = $_SESSION['userID'];
//            $user   = user_byID($userID);
//            $antragsteller["email"] = $user["email"];
//            $antragId = antrag_addBackend($antragsteller);
            if (filter_var($antragsteller["email"], FILTER_VALIDATE_EMAIL)) {
                antrag_addBackend($antragsteller);
            }else{
                core_print_message(0, "Unvollständig", "Bitte füllen Sie das Formular vollständig aus. Es muss eine gültige E-Mailadresse angegeben werden.");
            }
		} else {
            //prüfen ob Email-Adresse etwas "korrekt" aussehendes sein könnte :)
            if (filter_var($antragsteller["email"], FILTER_VALIDATE_EMAIL)) {
                antrag_update($antragId, $antragsteller);
            }else{
                core_print_message(0, "Unvollständig", "Bitte füllen Sie das Formular vollständig aus. Es muss eine gültige E-Mailadresse angegeben werden.");
            }
		}
} else {
	core_print_message(0, "Unvollständig", "Bitte füllen Sie das Formular vollständig aus. Es muss eine gültige E-Mailadresse angegeben werden.");
}

//Umleitung auf ergebnisseite und Mailversandt
//  if(!$error){
//  	header("location: index.php?action=antrag_add_result");
//  }






