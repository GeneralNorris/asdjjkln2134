<?php
header("Access-Control-Allow-Origin:*");
/**
 * Ausgabe der Anzahl aktuell offener Geschenke zur Einbindung (K.Scholz) in weitere Webseiten per Ajax-Request.  
 */


require_once("conf.php");
require_once("functions/db.php");
require_once("functions/core.php");

core_init();

if(config_get("spenden_input_enabled",0) == 0){
	echo '---';
} else {
	echo geschenk_getCountGeschenkeUnbezahlt();
}