<?php
user_check_login();
user_check_access('benutzer', true);
if(!(defined('CONF_APPNAME'))) {header("location: ../teilhabe.php");exit();};

$userID = (int) $_POST['userID'];
$password = $_POST['password'];
$user = user_byID($userID);
$email = $user['email'];
$error = false;
$success = false;
$emailReset = false;

if(user_pwCheck($userID, $password['oldPw'])&& user_check_editpermission($userID)){
	
	//update password
	if ($password['newPw1'] != "") {
		if (!user_update_password($password['newPw1'], $password['newPw2'], $userID)) {
			core_print_message(0, "Fehler:Neues Kennwort ungültig", "Bitte beachten Sie, dass das Kennwort aus mindestens 5 Zeichen bestehen und die Kennwort-Bestätigung dem Kennwort entsprechen muss.");
			$error = true;
		}else{
            $_SESSION["passwordChange"] = array("success" => true);
            $success = true;
        }
	}
    if($_POST['email'] != $email) {
        if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) || user_mailIsUsed($_POST["email"])) {
            core_print_message(0, "Fehler: Email ungültig", "Diese Email-Adresse wird entweder schon verwendet oder ist ungültig.");
            $error = true;
        }else{
            user_resetEmailValidation($userID);
            $_SESSION["emailChange"] = array("success" => true);
            $emailReset = true;
            $url = token_generate_confirmation($userID);
            if($url) {
//                mail_send_confirmationLink($url, $_POST['email']);
            }
        }
    }
	
	//Kontaktdaten aktualisieren
    if(!$error) {
        user_update_contact($userID, $_POST['vorname'], $_POST['name'], $_POST['email'], $_POST['strasse'], $_POST['ort'], $_POST['plz'], $_POST['tel']);
//        mail_send_userEdit($userID);


        //Keine Kennworte in die LOGs
        $_POST['password'] = "***";
        $password['newPw1'] = "***";
        $password['newPw2'] = "***";
        log_add("useredit", $userID . "," . serialize($_POST));
    }
}else{
	core_print_message(0, "Fehler: Altes Kennwort ungültig", "Bitte geben sie ihr altes Kennwort korrekt ein");
	$error = true;
}

if (!(@$_GET['ajax'] === "true")) {
	if (!$error) {
	    if($success == true){
	        header("location: teilhabe.php?action=login");
            exit;
        }if($emailReset == true){
            $_SESSION['login'] = '';
            $_SESSION['password'] = '';
            $_SESSION['ip'] = '';
            header("location: teilhabe.php?action=login");
            exit;
		} else {
			$_SESSION["user_edit"] = true;
			header("location: 
			.php?action=userEdit");
			echo "Daten Erfolgreich geändert!";
			//Hier Mail versenden
//            mail_send_userEdit($userID);
		}
	}
}