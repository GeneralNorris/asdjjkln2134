<?php
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin', true);

?>
	<div class="actionHead">
		<h1>Mitarbeiter</h1>
		<div class="buttonbar">
		</div>
	</div>
	<table cellpadding="0" cellspacing="0" id="dataTable" class="tablesorter">
		<thead>
			<tr>
				<th>Login</th>
				<th>Name</th>
				<th>Nachname</th>
				<th>E-Mail</th>
				<th>Rollen</th>
				<th>Lezter Login am</th>
				<th>Erstellt von</th>
				<th width="120">Erstellt am</th>
				<th width="90"></th>
			</tr>
		</thead>
		<tbody>
		<?php
			if (user_check_access('admin', false)) {
				$user_list = user_list_admins();
			} else {
				$user_list = user_listByRole("contactor", true);
			}

			$i = 0;
			while ($rs = db_fetch_array($user_list)) {
			?>
				<tr style="background-color:#<?php echo core_row_backgroundcolor($i)?>">
					<td><?php echo htmlspecialchars($rs['login'])?></td>
					<td name="vorname"><?php echo htmlspecialchars($rs['vorname'])?></td>
					<td name="lastname"><?php echo htmlspecialchars($rs['name'])?></td>
					<td name="email"><a href="mailto:<?php echo htmlspecialchars($rs['email'])?>"><?php echo htmlspecialchars($rs['email'])?></a></td>
					<td name="roles"><?php echo htmlspecialchars($rs['roles'])?></td>
					<td><?php echo htmlspecialchars($rs['lastlogindate'])?></td>
					<td><?php echo htmlspecialchars(user_loginByID($rs['createby']))?></td>
					<td><?php echo htmlspecialchars($rs['createdate'])?></td>
					<td>
						<!-- <a title="Benutzer löschen" href="do_backend_action.php?action=do_userDelete&deleteUserID=<?php echo $rs['id']?>" class="delete">löschen</a> | -->
						<a title="Benutzer editieren" href="do_backend_action.php?action=userEdit&editUserID=<?php echo $rs['id']?>" class="edit">editieren</a>
					</td>
				</tr>
			<?php
				$i++;
			}
		?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="8"></td>
				<td  style="background-color:#D7DDF0">
					<a title="Neuen Benutzer hinzufügen" href="do_backend_action.php?action=userAdd" class="add">Neuen Benutzer <br />hinzufügen</a>
				</td>
			</tr>
		</tfoot>
	</table>