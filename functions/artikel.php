<?php

function artikel_add($artikel, $dateinammlungId){
	$validFields = artikel_getValidFields();
	$result = false;
	
	$artikel['Dateisammlung_id'] = (int)$dateinammlungId;
	
	$keysStrings = array();
	$values 	 = array();
	foreach ($artikel as $key => $value) {
		//Nur die elemente aus dem Array übernehmen, die auch personenbezogen sind.
		if(in_array($key, $validFields)){
			$keysStrings[] = "`".db_secure_chars($key)."`";
			$values[]      = "'".db_secure_chars($value)."'";
		}
	}
	
	if(count($values)>0){
		$dbresult = db_query("INSERT INTO artikel (". implode(",", $keysStrings) .") VALUES (". implode(",", $values) ."); ",false);
		if($dbresult){
			$result = db_autoinc_id();
		}
	}
	
	return $result;
}

function artikel_update($artikel){
	
	$validFields = artikel_getValidFields();
	$updates = array();
	
	foreach ($artikel as $key => $value) {
		//Nur die elemente aus dem Array übernehmen, die auch personenbezogen sind.
		if(in_array($key, $validFields)){
			if($key=="preis"){
				$value = str_replace(",",".", $value);
			}
			$updates[] = "`".db_secure_chars($key)."` = '".db_secure_chars($value)."'";
		}
	}

	if(count($updates)>0){
		$dbresult = db_query("UPDATE artikel SET ".implode(', ', $updates)." WHERE id=".(int)$artikel['id']);
	}
}

// function artikel_getById($artikelId){
		
// }

function artikel_getValidFields(){
	return array("preis","anzGesamt", "bezeichnung", "beschreibung", "Dateisammlung_id", "bestellnummer");
}

function artikel_printSelectAvailable($fieldname, $selected = false){
	$verfuerbareArtikel = artikel_getArr(true);
		
	if( $selected !== false ){
		// Sollte der Selektierte Artikel aktuell nicht mehr verfügbar sein,
		// muss er im Backend zur Bearbeitung aber dennoch in der Liste sein
		// also suchen, ob dieser fehlt, wenn ja, explizit hinzufuegen
		$doAddSelected = true;
		foreach ($verfuerbareArtikel as $artikel) {
			if($artikel["id"] == $selected){
				$doAddSelected = false;
				break;
			}
		}
		if($doAddSelected){
			$selectierterArtikel = artikel_getArr(false, $selected);
			$verfuerbareArtikel = array_merge($selectierterArtikel,$verfuerbareArtikel);
		}
	}	
?>
	<select name="<?php echo $fieldname ?>">
	<?php foreach ($verfuerbareArtikel as $artikel) { ?>
		<option value="<?php echo $artikel["id"] ?>" <?php echo (($selected ==$artikel["id"])?" selected ":"") ?>><?php echo $artikel["bezeichnung"] ?></option>
	<?php } ?>
	</select>
<?php 
}


/**
 * Git ein Array mit  Artikeln und deren Verfügbarkeit zurück. 
 * Wenn $nurVerfuegbare <b>true</b> ist, werden nur Artikel zurückgegeben,
 * deren anzGesamt höher ist, als alle Vorkommen des Arktikels in der Tabelle "geschenke".
 * Ist  mit $artikelId ein spezielle Artikel ausgewählt, wird nur dieser zurückgegeben
 *   
 * @param boolen $nurVerfuegbare
 */
function artikel_getArr($nurVerfuegbare = false, $artikelId = -1){
	$result = array();
	
	$whereKlausel = " WHERE 1 = 1 ";
	if($nurVerfuegbare){
		$whereKlausel .= " AND (A.anzGesamt > 0 AND (G.anzVerbraucht < A.anzGesamt OR G.Artikel_id IS NULL)) ";
	}
	if($artikelId !== -1){
		$whereKlausel .= " AND (A.id =".(int)$artikelId.") ";
	}
	
	$dbresult = db_query("
	  SELECT 
		A.id, 
		A.preis,
		A.bezeichnung,
		A.beschreibung,
		A.Dateisammlung_id,
		A.anzGesamt,
		A.bestellnummer,
		COALESCE(anzVerbraucht, 0) as anzVerbraucht, /* COALESCE(...,0) macht NULL zu 0.*/
		(anzGesamt - COALESCE(anzVerbraucht, 0)) as anzVerfuegbar 
	 FROM 
			artikel A 
		LEFT OUTER JOIN	(
			SELECT 
				Artikel_id, count(*) as anzVerbraucht 
			FROM 
				geschenk 
			WHERE 
				geloescht_von IS NULL 
			GROUP BY Artikel_id
		) G
			ON( G.Artikel_id = A.id )
		".$whereKlausel."
		ORDER BY A.bestellnummer, A.id, A.bezeichnung");
	


	while($rs = db_fetch_array($dbresult, MYSQL_ASSOC)){
		$result[] = $rs;
	}
	return $result;
}
/*
 * Löscht den Gewünschten Artikel und die Dateianhänge mit der Artikel-Id
 * param int artikelId
 * return boolean
*/
 function artikel_delete($artikelId){
    //get DateisammlungID
     //if delete datei - dateisammlung ID
     //then delete artikel
     //if artikel deleted then delete Dateisammlung

     $result = false;
     $deleteDatei = false;
     $deleteArtikel = false;
     $deleteDateisammlung = false;
     $query = db_query("
                        SELECT Dateisammlung_id
                        FROM artikel
                        WHERE id =".$artikelId
                        );
     if($rs = db_fetch_array($query)){
         $dateisammlungId = $rs['Dateisammlung_id'];
         if($dateisammlungId > 0){
            $deleteDatei = db_query("
                                        DELETE 
                                        FROM datei
                                        WHERE Dateisammlung_id =".$dateisammlungId
                                        );
             $deleteArtikel = db_query("
                                        DELETE
                                        FROM artikel
                                        WHERE id=".$artikelId
             );
             $deleteDateisammlung = db_query("
                                            DELETE
                                            FROM dateisammlung
                                            WHERE id=".$dateisammlungId
             );
         }
     }
     if($deleteDateisammlung && $deleteDatei && $deleteArtikel){
         $result = true;
     }
    return $result;
 }


