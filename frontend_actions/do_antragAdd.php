<?php
user_check_login();
//ACHTUNG! dieses Script läuft ohne Autentifizierung


//TODO: Etwas gegen Spammer tun! http://www.1ngo.de/web/captcha-spam.html

//FIXME: BEIM SCHREIBEN DER GESCHENKE AUF DB-EBENE PRÜFEN, OB ein ARTIKEL nicht zwischenzeitlich bereits VERBRAUCHT IST (Insert mir WHERE klausel und reaktion, wenn nichts eingefügt werden konnte evtl. Kinder einzeln angeben uns speichern, dann ist es für den benutzer leichter... )
//TODO: hier TRANSACTIONs verwenden: wenn antrag_add false zurück gibt -> ROLLBACK, aber voher prüfen, ob antrag_add das auch zuverlässig macht.


if(config_get("antraege_input_enabled",0) == 0 && config_get("antraege_input_enabled_special",0) == 0){
	header("location: index.php");
}

require_once("functions/antrag.php");
require_once("functions/kind.php");

$antragId = false;
$freitext = ""; //TODO: Freitext auch im Frontend schon ertmöglichen?
log_add('antragadd', core_securechars($_SERVER['REMOTE_ADDR']).",".serialize($_POST));


$antragsteller = array();
$kinder = array();
//echo "<pre>";
//print_r($_POST);
//exit;

foreach ($_POST as $key => $value) {

	@list($type, $subkey) = explode("_", $key);
	if($type == "kind"){
		for ($i = 0; $i < count($value); $i++) {//$value ist ein array mit den Werten pro kind
			$kinder[$i][$subkey] = trim($value[$i]);
		}
	}
}


$kinderCount = count($kinder);
$kinderArray = kind_isChild($_SESSION["userID"]);
for ($i = 0; $i < $kinderCount; $i++) {
	if(!empty($kinder[$i]["id"])){
		$kinder[$i]["vorname"] = trim(kind_getVorname($kinder[$i]["id"]));
	}
    if (empty($kinder[$i]["artikel"]) || empty($kinder[$i]["id"]) || !in_array($kinder[$i]["id"], $kinderArray) || kind_hatGeschenk($kinder[$i]["id"] )) {
        unset($kinder[$i]);
    }
}
    $kinder = array_values($kinder);

    if (count($kinder) > 0) {
        $antragId = antrag_add($_SESSION['userID'], $kinder, $freitext, $_FILES);
    }


//Umleitung auf ergebnisseite und Mailversandt
if($antragId !== false){
	$_SESSION["antrag_add"] = array("antrag_id" => $antragId, "status_ok" => true);
	mail_send_antragAdd($antragId);
} else {
	$_SESSION["antrag_add"] = array("antrag_id" => $antragId, "status_ok" => false);
}

header("location: index.php?action=antrag_add_result");