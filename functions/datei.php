<?php
require_once("functions/user.php");

function datei_add($dateisammlungId, $file, $vertraulich = 1){
	$allowedFileTypes = datei_getAllowedFiletypes();

	if ($file["error"] > 0){
		if(!$file["error"] == UPLOAD_ERR_NO_FILE){
			trigger_error("Server konnte Datei nicht entgegen nehmen. Error: ".$file["error"], E_USER_NOTICE);
		}
		return false;
	}
	if ($file["size"] > CONF_FILEUPLOAD_MAXSIZE){
		trigger_error("Datei überschreitet maximale groeße.", E_USER_NOTICE);
		return false;
	}
	if(!is_dir(CONF_FILEUPLOAD_DESTPATH)){
		trigger_error("Verzeichnis für Datei-Upload existiert nicht", E_USER_ERROR);
		return false;
	}
		
	$path_parts = pathinfo($file['name']);
	$file["filename"] = core_securechars($path_parts['filename']);
	$file["extension"] = core_securechars($path_parts['extension']);
	unset($file["name"]);
	unset($path_parts);

	//so wissen wir den tatsächlichen! mime-type des Bildes!//FIXME: damit legen wir uns aber ungeachtet von datei_getAllowedFiletypes auf Images fest!
	$fileInfo = getimagesize($file["tmp_name"]);
	//Akzeptieren wir die Dateiendung und den Mimetype?
	if(in_array($fileInfo['mime'], $allowedFileTypes) && array_key_exists($file["extension"], $allowedFileTypes)){
		$file["type"] = $fileInfo['mime']; //Mimetype überschreiben
	} else {
		trigger_error("Datei hatte unbekannte/nicht zugelassene Erweiterung oder MIME-Type.", E_USER_NOTICE);
		return false;
	}
	
	
	//Der neue Name für die Datei PIRMARY-KEY!
	$uid = uniqid((rand(1000,9999)),true);
	//Datei ins Zielverzeichnis verschieben
	$fileMoveResult = move_uploaded_file($file["tmp_name"],	CONF_FILEUPLOAD_DESTPATH.$uid);
	
	//Datei in die Datenbank übernehmen
	if($fileMoveResult !== false){
		db_query("
			INSERT INTO
			datei (`Dateisammlung_id`, `name`, `erweiterung`,`mimetype`, `uid`, `size`, `vertraulich`)
			VALUES ( ".(int)$dateisammlungId.",
					'".db_secure_chars($file["filename"])."',
					'".db_secure_chars($file["extension"])."',
					'".db_secure_chars($file["type"])."',
					'".db_secure_chars($uid)."',
					'".db_secure_chars($file["size"])."',
					 ".(int)$vertraulich.");
	 ");
	}
}

function datei_update($dateisammlungId, $dateiId, $file, $vertraulich = 1){
	datei_delete($dateiId);
	datei_add($dateisammlungId, $file, $vertraulich);
}

function datei_delete($id){
	$datei = datei_getById($id);
	
	$delsucc = @unlink(CONF_FILEUPLOAD_DESTPATH.$datei['uid']);
	if($delsucc){
		db_query("DELETE FROM datei WHERE id = ".(int)$id, false);
	}
	return $delsucc;
}

function datei_getAllowedFiletypes(){
	$result = array();
	$values = explode(",", CONF_FILEUPLOAD_VALID_FILETYPES);
	
	foreach ($values as $value) {
		list($ext, $mimetype) = explode("|", $value);
		$result[$ext] = $mimetype;	
	}
	
	return $result;
}

function datei_getById($id){
	$result = false;
	$showVertraulich = user_check_access('admin, mitarbeiter', false);
	$onlyPublicSQL = "";
	if(!$showVertraulich){
		$onlyPublicSQL = " AND vertraulich = 0";
	}

	$dbresult = db_query("SELECT * FROM datei WHERE id = ".(int)$id." ".$onlyPublicSQL, false);
	if($dbresult !== false){
		$result = db_fetch_array($dbresult);
	}
	
	return $result;
}

function datei_getByDateisammlungId($dateisammlungId){
	$result = array();
	
	$showVertraulich = user_check_access('admin, mitarbeiter', false);
	$onlyPublicSQL = "";
	if(! $showVertraulich){
		$onlyPublicSQL = " AND vertraulich = 0";
	}
	
	$dbresult = db_query("SELECT * FROM datei WHERE Dateisammlung_id = ".(int)$dateisammlungId." ".$onlyPublicSQL);
	
	if($dbresult !== false){
		while($rs = db_fetch_array($dbresult)){
			$result[] = $rs;
		}
	}
	return $result;
}
