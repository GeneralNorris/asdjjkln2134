<?php
require_once("functions/antrag.php");
require_once("functions/dateisammlung.php");


function config_init(){
	$configResult = db_query("SELECT `configkey`, `value` FROM sys_config");
	
	$_SESSION["sys_config"] = array();
	while ($rs = db_fetch_array($configResult, 1)) {
		$_SESSION["sys_config"][$rs["configkey"]] = $rs["value"];
	}
}

function config_get($key, $defaultValue){
	if(isset($_SESSION["sys_config"]) && isset($_SESSION["sys_config"][$key])){
		return $_SESSION["sys_config"][$key];
		echo "#test";
	} else {
		return $defaultValue;
	}
}

function config_set($key, $value){
	
	db_query("INSERT INTO sys_config (`configkey`, `value`) VALUES ('".db_secure_chars($key)."', '".db_secure_chars($value)."') 
			 ON DUPLICATE KEY UPDATE `value` = '".db_secure_chars($value)."'" );
	config_init();
}

function config_system_clean(){

	//FALLS ACCOUNTS NICHT MIT GELÖSCHT WERDEN SOLLEN:
//	db_query("UPDATE kind SET Geschenk_id = NULL");
//    db_query("DELETE FROM antrag");
//    db_query("DELETE FROM geschenk");
//    db_query("DELETE FROM spende");


//	db_query("DELETE FROM `engelbaum_geschenke`.`antrag`", false);
//    db_query("DELETE FROM `engelbaum_geschenke`.`geschenk`", false);
//    db_query("DELETE FROM `engelbaum_geschenke`.`spende`", false);
//    db_query("DELETE FROM `engelbaum_geschenke`.`sys_log`", false);
    $idArr = array();
    $antraege = antrag_getArr();
    $query = db_query("SELECT Person_id FROM account WHERE roles = 'admin'");
    while ($rs = db_fetch_array($query)){
        array_push($idArr,$rs['Person_id']);
    }
    $adminIds = implode(',',$idArr);
    $adminIds = "(".$adminIds.")";


    db_query("DELETE FROM kind");
    db_query("DELETE FROM geschenk");
    db_query("DELETE FROM antrag");
    db_query("DELETE FROM spende");
//    db_query("DELETE FROM account WHERE Person_id NOT IN('".$adminIds."')");
    db_query("DELETE FROM account WHERE Person_id > 4");
    db_query("DELETE FROM token");
//    db_query("DELETE FROM person WHERE id NOT IN ('".$adminIds."')");
    db_query("DELETE FROM person WHERE id > 4"); //TODO: andere Abfrage als id>4!!!

    foreach ($antraege as $antrag) {
		dateisammlung_deleteById($antrag["Dateisammlung_id"]);
	}
}

