<?php 
//2013-11-17 
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin,mitarbeiter', true);

require_once("functions/antrag.php");

if(isset($_GET['id']) && $_GET['id'] != "ADD"){
	$antragId = (int) $_GET['id'];
	$antrag = antrag_getById($antragId);
} else {
	$antragId = "ADD";
}

?>
<form method="post" action="do_backend_action.php?action=do_antragEdit_and_Add">
<input type="hidden" name="antragId" value="<?php echo $antragId ?> ">
	<fieldset>
		<legend>Antragsteller</legend>
		<table>
			<tr>
					<th>Vorname</th>
					<td><input name="antragsteller_vorname" type="text" value="<?php echo htmlspecialchars(@$antrag["vorname"]) ?>"/></td>
				</tr>
				<tr>
					<th>Nachname</th>
					<td><input name="antragsteller_name" type="text" 	value="<?php echo htmlspecialchars(@$antrag["name"]) ?>"/></td>
				</tr>
				<tr>
					<th>Strasse / Nr.</th>
					<td><input name="antragsteller_strasse" type="text" value="<?php echo htmlspecialchars(@$antrag["strasse"]) ?>"/></td>
				</tr>
				<tr>
					<th>PLZ</th>
					<td><input name="antragsteller_plz" type="text" 	value="<?php echo htmlspecialchars(@$antrag["plz"])?>" size="5"/></td>
				</tr>			
				<tr>
					<th>Stadt</th>
					<td><input name="antragsteller_ort" type="text" 	value="<?php echo htmlspecialchars(@$antrag["ort"]) ?>"/></td>
				</tr>
				<tr>
					<th>Telefonnummer</th>
					<td><input name="antragsteller_tel" type="text"		value="<?php echo htmlspecialchars(@$antrag["tel"]) ?>"/></td>
				</tr>
<!--			--><?php //if($antragId != "ADD") { ?>
				<tr>
					<th>Email-Adresse</th>
					<td><input name="antragsteller_email" type="text"
							   value="<?php echo htmlspecialchars(@$antrag["email"]) ?>"/></td>
				</tr>
<!--				--><?php //} ?>
		</table>
	</fieldset>
</form>