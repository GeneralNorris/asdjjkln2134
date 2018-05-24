<?php
function person_add($personArr){
	$validFields = person_getValidFields();
	$result = false;

	$keysStrings = array();
	$values 	 = array();
	foreach ($personArr as $key => $value) {
		//Nur die elemente aus dem Array übernehmen, die auch personenbezogen sind.
		if(in_array($key, $validFields)){
			$keysStrings[] = "`".db_secure_chars($key)."`";
			$values[]      = "'".db_secure_chars($value)."'";
		}
	}
		
	if(count($values)>0){
		$dbresult = db_query("INSERT INTO person (". implode(",", $keysStrings) .") VALUES (". implode(",", $values) ."); ",false);
		if($dbresult){
			$result = db_autoinc_id();
		}else{
			$result = false;
		}
	}
	
	return $result;
}

function person_update($personId, array $personArr){
	$validFields = person_getValidFields();
	$values 	 = array();

	foreach ($personArr as $key => $value) {
		//Nur die elemente aus dem Array übernehmen, die auch personenbezogen sind.
		if(in_array($key, $validFields)){
			$values[]      = "`".db_secure_chars($key)."` = '".db_secure_chars($value)."'";
		}
	}
	
	if(count($values)>0){
		$dbresult = db_query("UPDATE person SET ". implode(",", $values) ." WHERE id = ".(int) $personId."; ");
	}
}

function person_getValidFields(){
	return array("firma","geburtstag", "geschlecht", "vorname", "name", "strasse", "plz", "ort", "tel", "email");
}
