<?php
//2010-07-12 
if (!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin,contactor_manager', true);

?>
	<form action="do_backend_action.php?action=do_userAdd" method="post">
		<fieldset>
			<legend>Anmeldung</legend>
			<table cellpadding="0" cellspacing="1">
				<tr>
					<th style="width:200px">Login-Name</th>
					<td><input name="login" type="text"  value="" style="width:200px" autocomplete="off" /></td>
				</tr>
				<tr>
					<th>Kennwort</th>
					<td><input name="password1" type="password"  value="" style="width:200px" autocomplete="off" /></td>
				</tr>
				<tr>
					<th>Kennwort-Bestätigung</th>
					<td><input name="password2" type="password"  value="" style="width:200px" autocomplete="off" /></td>
				</tr>
			</table>
		</fieldset>
		<br />
		<fieldset>
			<legend>Kontakt</legend>
			<table cellpadding="0" cellspacing="1">
				<tr>
					<th style="width:200px">Name</th>
					<td><input name="name" type="text"  value="" style="width:200px" autocomplete="off" /></td>
				</tr>
				<tr>
					<th>Nachname</th>
					<td><input name="lastname" type="text"  value="" style="width:200px" autocomplete="off" /></td>
				</tr>
				<tr>
					<th>E-Mail</th>
					<td><input name="email" type="text"  value="" style="width:200px" autocomplete="off" /></td>
				</tr>
			</table>
		</fieldset>
		<?php if (user_check_access('admin', false)) {?>
		<br />
		<fieldset>
			<legend>Rollen</legend>
			<table cellpadding="0" cellspacing="1" >
				<tr>
					<th style="width:200px; vertical-align:top">Rollen</th>
					<td><?php echo user_printRolesSelect() ?></td>
				</tr>
			</table>
		</fieldset>
		<?php } ?>

		<?php if (!isset($_GET['ajax'])) { ?>
			<input type="submit" value="OK" />
		<?php } ?>
	</form>