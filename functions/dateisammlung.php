<?php
require_once("functions/datei.php");

function dateisammlung_add(array $dateien, $vertraulich = 1){
	$resultId = false;
	
	$dbresult = db_query("INSERT INTO dateisammlung () VALUES ()", false);
	if($dbresult !== false){
		$resultId = db_autoinc_id();
		foreach ($dateien as $datei) {
			datei_add($resultId, $datei, $vertraulich);
		}
	}

	return $resultId;
	
}

function dateisammlung_update($dateisammlungId, array $dateien, $vertraulich = 1){
	foreach ($dateien as $key => $datei) {
		if($datei['error'] == 0){
			@list($trash, $id) = explode("_", $key);
			if($id == "ADD"){
				datei_add($dateisammlungId, $datei, $vertraulich);
			} else {
				datei_update($dateisammlungId, $id, $datei, $vertraulich);
			}
		}
	}
}

function dateisammlung_getById($id){
	return datei_getByDateisammlungId($id);
}

function dateisammlung_deleteById($id){
	echo "DELETE FROM dateisammlung WHERE id = ".(int)$id;
	
	$dateien = dateisammlung_getById($id);
	foreach ($dateien as $datei) {
		datei_delete($datei["id"]);
	}
	
	db_query("DELETE FROM dateisammlung WHERE id = ".(int)$id, false);
}
