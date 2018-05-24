<?php
require_once("functions/person.php");
require_once("functions/kind.php");
require_once("functions/dateisammlung.php");
require_once("functions/vorgang.php");
require_once("functions/geschenk.php");


define('ANTRAG_STATUS_INPRUEFUNG', 1); //hint: Wert wird auch zur Sortierung verwendet
define('ANTRAG_STATUS_WARTEND', 2);
define('ANTRAG_STATUS_GESPENDET', 3);
define('ANTRAG_STATUS_GESPENDET_UND_BEZAHLT',5);


/**
 *  
 * Erstellt einen Antrag
 * 
 * @param array $person_antragsteller
 * @param array $kinder
 * @param unknown $freitext
 * @param array $dateien
 * @return Ambigous <boolean, number>
 */
function antrag_add($personId, array $kinder = array(), $freitext="", array $dateien=array()){
	//Antrag in Tabelle einfügen, Geschenk_add, Kind_UPDATE Geschenk_id und Antrag_id setzen 
	
	if($personId !== false){

		$vorgangId = vorgang_add();
		if($vorgangId !== false){
			$dateisammlungId = dateisammlung_add($dateien);
			if($dateisammlungId !== false){
				$dbresult = db_query("INSERT INTO antrag (`Person_id`, `Vorgang_id`,`freitext`, `Dateisammlung_id`, `eingegangen_am` ) VALUES (".(int)$personId.", ".(int)$vorgangId.", '". db_secure_chars($freitext) ."', ".$dateisammlungId.", NOW())", false);
				if($dbresult !== false){
					$resultId = $personId; // das ist korrekt
					foreach ($kinder as $kind) {
						$geschenkId = geschenk_add($kind["artikel"]);
						kind_insertAntragGeschenk($kind["id"],$geschenkId);
					}
				}
			}
		}
	}
	
	return $resultId;
}

function antrag_addBackend($antragsteller, $freitext="", array $dateien=array()){
    $personId = person_add($antragsteller);
    if($personId !== false){
        $vorgangId = vorgang_add();
        if($vorgangId !== false){
            $dateisammlungId = dateisammlung_add($dateien);
            if($dateisammlungId !== false) {
                $dbresult = db_query("INSERT INTO antrag (`Person_id`, `Vorgang_id`,`freitext`, `Dateisammlung_id`, `eingegangen_am` ) VALUES (" . (int)$personId . ", " . (int)$vorgangId . ", '" . db_secure_chars($freitext) . "', " . $dateisammlungId . ", NOW())", false);
                if ($dbresult !== false) {
                    $resultId = $personId;
                } else {
                    $resultId = false;
                }
            }

        }
    }
    return $resultId;
}

function antrag_update($antragId, array $person_antragsteller){
	person_update($antragId, $person_antragsteller);
}

function antrag_getById($antragId){
	$dbresult = db_query("SELECT * FROM antrag A, person P WHERE A.Person_id = P.id AND A.Person_id = ".(int)$antragId);
	return db_fetch_array($dbresult);
}

