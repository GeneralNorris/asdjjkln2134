<?php
/**
 * Created by PhpStorm.
 * User: lvarnholt
 * Date: 21.09.2017
 * Time: 17:39
 */

if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin,contactor_manager', true);

$artikelId  = (int) $_GET['deleteArtikelID'];
$userID     = $_SESSION['userID'];
//editPermission, Konfiguation prüfen (alles Aus) , keine Anträge, keine Spenden
if(user_check_editpermission($userID)) {
    if ( config_get("antraege_input_enabled", 0) == 0 && config_get("spenden_input_enabled", 0) == 0){
        $antragArr = antrag_getArr();
        $spendenArr = spende_getArr();
        if(empty($antragArr) && empty($spendenArr)){
            if(artikel_delete($artikelId)){
                $_SESSION['artikelDelete'] = "success";
            }else{
                $_SESSION['artikelDelete'] = "false";
            }
        }else{
            $_SESSION['artikelDelete'] = "antrag_spende";
        }
    }else{
        $_SESSION['artikelDelete'] = "config";
    }
}
header("location: portal.php?action=artikel");
