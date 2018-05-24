<?php
require_once("functions/spende.php");

function geschenk_add($artikelId){
	$resultId = false;
	$dbresult = db_query("INSERT INTO geschenk (`Artikel_id`) VALUES (".(int)$artikelId.")", false);
	if($dbresult !== false){
		$resultId = db_autoinc_id();
	}else{
		echo "geschenk add Fehler";
		exit;
	}
	return $resultId;
}

function geschenk_update($geschenkId, $artikelId){
	db_query("UPDATE geschenk SET Artikel_id = ".(int)$artikelId." WHERE id =".(int)$geschenkId);
}

/**
 * Gibt die Anzahl der Geschenke zurück, die noch keiinen Spender haben. Ungeachtet ob sie zur Spende freigegeben sind. 
 */
function geschenk_getCountGeschenkeUnbezahlt() {
	$dbresult = db_query("
	SELECT 
		count(*)
	FROM	
		geschenk
	WHERE
		Spende_Person_id IS NULL                /*ist noch nicht bereits einer Spende zugeordet*/
	AND
		geloescht_von IS NULL                   /*ist nicht gelöscht worden*/
	");
	$arr = db_fetch_array($dbresult);
	return $arr[0];
}
/**
 * @return multitype:multitype: 
 */
function geschenk_getOffeneWuensche(){
	$result = array();
	$dbresult = db_query("
		SELECT 
			G.id as Geschenk_id, G.Artikel_id, P.vorname, P.geburtstag, P.geschlecht,
			TIMESTAMPDIFF(YEAR,P.geburtstag,CURDATE()) AS `alter`
		FROM		
			kind K,
			person P,
			(
				SELECT
					id, Artikel_id, freigabe_fuer_spender_am
				FROM 
					geschenk
				WHERE
					Spende_Person_id IS NULL                /*ist noch nicht bereits einer Spende zugeordet*/
				AND
					freigabe_fuer_spender_von IS NOT NULL   /*wurde für Spender freigegeben*/
				AND
					geloescht_von IS NULL                   /*ist nicht gelöscht worden*/
			) G
		WHERE 
			K.Person_id = P.id
		AND
			K.Geschenk_id = G.id
		ORDER BY
			G.freigabe_fuer_spender_am ASC
	");
	
	while($rs = db_fetch_array($dbresult)){
		$result[] = $rs;
	}
	return $result;
}

function geschenk_getNichtFreigegebeneWuensche(){
    $result = array();
    $dbresult = db_query("
                SELECT 
                    G.id as geschenk_id, G.Artikel_id,P.vorname, P.name,P.geburtstag, P.geschlecht, Art.bestellnummer,
                    A.Vorgang_id as antrag_vorgang_id
                  
                    
                FROM		
                    kind K,
                    person P,
                    antrag A,
                    artikel Art,
                    (
                        SELECT
                            id, Artikel_id, freigabe_fuer_spender_am
                        FROM 
                            geschenk
                        WHERE
                            Spende_Person_id IS NULL                /*ist noch nicht bereits einer Spende zugeordet*/
                        AND
                            freigabe_fuer_spender_von IS NULL   /*wurde nicht für Spender freigegeben*/
                        AND 
                            freigabe_fuer_spender_am IS NULL 
                        AND
                            geloescht_von IS NULL                   /*ist nicht gelöscht worden*/
                    ) G
                WHERE 
                    K.Person_id = P.id
                AND
                    K.Geschenk_id = G.id
                AND 
                    A.Person_id = K.Antrag_Person_id
                AND 
                    G.Artikel_id = Art.id
                AND
                    K.Antrag_Person_id = Mutter.id
                ORDER BY
                    Antrag_vorgang_id
    ");

    while($rs = db_fetch_array($dbresult)){
        $result[] = $rs;
    }
    return $result;
}

function geschenk_getArr(){
    $result = array();
    $dbresult = db_query("
            SELECT A.Vorgang_id, A.eingegangen_am, P.name, P.vorname, P.strasse, P.plz, P.ort, P.tel, P.email, concat_ws(', ', Kp.vorname, Ar.bestellnummer, Ar.bezeichnung) AS wunsch
            FROM antrag A JOIN person P ON A.Person_id = P.id
            JOIN kind K ON K.Antrag_Person_id = P.id
            JOIN geschenk G ON G.id = K.Geschenk_id
            JOIN artikel Ar ON G.Artikel_id = Ar.id
            JOIN person Kp ON K.Person_id = Kp.id
            WHERE A.geloescht_von IS NULL
            AND A.geloescht_am IS NULL
            AND G.geloescht_von IS NULL 
            AND G.geloescht_am IS NULL
            AND G.verschickt_am IS NULL 
            ORDER BY A.Vorgang_id DESC 
    ");

    while($rs = db_fetch_array($dbresult,MYSQL_ASSOC)){
        $result[] = $rs;
    }
    return $result;
}

function geschenk_getArrByAntragId($antragId){
	$result = array();
	$dbresult = db_query("
		SELECT
			P.vorname,
			P.name,
			P.geburtstag,
			P.geschlecht,
			G.id,
			G.Artikel_id,
			G.Spende_Person_id,
			G.freigabe_fuer_spender_von,
			K.Antrag_Person_id,
			K.Person_id,
			TIMESTAMPDIFF(YEAR,P.geburtstag,CURDATE()) AS `alter`,
			A.bezeichnung,
			A.bestellnummer,
			A.preis
		FROM
			kind K,
			person P,
			geschenk G,
			artikel A
		WHERE
			K.Antrag_Person_id = ".(int)$antragId." 
		AND
			K.Person_id = P.id
		AND
			K.Geschenk_id = G.id
		AND 
			G.Artikel_id = A.id
		AND
			G.geloescht_von IS NULL /*ist nicht gelöscht worden*/
	    ORDER BY A.id DESC
	");

	while($rs = db_fetch_array($dbresult,MYSQL_ASSOC)){
		$result[] = $rs;
	}
	return $result;
}

function geschenk_getCountArr(){
	$result = array();
	$dbresult = db_query("
		SELECT
			count(*) as count
		FROM
			geschenk G
		WHERE
			G.geloescht_von IS NULL
	");
	
	while($rs = db_fetch_array($dbresult,MYSQL_ASSOC)){
		$result["total"] = $rs["count"];
	}
	
	$dbresult = db_query("
		SELECT
			count(*) as count
		FROM
			geschenk G
		WHERE
			G.geloescht_von IS NULL
		AND
			G.freigabe_fuer_spender_von IS NULL
	");
	
	while($rs = db_fetch_array($dbresult,MYSQL_ASSOC)){
		$result["nicht_freigegeben"] = $rs["count"];
	}
	
	$dbresult = db_query("
		SELECT
			count(*) as count
		FROM
			geschenk G
		WHERE
			G.geloescht_von IS NULL
		AND
			G.freigabe_fuer_spender_von IS NOT NULL
		AND
			G.Spende_Person_id IS NULL
	");
	
	while($rs = db_fetch_array($dbresult,MYSQL_ASSOC)){
		$result["wartend"] = $rs["count"];
	}
	
	$dbresult = db_query("
		SELECT
			count(*) as count
		FROM
			geschenk G,
			spende S
		WHERE
			G.Spende_Person_id = S.Person_id
		AND
			G.geloescht_von IS NULL
		AND 
			S.zahlung_bestaetigt_von IS NULL
	");
	
	while($rs = db_fetch_array($dbresult,MYSQL_ASSOC)){
		$result["gespendet_nicht_bezahlt"] = $rs["count"];
	}
	
	$dbresult = db_query("
		SELECT
			count(*) as count
		FROM
			geschenk G,
			spende S
		WHERE
			G.Spende_Person_id = S.Person_id
		AND
			G.geloescht_von IS NULL
		AND
			S.zahlung_bestaetigt_von IS NOT NULL
	");
	
	while($rs = db_fetch_array($dbresult,MYSQL_ASSOC)){
		$result["gespendet_bezahlt"] = $rs["count"];
	}
	
	return $result;
}

function geschenk_getById($geschenkId){
	$dbresult = db_query("
		SELECT
			P.vorname,
			P.name,
			P.geburtstag,
			P.geschlecht,
			G.id,
			G.Artikel_id,
			G.Spende_Person_id,
			G.freigabe_fuer_spender_von,
			K.Antrag_Person_id,
			K.Person_id,
			TIMESTAMPDIFF(YEAR,P.geburtstag,CURDATE()) AS `alter`,
			A.bezeichnung,
			A.bestellnummer,
			A.preis
		FROM
			kind K,
			person P,
			geschenk G,
			artikel A
		WHERE
			G.id = ".(int)$geschenkId."
		AND
			K.Person_id = P.id
		AND
			K.Geschenk_id = G.id
		AND 
			G.Artikel_id = A.id
		AND
			G.geloescht_von IS NULL /*ist nicht gelöscht worden*/
	");
	return db_fetch_array($dbresult);
}

function geschenk_getArrBySpendeId($spendeId){
	$result = array();
	$dbresult = db_query("
		SELECT
			P.vorname,
			P.name,
			P.geburtstag,
			P.geschlecht,
			G.id,
			G.Artikel_id,
			G.Spende_Person_id,
			G.freigabe_fuer_spender_von,
			K.Antrag_Person_id,
			K.Person_id,
			A.bezeichnung,
			A.bestellnummer,
			A.preis,
			TIMESTAMPDIFF(YEAR,P.geburtstag,CURDATE()) AS `alter`
		FROM
			kind K,
			person P,
			geschenk G,
			artikel A
		WHERE
			G.Spende_Person_id = ".(int)$spendeId." 
		AND
			K.Person_id = P.id
		AND
			A.id = G.Artikel_id
		AND
			K.Geschenk_id = G.id
		AND
			G.geloescht_von IS NULL /*ist nicht gelöscht worden*/
	");

	while($rs = db_fetch_array($dbresult, MYSQL_ASSOC)){
		$result[] = $rs;
	}
	return $result;
}

function geschenk_getHTMLByGeschenkeArr($geschenke, $antragId){
	$geschenkeCount = count($geschenke);
	$geschenkeHtml='<table cellpadding="0" cellspacing="0">';
	foreach ($geschenke as $geschenk) {
	
		$spende = spende_getById($geschenk["Spende_Person_id"]);
		if($geschenk["Spende_Person_id"]>0){
			if($geschenk["Spende_Person_id"]>0 && $spende["zahlung_bestaetigt_von"]>0){
				$statusImg = "flag_green.png";
				$statusTxt = "Geschenk wurde gespendet und bezahlt";
			} else {
				$statusImg = "flag_blue.png";
				$statusTxt = "Geschenk ist gespendet, aber noch nicht bezahlt";
			}
			
		} elseif($geschenk["freigabe_fuer_spender_von"]) {
			$statusImg = "flag_yellow.png";
			$statusTxt = "Geschenk wartet auf einen Spender";
		} else {
			$statusImg = "flag_red.png";
			$statusTxt = "Geschenk ist noch nicht freigegeben";
		}
	
		$geschenkeHtml .= '
				<tr>
					<td><img src="backend_theme/images/icons/'.$statusImg.'" title="'.$statusTxt.'"></td>
					<td>[<b>'.$geschenk["bestellnummer"].'</b> '.$geschenk["bezeichnung"].']</td>
					<td>'.htmlspecialchars($geschenk["vorname"].' '.$geschenk["name"]).' ('.$geschenk["geschlecht"].',<span style="visibility:hidden">g</span>'.core_dateformat($geschenk["geburtstag"]).')</td>
					<td>
						<a style="margin-right:9px" title="Geschenk bearbeiten" href="do_backend_action.php?action=geschenkEdit_and_Add&geschenkId='.$geschenk['id'].'" class="edit"><img src="backend_theme/images/icons/user_edit.png"></a>
						<a style="margin-right:9px" title="ACHTUNG! Geschenk löschen und den Artikel wieder freigeben" href="do_backend_action.php?action=do_geschenkDelete&id='.$geschenk['id'].'" class="delete"><img src="backend_theme/images/icons/user_delete.png"></a>
					</td>
				</tr>';
	}
	
	$geschenkeHtml .='
				<tr>
					<td colspan="3"></td>
					<td style="text-align:right;">
						<a style="margin-right:9px" title="Geschenk hinzufügen" href="do_backend_action.php?action=geschenkEdit_and_Add&antragId='.(int)$antragId.'&geschenkId=ADD" class="add"><img src="backend_theme/images/icons/user_add.png"></a>
					</td>
				</tr>
			</table>';
	
	return $geschenkeHtml;
}

function geschenk_getSpecialSpendenHtml($geschenke, $vorgang_id){
    $geschenkeHtml='<table cellpadding="0" cellspacing="0">';
    foreach ($geschenke as $geschenk) {
        $geschenkeHtml .= '
				<tr>
				    <td><input type="checkbox" name="checkedGeschenke[]" preis='.$geschenk["preis"].'  id='.$vorgang_id.' value='. $geschenk["id"].'></td>
					<td>[<b>' . $geschenk["bestellnummer"] . '</b> ' . $geschenk["bezeichnung"] . '  ' . $geschenk["preis"] . '€ ]</td>
					<td>' . htmlspecialchars($geschenk["vorname"] . ' ' . $geschenk["name"]) . ' (' . $geschenk["geschlecht"] . ',<span style="visibility:hidden">g</span>' . core_dateformat($geschenk["geburtstag"]) . ')</td>
				</tr>';

    }
    $geschenkeHtml .= '</table>';

return $geschenkeHtml;
}

function geschenk_setFreigabe($geschenkId){
	db_query("UPDATE geschenk SET freigabe_fuer_spender_von = ".(int) $_SESSION['userID'].", freigabe_fuer_spender_am=NOW() WHERE id = ".(int)$geschenkId." LIMIT 1");
}

/**
 * Gibt true zurück, wenn der Spender für das Geschenk gesetzt werden konnte, false sonst. (false = geschenk bereits gespendet)
 * @param int $geschenkId
 * @param int $spenderId
 * @return number
 */
function geschenk_setSpender($geschenkId, $spenderId){
	db_query("UPDATE geschenk SET Spende_Person_id = ".(int) $spenderId." WHERE id = ".(int)$geschenkId." AND Spende_Person_id IS NULL LIMIT 1");
	return (db_affected_rows() > 0);
}

function geschenk_delete($geschenkId){
//	db_query("UPDATE geschenk SET geloescht_von = ".(int) $_SESSION['userID'].", geloescht_am=NOW() WHERE id = ".(int)$geschenkId." LIMIT 1");
    db_query("UPDATE kind SET geschenk_id = NULL WHERE geschenk_id ='".$geschenkId."'");
    db_query("DELETE From geschenk WHERE id = '".$geschenkId."'");

}