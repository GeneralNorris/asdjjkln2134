<?php
/**
 * Created by PhpStorm.
 * User: lvarnholt
 * Date: 05.04.2018
 * Time: 15:18
 */


$user = array();
$person= array();
$kinder = array();
$error = false;
$dateisammlungsId = false;

foreach ($_POST as $key => $value) {
    @list($type, $subkey) = explode("_", $key);

    if ($type == "user") {
        $user[$subkey] = trim($value);
    }
    if ($type == "person") {
        $person[$subkey] = trim($value);
    }
}
$checkData = user_checkRegistrationData($user, $person);

switch ($checkData){
    case 1: break;

    case 2: core_print_message(0, "Fehler: PLZ unggültig","Bitte geben Sie eine gültige PLZ ein.");
        $error = true;
        break;
    case 3: core_print_message(0, "Fehler: Kennwort ungueltig",
        "Bitte beachten Sie, dass das Kennwort aus mindestens 5 Zeichen bestehen muss und die
         Kennwort-Bestätigung dem Kennwort entsprechen muss.");
        $error = true;
        break;
    case 4: core_print_message(0,"Fehler",
        "Diese Email-Adresse wird bereits benutzt. Bitte nehmen sie eine andere");
        $error = true;
        break;
    case 5: core_print_message(0, "Fehler","Bitte überprüfen Sie ihre Daten auf Richtigkeit");
        $error = true;
        break;
}
if($_FILES['user_bescheidFile']['error'] == 0){
   $dateisammlungsId =  dateisammlung_add($_FILES,1);
}

if ($checkData == 1){
    if (filter_var($person["email"], FILTER_VALIDATE_EMAIL) && !user_mailIsUsed($person["email"]) && $dateisammlungsId) {
        $personId = person_add($person);
        $userAdd = user_add_new($personId, $user["password"], "benutzer", $dateisammlungsId);
    } else {
        if(!$dateisammlungsId){
            core_print_message(0,"Fehler","Bitte Laden sie ihren Hartz4-Bescheid in einer Bilddatei hoch");
        }
        if (user_mailIsUsed($person["email"])){
            core_print_message(0,"Fehler","Diese Email-Adresse wird bereits benutzt. Bitte nehmen sie eine andere");
        } else{
            core_print_message(0,"Fehler","Bitte geben sie eine Korrekte Email-Adresse ein");
        }
            $error = true;
    }
}

if (!$error && $personId && $userAdd) {
    $_SESSION["register"] = array("person_id" => $personId, "success" => true);
    $url = token_generate_confirmation($personId);
    $email = $person["email"];
    if($url) {
        mail_send_confirmationLink($url, $email);
    }
    mail_send_mailRegistered($personId);
    header("location: teilhabe.php?action=registration_result");
}