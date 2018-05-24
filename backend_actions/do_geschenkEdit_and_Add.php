<?php
//2010-07-12
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin,mitarbeiter', true);
require_once("functions/geschenk.php");
require_once("functions/kind.php");

if(isset($_POST["geschenkId"]) && trim($_POST["geschenkId"]) == "ADD"){
	$geschenkId = "ADD";
	$antragId = (int) $_POST["antragId"];
	log_add('geschenkadd', core_securechars($_SERVER['REMOTE_ADDR']).",".serialize($_POST));
} else {
	$geschenkId = (int)$_POST["geschenkId"];
	log_add('geschenkedit', core_securechars($_SERVER['REMOTE_ADDR']).",".serialize($_POST));
}


if(! ( empty($_POST["kind_vorname"])
		|| empty($_POST["kind_artikel"])
		|| empty($_POST["kind_geburtstag"])
		)
){

	$kindId = array();
	foreach ($_POST as $key => $value) {
		@list($type, $subkey) = explode("_", $key);
		if($type == "kind"){
			if($subkey == "geburtstag"){
				$value = date("Y-m-d", core_timestampFromGermanDate(trim($value))); // ehm, komplizierter ging es nicht?
			}
			$kind[$subkey] = trim($value);
		}
	}
	

	if($geschenkId == "ADD"){
		
		$kind_id = kind_add($kind, $antragId);
        $geschenk_id = geschenk_add($kind["artikel"]);
        kind_insertAntragPerson($kind_id, $antragId);
        kind_insertAntragGeschenk($kind_id, $geschenk_id);
		
	} else {
		$geschenk = geschenk_getById($geschenkId); // Man man, Kind einzeln zu modellieren erscheint mir jetzt als fehler!
		
		kind_update($geschenk["Person_id"], $kind);
		
		geschenk_update($geschenkId, $kind["artikel"]);
	}

} else {
	core_print_message(0, "Unvollständig", "Bitte füllen Sie das Formular vollständig aus.");
}
