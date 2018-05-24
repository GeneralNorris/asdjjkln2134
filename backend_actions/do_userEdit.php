<?php
//2010-07-12
require_once("functions/tokens.php");
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};

$userID = (int) $_POST['userID'];
$password = $_POST['password'];
$user = user_byID($userID);
$email = $user['email'];
$error = false;

if (user_check_editpermission($userID)) {
	//update password
	if ($password['newPw1'] != "") {
		if (!user_update_password($password['newPw1'], $password['newPw2'], $userID)) {
			core_print_message(0, "Fehler: Kennwort ungültig", "Bitte beachten Sie, dass das Kennwort aus mindestens 5 Zeichen bestehen muss und die Kennwort-Bestätigung dem Kennwort entsprechen muss.");
			$error = true;
		}
	}
	//update roles -> dürfen nur Admins
	if (user_check_access('admin', false)) {
		if (isset($_POST['roles'])) {
			user_update_roles($userID, $_POST['roles']);
		}
		if(isset($_POST['maxChildren'])){
			user_update_maxChildren($userID, 1);
		}else if (!isset($_POST['maxChildren'])){
			user_update_maxChildren($userID, 0);
		}
		if(isset($_POST['activated'])){
			if(!user_isActivated($userID)) {
                user_activate($userID, 1);
                if(user_isMailConfirmed($userID)) {
                    mail_send_accountActivated($userID);
                }
                if(config_get("antraege_input_enabled",0) > 0){
                    //mail_send_geschenkVerfuegbar($_POST['email']);
                }
            }else{

            }
		}else if (!isset($_POST['activated'])) {
            user_activate($userID, 0);
//			mail_send_accountDeactivated($userID);
        }
//		if (isset($_POST['kundennr'])){
//			user_update_kundennr($userID,$_POST['kundennr']);
//		}
	}
	//update faultylogins -> dürfen nur admin und contactor_manager
	if (user_check_access('admin,contactor_manager', false)) {
		if (isset($_POST['faultylogins'])) {
			user_update_faultylogins($userID, $_POST['faultylogins']);
		}
		
	}
    if($_POST['email'] != $email) {
        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) || user_mailIsUsed($_POST["email"])) {
            core_print_message(0, "Fehler: Email ungültig", "Diese Email-Adresse wird entweder schon verwendet oder ist ungültig.");
            $error = true;
        }else{
            user_resetEmailValidation($userID);
            $url = token_generate_confirmation($userID);
            if($url) {
                mail_send_confirmationLink($url, $_POST['email']);
            }
        }
    }
    if(!$error) {
        //Kontaktdaten aktualisieren
        user_update_contact($userID, $_POST['vorname'], $_POST['name'], $_POST['email'], $_POST['strasse'], $_POST['ort'], $_POST['plz'], $_POST['tel']);
//        mail_send_userEdit($userID);

        if(isset($_POST['emailValidation'])){
            $url = token_generate_confirmation($userID);
            if($url) {
                mail_send_confirmationLink($url, $_POST['email']);
            }
        }

        //Keine Kennworte in die LOGs
        $_POST['password'] = "***";
        $password['newPw1'] = "***";
        $password['newPw2'] = "***";
        log_add("useredit", $userID . "," . serialize($_POST));
    }
}

if (!(@$_GET['ajax'] === "true")) {
	if (!$error) {
		if ($userID != $_SESSION['userID']) {//Wenn ungleich, wurden nicht die eigenen Einstellungen bearbeitet (war also Rolle:admin/contactor_manager), also zurück zur Benutzerübersicht.
			header("location: portal.php?action=user");
		} else {
			header("location: portal.php?action=userEdit");
		}
	}
}