<?php 
require_once("conf.php");
require_once("functions/db.php");
require_once("functions/core.php");
require_once("functions/geo.php");
//require_once("/functions/user.php");
require_once("functions/kind.php");
//require_once("../functions/db.php");
require_once("functions/tokens.php");
//echo "<pre>";
//print_r($_POST);
//exit;
core_init();


$user = array();
$person= array();
//$kinder = array();
$error = false;
foreach ($_POST as $key => $value) {
        @list($type, $subkey) = explode("_", $key);
        if ($type == "user") {
            $user[$subkey] = trim($value);
        }
        if ($type == "person") {
            $person[$subkey] = trim($value);
        }
//        if ($type == "kind") {
//            for ($i = 0; $i < count($value); $i++) {//$value ist ein array mit den Werten pro kind
//                $kinder[$i][$subkey] = trim($value[$i]);
//            }
//        }
    }
    //Geburtstag in Timestamp überführen, leere Elemente entfernen
//    $kinderCount = count($kinder);
//    for ($i = 0; $i < $kinderCount; $i++) {
//        if (empty($kinder[$i]["vorname"])) { //|| empty($kinder[$i]["artikel"])){
//            unset($kinder[$i]);
//        } else {
//            $kinder[$i]["geburtstag"] = $kinder[$i]["geb-j"] . "-" . $kinder[$i]["geb-m"] . "-" . $kinder[$i]["geb-d"];
//            unset($kinder[$i]["geb-d"]);
//            unset($kinder[$i]["geb-m"]);
//            unset($kinder[$i]["geb-j"]);
//        }
//    }

    function checkUserData($user, $person)
    {

        if (!(empty($person["name"])
            || empty($person["vorname"])
            || empty($person["email"])
            || empty($person["plz"])
            || empty($person["ort"])
            || empty($person["strasse"]))
        ) {
            if (user_check_unique($user["login"]) && user_check_password($user["password"], $user["passwordCheck"]) && user_check_loginsyntax($user["login"])
                && geo_check_deutsche_plz(intval($person["plz"])) && !user_mailIsUsed($person['email'])) {
                return true;
            } else {
                if (!geo_check_deutsche_plz(intval($person["plz"]))) {
                    echo "%plz_wrong";
                }
                if (!user_check_unique($user["login"])) {
                    echo "%login_used";
                }
                if (!user_check_loginsyntax($user["login"])) {
                    echo "%login_wrong";
                }
                if (!user_check_password($user["password"], $user["passwordCheck"])) {
                    echo "%password_wrong";
                }
                if(user_mailIsUsed($person["email"])){
                    echo "%email_used";
                }
                return false;//Username Vergeben o. Passwörter stimmen nicht überein
            }
        } else {
            echo "%data_empty";
            return false;//Nicht alle Daten Ausgefüllt
        }
    }
    if (checkUserData($user, $person)) {
        //prüfen ob Email-Adresse etwas "korrekt" aussehendes sein könnte :)
        if (filter_var($person["email"], FILTER_VALIDATE_EMAIL) && !user_mailIsUsed($person["email"])) {
//            $kinder = array_values($kinder);
//             if(count($kinder) <= 0) {
//                 echo "%child_empty";
//                $error = true;
//            }
        } else {
            if(user_mailIsUsed($person["email"])){
                echo "%email_used";
            }else{
                echo "%email_wrong";
            }
            $error = true;
        }
    }else{
        $error = true;
    }
    if(!$error){
        echo "%success";
    }