<?php 
//2013-11-17 
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin,mitarbeiter', true);

require_once("functions/geschenk.php");
require_once("functions/artikel.php");
$antragId = -1;
if(isset($_GET['geschenkId']) && $_GET['geschenkId'] != "ADD"){
	$geschenkId = (int) $_GET['geschenkId'];
	$geschenk = geschenk_getById($geschenkId);
} else if(!@empty($_GET["antragId"])){
	$geschenkId = "ADD";
	$antragId = (int)$_GET["antragId"];
} else {
	trigger_error("Unzureichende Eingangsdaten per GET", E_USER_ERROR);
	exit;
}

?>
<form method="post" action="do_backend_action.php?action=do_geschenkEdit_and_Add">
<input type="hidden" name="geschenkId" value="<?php echo $geschenkId ?>">
<input type="hidden" name="antragId" value="<?php echo $antragId ?>">
	<fieldset>
		<legend>Geschenkewunsch</legend>
		<table>
			<tr>
				<th>Vorname</th>
				<td><input name="kind_vorname" type="text" value="<?php echo @htmlspecialchars($geschenk["vorname"]) ?>"/></td>
			</tr>
			<tr>
				<th>Nachname</th>
				<td><input name="kind_name" type="text" 	value="<?php echo htmlspecialchars(@$geschenk["name"]) ?>"/></td>
			</tr>
			<tr>
				<th>Geburtstag</th>
				<td><input name="kind_geburtstag" type="text" id="geburtstag" value="<?php echo @core_dateformat(@$geschenk["geburtstag"]) ?>" /></td>
			</tr>
			<tr>
				<th>Geschlecht</th>
				<td>
					<select name="kind_geschlecht">
						<option value="m" <?php echo ((@$geschenk["geschlecht"] == "m")? " selected ":"")?>>männlich</option>
						<option value="w" <?php echo ((@$geschenk["geschlecht"] == "w")? " selected ":"")?>>weiblich</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>Artikel</th>
				<td>
					<?php artikel_printSelectAvailable("kind_artikel", @$geschenk["Artikel_id"]);?>
				</td>
			</tr>	
		</table>
	</fieldset>
</form>
<script>
$("#geburtstag").datepicker({ dateFormat: 'dd.mm.yy', showButtonPanel: false, changeMonth: true,
	changeYear: true});
</script>