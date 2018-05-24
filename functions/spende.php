<?php
require_once("functions/person.php");
require_once("functions/geschenk.php");
require_once("functions/antrag.php");
require_once("functions/vorgang.php");

define('SPENDE_STATUS_INPRUEFUNG', 1);
define('SPENDE_STATUS_BEZAHLT', 2);

function spende_add(array $person_spender, array $geschenke){

	db_query("START TRANSACTION");
	
	//Prüfsumme, ob alle Geschenke gespendet werden konnten, oder ob welche bereits "weggeschnappt" waren.
	$gespendeteGeschenke = 0;
	
	$resultId = false;
	$personId = person_add($person_spender);
	if($personId !== false){
		$vorgangId = vorgang_add();
		if($vorgangId !== false){
			$dbresult = db_query("INSERT INTO spende (`Person_id`, `Vorgang_id`, `eingegangen_am`  ) VALUES (".(int)$personId.", ".(int)$vorgangId.", NOW())", false);
			if($dbresult !== false){
				$resultId = $personId; // das ist korrekt
				foreach ($geschenke as $geschenkId) {
					if(geschenk_setSpender((int)$geschenkId, $personId)){
						$gespendeteGeschenke ++;
					}
				}
			}
		}
	}
	
	if($gespendeteGeschenke != count($geschenke)){
		db_query("ROLLBACK");
		return false;
	} else {
		db_query("COMMIT");
		return $resultId;
	}
}

function spende_getArr(){
	$result = array();
	$dbresult = db_query("
			SELECT
				*,
				(SELECT SUM(preis) FROM artikel A, geschenk G WHERE A.id=G.Artikel_id AND G.Spende_Person_id = S.Person_id) AS summe
			FROM
				spende S, person P
			WHERE
				S.Person_id = P.id
			ORDER BY S.Vorgang_id DESC");
	
	while($rs = db_fetch_array($dbresult, MYSQL_ASSOC)){
		$result[] = $rs;
	}
	return $result;
	
}
function spende_getDetailArr($spende_vorgang){
    $result = array();
    $dbresult = db_query("
                
			                    SELECT PKind.vorname as kind_vorname, 
			                    Art.bezeichnung as artikel_bezeichnung, 
			                    An.Vorgang_id as antrag_vorgang_id					
						  		FROM spende S 
						  		JOIN geschenk G ON G.Spende_Person_id = S.Person_id
								JOIN kind K ON G.id = K.Geschenk_id
								JOIN person PKind ON K.Person_id = PKind.id
								JOIN antrag An ON An.Person_id = K.Antrag_Person_id
								JOIN artikel Art ON Art.id = G.Artikel_id
								WHERE S.Vorgang_id=".(int)$spende_vorgang);
    while($rs = db_fetch_array($dbresult, MYSQL_ASSOC)){
        $result[] = $rs;
    }
    return $result;
}

function spende_getById($spendeId){
	$dbresult = db_query("
			SELECT 
				*,
				(SELECT SUM(preis) FROM artikel A,geschenk G WHERE A.id=G.Artikel_id AND G.Spende_Person_id = ".(int) $spendeId.") AS summe
			FROM 
				spende S, person P
			WHERE 
				S.Person_id = P.id 
			AND 
				S.Person_id = ".(int)$spendeId);
	return db_fetch_array($dbresult);
}

function spende_freigabe($spendeId){
	db_query("UPDATE spende SET zahlung_bestaetigt_von = ".(int) $_SESSION['userID'].", zahlung_bestaetigt_am=NOW() WHERE Person_id = ".(int)$spendeId." LIMIT 1");
	$geschenke = geschenk_getArrBySpendeId($spendeId);
	
	foreach ($geschenke as $geschenk) {
		mail_send_spendeEinesGeschenksBestaetigt($geschenk);
	}
}

function spende_delete($spendeId){
	db_query("UPDATE geschenk SET Spende_Person_id = NULL WHERE Spende_Person_id = ".(int)$spendeId);
	db_query("DELETE FROM spende WHERE Person_id = ".(int)$spendeId." LIMIT 1");

}

function spende_getSummeArr(){
	$result = array();

	$dbresult = db_query("
		SELECT
			IFNULL(SUM(A.preis), 0) AS summe
		FROM
			geschenk G,
			spende S,
			artikel A
		WHERE
			S.Person_id = G.Spende_Person_id
		AND
			G.Artikel_id = A.id
	");
	
	if($rs = db_fetch_array($dbresult,MYSQL_ASSOC)){
		$result["summe_total"] = $rs["summe"];
	}
	
	$dbresult = db_query("
		SELECT
			 IFNULL(SUM(A.preis), 0) AS summe
		FROM
			geschenk G,
			spende S,
			artikel A
		WHERE
			S.Person_id = G.Spende_Person_id
		AND
			G.Artikel_id = A.id
		AND 
			S.zahlung_bestaetigt_von IS NOT NULL
	
	");
	
	if($rs = db_fetch_array($dbresult,MYSQL_ASSOC)){
		$result["summe_bestätigt"] = $rs["summe"];
	}
	
	$dbresult = db_query("
		SELECT
			IFNULL(SUM(A.preis), 0) AS summe
		FROM
			geschenk G,
			spende S,
			artikel A
		WHERE
			S.Person_id = G.Spende_Person_id
		AND
			G.Artikel_id = A.id
		AND
			S.zahlung_bestaetigt_von IS NULL
	
	");
	if($rs = db_fetch_array($dbresult,MYSQL_ASSOC)){
		$result["summe_nicht_bestätigt"] = $rs["summe"];
	}
	
	return $result;

}