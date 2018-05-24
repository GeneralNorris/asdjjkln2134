<?php
if (!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};

$userID = isset($_GET['editUserID'])? (int) $_GET['editUserID'] : $_SESSION['userID'];

if (!user_check_editpermission($userID)) {
	$userID = $_SESSION['userID'];
}

$user = user_byID($userID);
?>
<style>
	fieldset{
		width:425px;
	}
</style>
<?php if (!(@$_GET['ajax'] === "true")) { ?>
	<div class="actionHead">
		<h1>Benutzerkonto: <?php echo user_loginByID($userID) ?></h1>
	</div>
	<br />
	<form method="post" action="portal.php?action=do_userEdit">
<?php } else { ?>
	<form method="post" action="do_backend_action.php?action=do_userEdit">
<?php } ?>
	<input type="hidden" name="userID" value="<?php echo $userID ?> ">
	<fieldset>
		<legend>Anmeldung</legend>
		<table cellspacing="1" cellpadding="0" >
		<?php /*if(!(user_check_access('admin', false) && isset($_POST['editUserID']))){?>
			<tr>
				<th >Aktuelles Kennwort</th>
				<td><input type="password" name="password[oldPw]" value="" maxlength="255"  style="width:200px"></td>
			</tr>
		<?php } */?>
			<tr>
				<th style="width:200px">Neues Kennwort</th>
				<td><input type="password" name="password[newPw1]" value="" maxlength="255"  style="width:200px" autocomplete="off" /></td>
			</tr>
			<tr>
				<th>Kennwort-Bestätigung</th>
				<td><input type="password" name="password[newPw2]" value="" maxlength="255"  style="width:200px" autocomplete="off" /></td>
			</tr>
<?php if (user_check_access('admin,contactor_manager', false)) {?>
			<tr>
				<th>Fehlerhafte Logins</th>
				<td><input type="text" name="faultylogins" value="<?php echo (int) $user['faultylogins'] ?>" maxlength="4"  style="width:200px" autocomplete="off" /><br />(Ab dem <?php echo CONF_MAXFAULTYLOGINS?>. Fehlversuch ist das Konto gesperrt)</td>
			</tr>
<?php } ?>
			</table>
	</fieldset>
	<br />
	<fieldset>
		<legend>Kontakt</legend>
		<table cellspacing="1" cellpadding="0" >
			<tr>
				<th style="width:200px">Vorname</th>
				<td><input type="text" name="vorname" value="<?php echo htmlspecialchars($user['vorname']) ?>" maxlength="255" style="width:200px" autocomplete="off" /></td>
			</tr>
			<tr>
				<th>Nachname</th>
				<td><input type="text" name="name" value="<?php echo htmlspecialchars($user['name']) ?>" maxlength="255" style="width:200px" autocomplete="off" /></td>
			</tr>
			<tr>
				<th>E-Mail</th>
				<td><input type="text" name="email" value="<?php echo htmlspecialchars($user['email']) ?>" maxlength="255" style="width:200px" autocomplete="off" /></td>
			</tr>
			<tr>
				<th>Strasse</th>
				<td><input type="text" name="strasse" value="<?php echo htmlspecialchars($user['strasse']) ?>" maxlength="255" style="width:200px" autocomplete="off" /></td>
			</tr>
			<tr>
				<th>PLZ</th>
				<td><input type="text" name="plz" value="<?php echo htmlspecialchars($user['plz']) ?>" maxlength="255" style="width:200px" autocomplete="off" /></td>
			</tr>
			<tr>
				<th>Ort</th>
				<td><input type="text" name="ort" value="<?php echo htmlspecialchars($user['ort']) ?>" maxlength="255" style="width:200px" autocomplete="off" /></td>
			</tr>
			<tr>
				<th>Telefonnummer</th>
				<td><input type="text" name="tel" value="<?php echo htmlspecialchars($user['tel']) ?>" maxlength="255" style="width:200px" autocomplete="off" /></td>
			</tr>
		</table>
	</fieldset>
<?php if (user_check_access('admin', false)) { ?>
	<br />
	<fieldset>
		<legend>System</legend>
			<table cellspacing="1" cellpadding="0">
			<?php if($user['roles'] != 'benutzer'){?>
			<tr>
				<th style="width:200px; vertical-align:top">Benutzer-Rollen</th>
				<td><?php echo user_printRolesSelect(user_roles($userID)) ?></td>
			</tr>
			<?php }?>
			<tr>
				<th style="width:200px; vertical-align:top">Maximale Kinder deaktivieren</th> 
				<td><input type ="checkbox" name="maxChildren" value ="maxChildren" <?php if($user['maxChildren']){?> checked <?php } ?> /></td>
			</tr>
			<tr>
				<th style="width:200px; vertical-align:top">Aktiviert</th> 
				<td><input type ="checkbox" name="activated" value = "activated" <?php if($user['activated']){?> checked <?php } ?> /></td>
			</tr>
<!--			<tr>-->
<!--				<th style="width:200px; vertical-align:top">IdentNr</th>-->
<!--				<td><input type ="text" name="kundennr" value ="--><?php //echo htmlspecialchars($user['kundennr']) ?><!--" maxlength="255" style="width:200px" autocomplete="off" /></td>-->
<!--			</tr>-->
				<tr>
					<th style="width:200px; vertical-align:top">Email Bestätigungs-Link erneut senden</th>
					<td><input type ="checkbox" name="emailValidation" value ="emailValidation"/></td>
				</tr>
		</table>
	</fieldset>
	
<?php }
//
?>
	<?php if (!(@$_GET['ajax'] === "true")) {?>
		<table cellspacing="1" cellpadding="0" style="width:455px;">
			<tr>
				<td style="text-align:right">
					<input type="submit" value="Speichern">
				</td>
			</tr>
		</table>
	<?php } ?>
</form>