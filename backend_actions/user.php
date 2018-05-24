<?php
//2010-06-09 
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin', true);
if(isset($_SESSION["userDelete"]) && $_SESSION["userDelete"] == "success") {   ?>
	<br>
	<p style="color: green; font-size: 14px"> Mutter wurde Erfolgreich gelöscht!</p>
<?php  } else if(isset($_SESSION["userDelete"]) && $_SESSION["userDelete"]=="geschenke"){   ?>
    <br>
    <p style="color: red; font-size: 14px"> Mutter konnte nicht gelöscht werden, da sie bereits Geschenkwünsche eingereicht hat!</p>
<?php  } else if(isset($_SESSION["userDelete"]) && !$_SESSION["userDelete"]=="kindFehler"){     ?>
	<br>
    <p style="color: red;font-size: 14px"> Unbekannter Fehler: Die Kinder konnten nicht glöscht werden!</p>
<?php } else if(isset($_SESSION["userDelete"]) && !$_SESSION["userDelete"]=="fehler"){     ?>
    <br>
    <p style="color: red;font-size: 14px"> Unbekannter Fehler: Die Mutter konnte nicht gelöscht werden!</p>
<?php }
$bgcolors =  array(0 => '#F00000', 1 => '#00FF00');
?>
	<div class="actionHead">
		<h1>Mütter</h1>

        </span>
        Suche: <input id="pattern" title="Suche in allen Spalten" autocomplete="off">
        </span>
	</div>
	<table cellpadding="0" cellspacing="0" id="dataTable" class="tablesorter">
		<thead>
			<tr>
                <th>Geschenke</th>
                <th>Login</th>
				<th>Nachname</th>
				<th>Vorname</th>
				<th>E-Mail</th>
				<th>Aktiviert</th>
				<th width="5">Maximale Kinderanzahl</th>
				<th>Email-Bestätigt</th>
<!--				<th>IdentNr</th>-->
				<th>Erstellt am</th>
                <th>Lezter Login am</th>
				<th width="50"></th>
                <th width="50"></th>
				<th width="80"></th>
				<th width="80"></th>
            </tr>
		</thead>
		<tbody>
		<?php
			if (user_check_access('admin', false)) {
				$user_list = user_list();
			} else {
				$user_list = user_listByRole("contactor", true);
			}

			$i = 0;
			while ($rs = db_fetch_array($user_list)) {
			?>
				<tr id="<?php echo $rs['id']?>" style="background-color:#<?php echo core_row_backgroundcolor($i)?>">
                    <td style="background-color:<?php echo user_hatAntrag($rs['id']) ?  '#00FF00' :  '#F00000'; ?>"><?php echo user_hatAntrag($rs['id']) ?  'Ja' :  'Nein';?></td>
                    <td><?php echo htmlspecialchars($rs['login'])?></td>
					<td name="lastname"><?php echo htmlspecialchars($rs['name'])?></td>
					<td name="vorname"><?php echo htmlspecialchars($rs['vorname'])?></td>
					<td name="email"><a href="mailto:<?php echo htmlspecialchars($rs['email'])?>"><?php echo htmlspecialchars($rs['email'])?></a></td>
					<td><?php echo ($rs['activated']) ? "Ja" : "Nein"?></td>
					<td><?php echo ($rs['maxChildren']) ? "unbegrenzt" : "6"?></td>
					<td><?php echo ($rs['checkedMail']) ? "Ja" : "Nein"?></td>
<!--					<td>--><?php //echo htmlspecialchars($rs['kundennr'])?><!--</td>-->
					<td><?php echo htmlspecialchars($rs['createdate'])?></td>
                    <td><?php echo htmlspecialchars($rs['lastlogindate'])?></td>
                    <td>
                        <a title="Benutzer editieren" href="do_backend_action.php?action=userEdit&editUserID=<?php echo $rs['id']?>" class="edit">editieren</a>
                    </td>
					<td>
						 <a title="Mutter löschen" href="do_backend_action.php?action=do_userDelete&deleteUserID=<?php echo $rs['id']?>" class="deleteUser">löschen</a>
					</td>
					<td>
						<a title="Kinder editieren" href="do_backend_action.php?action=kinderEdit&editUserID=<?php echo $rs['id']?>" class="edit">Kinder bearbeiten</a>
					</td>
					<td>
						<a title="Kinder hinzufügen" href="do_backend_action.php?action=kinderAdd&editUserID=<?php echo $rs['id']?>" class="edit">Kinder hinzufügen</a>
					</td>

                </tr>
			<?php
				$i++;
			}
		?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="13"></td>
				<td  style="background-color:#D7DDF0">
					<a title="Neuen Benutzer hinzufügen" href="do_backend_action.php?action=userAdd" class="add">Neuen Benutzer <br />hinzufügen</a>
				</td>
			</tr>
		</tfoot>
	</table>
<?php
unset($_SESSION["userDelete"]);