function antrag_getByGeschenkId($geschenkId){
	$dbresult = db_query("
			SELECT A.*, P.*
			FROM antrag A, person P, kind K
			WHERE
				A.Person_id = P.id
			AND 
				A.Person_id = K.Antrag_Person_id
			AND
				K.Geschenk_id = ".(int) $geschenkId."
			AND  
				A.geloescht_von is NULL");
	return db_fetch_array($dbresult);
}

/**
 * Gibt ein Array aller Anträge zurück 
 * @return multitype:multitype: 
 */
function antrag_getArr(){
	$result = array();
	$dbresult = db_query("
			SELECT * FROM antrag A, person P 
			WHERE 
					A.Person_id = P.id 
			AND		A.geloescht_von is NULL
			ORDER BY A.Vorgang_id DESC");
	
	while($rs = db_fetch_array($dbresult, MYSQL_ASSOC)){
		$result[] = $rs;
	}
	return $result;
}
/**
 * Gibt ein Array mit nicht Freigegebenen Anträgen zurück
 * @return multitype:multitype:
 */
function antrag_getArrNichtFreigegeben(){
    $result = array();
    $dbresult = db_query("
			SELECT A.Person_id, A.freitext, A.Dateisammlung_id, A.geloescht_am, A.geloescht_von, A.Vorgang_id, A.eingegangen_am, P.id, P.firma, P.name, P.vorname, P.strasse, P.plz, P.ort, P.tel, P.email, P.geburtstag, P.geschlecht
			FROM antrag A 
			JOIN person P ON A.Person_id = P.id 
			JOIN kind K ON P.id = K.Antrag_Person_id
            JOIN geschenk G ON G.id = K.Geschenk_id
            WHERE A.geloescht_von IS NULL
            AND G.geloescht_von IS NULL
            AND G.freigabe_fuer_spender_von IS NULL
            GROUP BY A.Person_id
            ORDER BY A.Vorgang_id DESC
			");

    while($rs = db_fetch_array($dbresult, MYSQL_ASSOC)){
        $result[] = $rs;
    }
    return $result;
}
/**
 * Gibt ein Array der wartenden Anträge zurück
 * @return multitype:multitype:
 */
function antrag_getArrWartend(){
    $result = array();
    $dbresult = db_query("
			SELECT A.Person_id, A.freitext, A.Dateisammlung_id, A.geloescht_am, A.geloescht_von, A.Vorgang_id, A.eingegangen_am, P.id, P.firma, P.name, P.vorname, P.strasse, P.plz, P.ort, P.tel, P.email, P.geburtstag, P.geschlecht
			FROM antrag A 
			JOIN person P ON A.Person_id = P.id 
			JOIN kind K ON P.id = K.Antrag_Person_id
            JOIN geschenk G ON G.id = K.Geschenk_id
            WHERE A.geloescht_von IS NULL
            AND G.geloescht_von IS NULL
		    AND G.freigabe_fuer_spender_von IS NOT NULL
		    AND	G.Spende_Person_id IS NULL
            GROUP BY A.Person_id
            ORDER BY A.Vorgang_id DESC
			");

    while($rs = db_fetch_array($dbresult, MYSQL_ASSOC)){
        $result[] = $rs;
    }
    return $result;
}
/**
 * Gibt ein Array der gespendeten, nicht bezahlten Anträge zurück
 * @return multitype:multitype:
 */
function antrag_getArrGespendetNichtBezahlt(){
    $result = array();
    $dbresult = db_query("
			SELECT A.Person_id, A.freitext, A.Dateisammlung_id, A.geloescht_am, A.geloescht_von, A.Vorgang_id, A.eingegangen_am, P.id, P.firma, P.name, P.vorname, P.strasse, P.plz, P.ort, P.tel, P.email, P.geburtstag, P.geschlecht
			FROM antrag A 
			JOIN person P ON A.Person_id = P.id 
			JOIN kind K ON P.id = K.Antrag_Person_id
            JOIN geschenk G ON G.id = K.Geschenk_id
            JOIN spende S ON G.Spende_Person_id = S.Person_id
            WHERE A.geloescht_von IS NULL
            AND G.geloescht_von IS NULL
		    AND S.zahlung_bestaetigt_von IS NULL
            GROUP BY A.Person_id
            ORDER BY A.Vorgang_id DESC
			");

    while($rs = db_fetch_array($dbresult, MYSQL_ASSOC)){
        $result[] = $rs;
    }
    return $result;
}
/**
 * Gibt ein Array der gespendeten und bezahlten Anträge zurück
 * @return multitype:multitype:
 */
function antrag_getArrGespendetUndBezahlt(){
    $result = array();
    $dbresult = db_query("
			SELECT A.Person_id, A.freitext, A.Dateisammlung_id, A.geloescht_am, A.geloescht_von, A.Vorgang_id, A.eingegangen_am, P.id, P.firma, P.name, P.vorname, P.strasse, P.plz, P.ort, P.tel, P.email, P.geburtstag, P.geschlecht
			FROM antrag A 
			JOIN person P ON A.Person_id = P.id 
			JOIN kind K ON P.id = K.Antrag_Person_id
            JOIN geschenk G ON G.id = K.Geschenk_id
            JOIN spende S ON G.Spende_Person_id = S.Person_id
            WHERE A.geloescht_von IS NULL
            AND	G.geloescht_von IS NULL
		    AND	S.zahlung_bestaetigt_von IS NOT NULL
            GROUP BY A.Person_id
            ORDER BY A.Vorgang_id DESC
			");

    while($rs = db_fetch_array($dbresult, MYSQL_ASSOC)){
        $result[] = $rs;
    }
    return $result;
}
/**
 * Freigabe (für Liste für Spender) aller Geschenke eines Antrages 
 */
function antrag_setFreigabe($antragId){
	$geschenke = geschenk_getArrByAntragId($antragId);
	
	foreach ($geschenke as $geschenk) {
		geschenk_setFreigabe($geschenk["id"]);
	}
}

function antrag_delete($antragId){
	
	$geschenke = geschenk_getArrByAntragId($antragId);
	foreach ($geschenke as $geschenk) {
		geschenk_delete($geschenk['id']);
	}
//	db_query("UPDATE antrag SET geloescht_von = ".(int) $_SESSION['userID'].", geloescht_am = NOW() WHERE Person_id = ".(int)$antragId." LIMIT 1");
    db_query("DELETE FROM antrag WHERE Person_id = '".$antragId."'");
}

