<?php
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin', true);

require_once("functions/config.php");
require_once("functions/kind.php");


if(isset($_POST["antraege_input_enabled"])){
	// Mails an die "Mütter" nur dann versenden, wenn die Antragsannahme vorher ABGESCHALTET war. Sonst wird bei jeder Speicherung des Formulares erneut gesendet. 
	if(config_get("antraege_input_enabled", 0) == 0){
//		mail_send_antraegeVerfuegbar();
	}
	config_set("antraege_input_enabled", 1);

} else {
	config_set("antraege_input_enabled", 0);
}

// vorabanmeldung. die Antrag Seite funktioniert, aber es existiert keine anzeige (verlinkung auf der Startseite)
if(isset($_POST["antraege_input_enabled_special"])){
	config_set("antraege_input_enabled_special", 1);

} else {
	config_set("antraege_input_enabled_special", 0);
}


if(isset($_POST["spenden_input_enabled"])){
	config_set("spenden_input_enabled", 1);
} else {
	config_set("spenden_input_enabled", 0);
}



if(isset($_GET["config_system_clean"]) 
&& isset($_SESSION["config_system_clean_guiID"])
&& isset($_POST["config_system_clean_guiID"]) 
&& $_SESSION["config_system_clean_guiID"] === $_POST["config_system_clean_guiID"]){
	config_system_clean();
}

unset($_SESSION["config_system_clean_guiID"]);
header("location: portal.php?action=config");