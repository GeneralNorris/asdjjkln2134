<?php
require_once("functions/artikel.php");
require_once("functions/geschenk.php");
require_once("functions/person.php");

/**
 * Setzt dummerweise auch das geschenk ->//FIXME: in geschenk_add verlagern, hier dann nur die ID übergeben
 * @param unknown $antragId
 * @param unknown $person_kind
 * @return Ambigous <boolean, number>
 */
function kind_add($person_kind, $mutterId){
	$result = false;
	
	$personId = person_add($person_kind);
	if($personId !== false){
		
			$dbresult = db_query(
			"	INSERT INTO
				kind
				(
					Person_id,
					Antrag_Person_id
				)
				VALUES
				(	
					'".db_secure_chars($personId)."',
					'".db_secure_chars($mutterId)."'
				)"
			);
			if($dbresult){
//				$result = db_autoinc_id();
				$result = $personId;
			}else{
				echo "kind add fehler";
				exit;
			}
		
	}

	return $result;
}

function kind_delete($kindId){
	$kindID = (int)$kindId;
	$result = false;
	$query = db_query(
			"
			SELECT Geschenk_id
			FROM kind
			WHERE Person_id=".$kindID
			);

	$q2 = db_query(
			"DELETE 
			FROM kind
			WHERE Person_id=".$kindID
			);
	if($rs = db_fetch_array($query)){
		$geschenkId = $rs['Geschenk_id'];
		if($geschenkId > 0) {
			db_query(
				"DELETE
				FROM Geschenk
				WHERE id=" . $geschenkId
			);
		}
	}
	
	$q3 = db_query(
			"DELETE
			FROM person
			WHERE id=".$kindID
			);
	if($q2 && $q3){
		$result = true;
	}
	return $result;
}

function kind_update($kindId, $person_kind){
	person_update($kindId, $person_kind);	
}

function kind_update_name($kindId, $name){
	$kind_id=(int)$kindId;
	db_query(
			"UPDATE person
			 SET vorname='".db_secure_chars($name)."'
			 WHERE id=".$kind_id
			);
}

function kind_update_geburtstag($kindId, $geburtstag){
	$kind_id = (int)$kindId;
	db_query(
			"UPDATE person
			 SET geburtstag='".db_secure_chars($geburtstag)."'
			 WHERE id=".$kind_id
			);
}

function kind_insertAntragGeschenk($kindId,$GeschenkId){
	$kind_id = (int)$kindId;
	$geschenk_id = (int)$GeschenkId;
	$dbresult= db_query(
			"	UPDATE kind
				SET Geschenk_id=".$geschenk_id."
				WHERE Person_id=".$kind_id
			);
	if(!$dbresult){
		echo "Kind insert Fehler";
		exit;
	}
	
}

function kind_insertAntragPerson($kindId ,$antragId){
	$kind_id = (int)$kindId;
	$antrag_id = (int)$antragId;
	$dbresult= db_query(
		"	UPDATE kind
				SET  Antrag_Person_id =".$antrag_id."
				WHERE Person_id=".$kind_id
	);
	if(!$dbresult){
		echo "Kind insert Fehler";
		exit;
	}

}

function kind_isChild($userId){
	$parentId = (int) $userId;
	$dbresult = db_query("
				SELECT Person_id
				FROM kind
				WHERE Antrag_Person_id=".$parentId
			);
	$ids = array();
	while($a = db_fetch_array($dbresult)){
		array_push($ids, $a["Person_id"]);
	}
	return $ids;
}

function kind_hatGeschenk($kindId){
	$kind_id = (int)$kindId;
	$dbresult = db_query("
				SELECT Geschenk_id 
				FROM kind
				WHERE Person_id=".$kind_id
			);
	$rs = db_fetch_array($dbresult);
	if($rs["Geschenk_id"]){
		return true;
	}else{
		return false;
	}
}

