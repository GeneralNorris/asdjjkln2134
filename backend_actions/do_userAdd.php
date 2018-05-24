<?php
require_once("functions/tokens.php");
//2010-07-12 
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin', true);

$error = false;

//check login
if (!user_check_loginsyntax($_POST['login'])) {
	core_print_message(0, "Fehler: Login ungültig", "Dieser Login-Name enthält ungültige Zeichen.");
	$error = true;
}
if (!user_check_unique($_POST['login'])) {
	core_print_message(0, "Fehler: Login ungültig", "Dieser Login-Name ist bereits vergeben.");
	$error = true;
}

//check password
if (!user_check_password($_POST['password1'], $_POST['password2'])) {
	core_print_message(0, "Fehler: Kennwort ungültig", "Bitte beachten Sie, dass das Kennwort aus mindestens 5 Zeichen bestehen muss und die Kennwort-Bestätigung dem Kennwort entsprechen muss.");
	$error = true;
}
if(user_mailIsUsed($_POST['email'])){
    core_print_message(0,"Fehler: Email-Adresse ist schon benutzt", "Bitte nehmen Sie eine andere Email-Adresse");
    $error = true;
}

if (!$error) {
	if (user_check_access('admin', false)) {
		$roles = @$_POST['roles'];
	} elseif(user_check_access('contactor_manager', false)) {//contactor_manager dürfen nur Benutzer mitder alleinigen Rolle contactor anlegen
		$roles[] = "benutzer";
	}
	$personArray = array();
	$personArray["vorname"] = $_POST["name"];
	$personArray["name"]	= $_POST["lastname"];
	$personArray["email"]	= $_POST["email"];
	$person_Id = person_add($personArray);

    if($person_Id && user_add($person_Id, $_POST['login'], $_POST['password1'], $roles)){
        $url = token_generate_confirmation($person_Id);
        $email = $personArray["email"];
        if($url) {
            mail_send_confirmationLink($url, $email);
        }else{

        }
        mail_send_mailRegistered($person_Id);
    }


	//Kennworte nicht in die LOGs
	$_POST['password1'] = "***";
	$_POST['password2'] = "***";
	log_add('useradd', serialize($_POST));

	if (!(@$_GET['ajax'] === "true")) {
		header("location: portal.php?action=user");
	}
}