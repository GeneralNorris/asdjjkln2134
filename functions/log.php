<?php
/**
 * Gibt ein array mit den Jahreszahlen zurück für die Einträge im der Logging-Tabelle vorhanden sind
 * @return array[int]
 */
function log_get_years(){
	$years = array();
	$sql = "
		SELECT YEAR(date) AS year
		FROM
			sys_log
		GROUP BY YEAR(date)
	";
	$res = db_query($sql);
	while ($row = db_fetch_array($res)) {
		$years[] = $row['year'];
	}
	return $years;
}

/**
 * Gibt alle userIDs als array zurück, die in der Logging-Tabelle bereits bekannt sind
 * @return array[int]
 */
function log_get_userids(){
	$userids = array();
	$sql = "
		SELECT userid
		FROM
			sys_log
		WHERE
			userid > 0
		GROUP BY userid
	";
	$res = db_query($sql);
	while ($row = db_fetch_array($res)) {
		$userids[] = $row['userid'];
	}
	return $userids;
}

/**
 * Gibt ein Mehrdimensionales Array zurück, welches alle derzeit eingerichteten "log_actions" beschreibt.
 * @return array [string=>[id=>string,helptext=>string,helptextshort=>string,datastructure=>string]
 */
function log_get_actions(){
	static $actions = array();
	if(count($actions == 0)){
		$res = db_query("SELECT * FROM sys_log_action");

		while ($row = db_fetch_array($res)) {
			$actions[$row['id']] = array(	"id"=>$row["id"],
											"helptext"=>$row["helptext"],
											"helptextshort"=>$row["helptextshort"],
											"datastructure"=>$row["datastructure"]
										);
		}
	}
	return $actions;
}

/**
 * Erstellt einen neuen Eintrag in der Logging-Tabelle
 * @param String $actionKey ID der log_action (ID aus Tabelle sys_log_action)
 * @param String $data Enthält die zu speichernden Daten: Fehlermeldung oder serialisiertes Objekt, usw.
 * @param int $contactID ID des betreffenden Kontaktes, wenn sinnvoll/verfügbar
 * @param int $statusID ID des status des betr. Kontaktes, wenn sinvoll/verfügbar
 */
function log_add($actionKey, $data='', $contactID = -1, $statusID = -1) {
	$userID = ((isset($_SESSION['userID']))? $_SESSION['userID'] : -1);

	if($contactID == -1){
		$contactID = "NULL";
	}else{
		$contactID = (int) $contactID;
	}

	if($statusID == -1){
		$statusID = "NULL";
	}else{
		$statusID = (int) $statusID;
	}

	db_query(
		"
			INSERT INTO
				sys_log
			SET
				userid = ".(int) $userID.",
				log_actionid = '".db_secure_chars($actionKey)."',
				contactid = $contactID,
				statusid = $statusID,
				data = '".db_secure_chars($data)."',
				date = NOW()
		"
	);
}