function kind_getArr(){
    $result = array();
    $dbresult = db_query("
                            SELECT P.vorname, M.name, P.geburtstag, P.geschlecht,  M.strasse, M.plz, M.ort, M.email  
                            FROM kind k 
                            JOIN person P on k.Person_id = P.id
                            JOIN person M on k.Antrag_Person_id = M.id 
                            ");
    while ($rs = db_fetch_array($dbresult,MYSQL_ASSOC)){
        $result[] = $rs;
    }
    return $result;
}

function kind_getVorname($kindId){
	$kind_id = (int)$kindId;
	$dbresult = db_query("
							SELECT vorname
							FROM person
							WHERE id=".$kind_id
				);
	$rs = db_fetch_array($dbresult);
	return $rs["vorname"];
}

function kind_printAntragElements(){
	static $count =0;
	$count++;
?>
			<tr>
				<td>
				Geschenkwunsch:<?php echo "<span style=\"color:red\">*</span>"?>
				</td>
				<td>
					<?php artikel_printSelectAvailable("kind_artikel[]");?>
				</td>
				
			</tr>	
<?php 
}

function kind_printAddElements(){
	static $count = 0;
	$count++;
	?>
        <table>
	<tr>
        <th>Vorname:<?php echo (($count == 1) ? "<span style=\"color:red\">*</span>" : "")?></th>
		<td><input name="kind_vorname" type="text"<?php echo (($count == 1) ? "required" : "")?>/></td>
	</tr>
	<tr>
		<th>Nachname:</th>
		<td><input name="kind_name" type="text"/></td>
	</tr>
	<tr>
		<th for="">Geburtsdatum:<?php echo (($count == 1) ? "<span style=\"color:red\">*</span>" : "")?></th>
		<td>
			<select name="kind_geb-d" <?php echo (($count == 1) ? "required" : "")?>>
				<option selected="selected">1</option>
				<option>2</option>
				<option>3</option>
				<option>4</option>
				<option>5</option>
				<option>6</option>
				<option>7</option>
				<option>8</option>
				<option>9</option>
				<option>10</option>
				<option>11</option>
				<option>12</option>
				<option>13</option>
				<option>14</option>
				<option>15</option>
				<option>16</option>
				<option>17</option>
				<option>18</option>
				<option>19</option>
				<option>20</option>
				<option>21</option>
				<option>22</option>
				<option>23</option>
				<option>24</option>
				<option>25</option>
				<option>26</option>
				<option>27</option>
				<option>28</option>
				<option>29</option>
				<option>30</option>
				<option>31</option>
			</select>
			<select name="kind_geb-m" <?php echo (($count == 1) ? "required" : "")?>>
				<option selected="selected" value="1">Januar</option>
				<option value="2">Febraur</option>
				<option value="3">März</option>
				<option value="4">April</option>
				<option value="5">Mai</option>
				<option value="6">Juni</option>
				<option value="7">Juli</option>
				<option value="8">August</option>
				<option value="9">September</option>
				<option value="10">Oktober</option>
				<option value="11">November</option>
				<option value="12">Dezember</option>
			</select>
			<select name="kind_geb-j" <?php echo (($count == 1) ? "required" : "")?>>

				<?php for ($i = date("Y"); $i > date("Y") - 16; $i--) { ?>
					<option><?php echo $i ?></option>
				<?php }	?>

			</select><br>
		</td>
	</tr>
	<tr>
		<th>Geschlecht:<?php echo (($count == 1) ? "<span style=\"color:red\">*</span>" : "")?></th>
		<td>
			<select name="kind_geschlecht">
				<option value="m" selected="selected">männlich</option>
				<option value="w">weiblich</option>
			</select>
		</td>
	</tr>
        </table>

	<?php
}

function kind_printRegistrationElements(){
	static $count = 0;
	$count++;
?>
		<tr>
			<td><label>Vorname:<?php echo (($count == 1) ? "<span style=\"color:red\">*</span>" : "")?></label></td>
			<td><input name="kind_vorname[]" id="kind_vorname" type="text"  <?php echo (($count == 1) ? "required" : "")?>/></td>
		</tr>
		<tr>
			<td><label>Nachname:</label></td>
			<td><input name="kind_name[]" id="kind_name" type="text"  /></td>
		</tr>
		<tr>
			<td><label for="">Geburtsdatum:<?php echo (($count == 1) ? "<span style=\"color:red\">*</span>" : "")?></label></td>
			<td>
				<select name="kind_geb-d[]" <?php echo (($count == 1) ? "required" : "")?>>
					<option selected="selected">1</option>
					<option>2</option>
					<option>3</option>
					<option>4</option>
					<option>5</option>
					<option>6</option>
					<option>7</option>
					<option>8</option>
					<option>9</option>
					<option>10</option>
					<option>11</option>
					<option>12</option>
					<option>13</option>
					<option>14</option>
					<option>15</option>
					<option>16</option>
					<option>17</option>
					<option>18</option>
					<option>19</option>
					<option>20</option>
					<option>21</option>
					<option>22</option>
					<option>23</option>
					<option>24</option>
					<option>25</option>
					<option>26</option>
					<option>27</option>
					<option>28</option>
					<option>29</option>
					<option>30</option>
					<option>31</option>
				</select>
				<select name="kind_geb-m[]" <?php echo (($count == 1) ? "required" : "")?>>
					<option selected="selected" value="1">Januar</option>
					<option value="2">Febraur</option>
					<option value="3">März</option>
					<option value="4">April</option>
					<option value="5">Mai</option>
					<option value="6">Juni</option>
					<option value="7">Juli</option>
					<option value="8">August</option>
					<option value="9">September</option>
					<option value="10">Oktober</option>
					<option value="11">November</option>
					<option value="12">Dezember</option>
				</select>
				<select name="kind_geb-j[]" <?php echo (($count == 1) ? "required" : "")?>>
				
				<?php for ($i = date("Y"); $i > date("Y") - 16; $i--) { ?>
						<option><?php echo $i ?></option>
				<?php }	?>
				
				</select>
			</td>
		</tr>
		<tr>
			<td><label>Geschlecht:<?php echo (($count == 1) ? "<span style=\"color:red\">*</span>" : "")?></label></td>
			<td>
				<select name="kind_geschlecht[]">
					<option value="m" selected="selected">männlich</option>
					<option value="w">weiblich</option>
				</select>
			</td>
		</tr>

		
<?php
}

function kind_zuAlt()
{
	$query = db_query(
		"SELECT Person_id FROM person, kind
			 WHERE TIMESTAMPDIFF(YEAR,person.geburtstag,CURDATE()) > 15 
			 AND kind.Geschenk_id IS NULL 
			 AND kind.Person_id = person.id"
	);
	$kinderIds = array();
	while ($a = db_fetch_array($query)) {
		array_push($kinderIds, $a["Person_id"]);
	}
	if (count($kinderIds) > 0) {
		$query2 = db_query(
			"SELECT Antrag_Person_id
				 FROM kind
				 WHERE Person_id IN (" . implode(",", $kinderIds) . ")
				 ");
		$mutterIds = array();
		while ($b = db_fetch_array($query2)) {
			array_push($mutterIds, $b["Antrag_Person_id"]);
		}
		for ($i = 0; $i < count($mutterIds); $i++) {
			mail_send_kindDeleted($mutterIds[$i]);
		}
		for ($j = 0; $j < count($kinderIds); $j++) {
			kind_delete($kinderIds[$i]);
		}
		return true;
	}else{
		return false;
	}
	//IDS der Mütter abfragen -> Email an Mütter -> Benachrichtigung das Kinder gelöscht werden -> Kinder löschen
}