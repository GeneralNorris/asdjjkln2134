<?php
/**
 * Created by PhpStorm.
 * User: lvarnholt
 * Date: 10.03.2017
 * Time: 15:01
 */
user_check_login();
$userId = $_SESSION['userID'];
$kind = array();

$kind["vorname"] = isset ($_POST["kind_vorname"]) ? trim($_POST["kind_vorname"]) : "";
$kind["name"]    = isset ($_POST["kind_name"]) ? trim($_POST["kind_name"]) : "";
$geb_j = isset($_POST["kind_geb-j"]) ? $_POST["kind_geb-j"] : "";
$geb_m = isset($_POST["kind_geb-m"]) ? $_POST["kind_geb-m"] : "";
$geb_d = isset($_POST["kind_geb-d"]) ? $_POST["kind_geb-d"] : "";
if(!empty($geb_j)&&!empty($geb_m)&&!empty($geb_d)){
    $kind["geburtstag"] = $geb_j."-".$geb_m."-".$geb_d;
}
$kind["geschlecht"] = isset($_POST["kind_geschlecht"]) ? $_POST["kind_geschlecht"] : "";
if(empty($kind["vorname"])){
        unset($kind);
}
if(!empty($kind['vorname'])  && !empty($kind['geburtstag']) && !empty($kind['geschlecht'])){

        kind_add($kind, $userId);
        if(isset($_GET["last"]) && $_GET['last'] == true){
            echo "%done";
        }else{
            echo "%success";
        }
}else{
    echo "%failed";
}