<?php
//require_once("/functions/user.php");

require_once("functions/kind.php");
//require_once("../functions/db.php");

require_once("functions/tokens.php");
//echo "<pre>";
//print_r($_POST);
//exit;

$user = array();
$person= array();
$kinder = array();
$error = false;


if (isset($_POST["token"]) && preg_match('/^[0-9A-F]{40}$/i', $_POST["token"])) {
	$token = $_POST["token"];
}
else {
    core_print_message(0, "Fehler: Token ungültig", "Dieses Token ist ungültig");
    $error = true;
}
if(token_verify($token)) {
	foreach ($_POST as $key => $value) {

		@list($type, $subkey) = explode("_", $key);

		if ($type == "user") {
			$user[$subkey] = trim($value);
		}
		if ($type == "person") {
			$person[$subkey] = trim($value);
		}
//		if ($type == "kind") {
//			for ($i = 0; $i < count($value); $i++) {//$value ist ein array mit den Werten pro kind
//				$kinder[$i][$subkey] = trim($value[$i]);
//			}
//		}
	}

	//Geburtstag in Timestamp überführen, leere Elemente entfernen
//	$kinderCount = count($kinder);
//	for ($i = 0; $i < $kinderCount; $i++) {
//		if (empty($kinder[$i]["vorname"])) { //|| empty($kinder[$i]["artikel"])){
//			unset($kinder[$i]);
//		} else {
//			$kinder[$i]["geburtstag"] = $kinder[$i]["geb-j"] . "-" . $kinder[$i]["geb-m"] . "-" . $kinder[$i]["geb-d"];
//			unset($kinder[$i]["geb-d"]);
//			unset($kinder[$i]["geb-m"]);
//			unset($kinder[$i]["geb-j"]);
//		}
//	}

	function checkUserData($user, $person, $token)
	{
		if (user_check_unique($user["login"]) && user_check_password($user["password"], $user["passwordCheck"]) && user_check_loginsyntax($user["login"])) {
			if (!(empty($person["name"])
				|| empty($person["vorname"])
				|| empty($person["email"])
				|| empty($person["plz"])
				|| empty($person["ort"])
				|| empty($person["strasse"]))
			) {
				return true;
			} else {
				return false;//Nicht alle Daten Ausgefüllt
			}
		} else {
			if (!user_check_unique($user["login"])) {
				core_print_message(0, "Fehler: Login ungueltig", "Dieser Login-Name ist bereits vergeben.");
				token_setFree($token);
			}
			if (!user_check_loginsyntax($user["login"])) {
				core_print_message(0, "Fehler: Login ungueltig", "Dieser Login-Name enthält ungueltige Zeichen.");
				token_setFree($token);


			}
			if (!user_check_password($user["password"], $user["passwordCheck"])) {
				core_print_message(0, "Fehler: Kennwort ungueltig", "Bitte beachten Sie, dass das Kennwort aus mindestens 5 Zeichen bestehen muss und die Kennwort-Bestätigung dem Kennwort entsprechen muss.");
				token_setFree($token);
			}

			return false;//Username Vergeben o. Passwörter stimmen nicht überein
		}
	}


	//Nur einen Antrag erstellen, wenn Antragsteller die notwendigen felder ausgefüllt hat //TODO: Weitere Felder bessere inhaltsprüfubng? Dann aber in funtion packen
	if (checkUserData($user, $person, $token)) {

		//prüfen ob Email-Adresse etwas "korrekt" aussehendes sein könnte :)
		if (filter_var($person["email"], FILTER_VALIDATE_EMAIL) && !user_mailIsUsed($person["email"])) {


                $personId = person_add($person);
				$userAdd = user_add($personId, $user["login"], $user["password"], "benutzer");

//			$kinder = array_values($kinder);
//
//			if (count($kinder) > 0) {
//				$personId = person_add($person);
//				$userAdd = user_add($personId, $user["login"], $user["password"], "benutzer");
//				foreach ($kinder as $kind) {
//					kind_add($kind, $personId);
//
//				}
//
//			} else {
//
//                core_print_message(0,"Fehler","Bitte Fügen sie mindestens 1 Kind hinzu");
//                token_setFree($token);
//                $error = true;
//
//			}

		} else {
		    if(user_mailIsUsed($person["email"])){
                core_print_message(0,"Fehler","Diese Email-Adresse wird bereits benutzt. Bitte nehmen sie eine andere");
                token_setFree($token);
            }else{
                core_print_message(0,"Fehler","Bitte geben sie eine Korrekte Email-Adresse ein");
                token_setFree($token);
            }
            $error = true;

		}
	}else{
	    $error = true;
    }

}else{
    core_print_message(0, "Fehler: Token ungültig", "Dieses Token ist ungültig");
    $error = true;
}


//Umleitung auf ergebnisseite und Mailversandt
if (!$error && $personId && $userAdd) {
    $_SESSION["register"] = array("person_id" => $personId, "success" => true);
    $url = token_generate_confirmation($personId);
    token_setUser($token, $personId);
    $email = $person["email"];
    if($url) {
        mail_send_confirmationLink($url, $email);
    }else{
//            print "keine Bestätigunsmail";
    }
    mail_send_mailRegistered($personId);
    //Hier Mail versenden
    header("location: index.php?action=registration_result");
}