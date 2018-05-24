<?php
user_check_login();
user_check_access('benutzer', true);
if (!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};

$userID = isset($_GET['editUserID'])? (int) $_GET['editUserID'] : $_SESSION['userID'];

if (!user_check_editpermission($userID)) {
	$userID = $_SESSION['userID'];
}
if(!user_isMailConfirmed($userID)){
	header("location: index.php?action=overview");
}
if(isset($_SESSION['user_edit'])&& $_SESSION['user_edit']){
	echo "Daten erfolgreich geändert";
	unset($_SESSION["user_edit"]);
}
$user = user_byID($userID);
?>
<?php if (!(@$_GET['ajax'] === "true")) { ?>
	<form method="post" action="index.php?action=do_userEdit">
<?php } else { ?>
	<form method="post" action="do_frontend_action.php?action=do_userEdit">
<?php } ?>
	<input type="hidden" name="userID" value="<?php echo $userID ?> ">
        <h4>Benutzerkonto: <?php echo user_loginByID($userID) ?></h4>
        <section class="wrapper style4">
            <div class="container">

                <div class="row">
                    <section class="6u 12u(narrower)">
                        <table>
                                        <th><h3>Anmeldung</h3></th>
                            			<tr>
                            				<th>Neues Kennwort</th>
                            				<td><input type="password" name="password[newPw1]" value="" maxlength="255" autocomplete="off" /></td>
                            			</tr>
                            			<tr>
                            				<th>Kennwort wiederholen</th>
                            				<td><input type="password" name="password[newPw2]" value="" maxlength="255" autocomplete="off" /></td>
                            			</tr>
                        </table>
                        <table>
                            <th><h3>Kennwort-Bestätigung</h3></th>
                            <tr>
                                <th><label>Aktuelles Kennwort<span style="color:red">*</span></label></th>
                                <td><input type="password" name="password[oldPw]" value="" maxlength="255"></td>
                            </tr>
                        </table>
                    </section>
                    <section class="6u 12u(narrower)">
                        <table>
                        <th><h3>Kontakt</h3></th>
                        			<tr>
                        				<th>Vorname</th>
                        				<td><input type="text" name="vorname" value="<?php echo htmlspecialchars($user['vorname']) ?>" maxlength="255" autocomplete="off" /></td>
                        			</tr>
                        			<tr>
                        				<th>Nachname</th>
                        				<td><input type="text" name="name" value="<?php echo htmlspecialchars($user['name']) ?>" maxlength="255" autocomplete="off" /></td>
                        			</tr>
                        			<tr>
                        				<th>E-Mail</th>
                        				<td><input type="text" name="email" value="<?php echo htmlspecialchars($user['email']) ?>" maxlength="255" autocomplete="off" /></td>
                        			</tr>
                        			<tr>
                        				<th>Strasse</th>
                        				<td><input type="text" name="strasse" value="<?php echo htmlspecialchars($user['strasse']) ?>" maxlength="255" autocomplete="off" /></td>
                        			</tr>
                        			<tr>
                        				<th>PLZ</th>
                        				<td><input type="text" name="plz" value="<?php echo htmlspecialchars($user['plz']) ?>" maxlength="255" autocomplete="off" /></td>
                        			</tr>
                        			<tr>
                        				<th>Ort</th>
                        				<td><input type="text" name="ort" value="<?php echo htmlspecialchars($user['ort']) ?>" maxlength="255" autocomplete="off" /></td>
                        			</tr>
                        			<tr>
                        				<th>Telefonnummer</th>
                        				<td><input type="text" name="tel" value="<?php echo htmlspecialchars($user['tel']) ?>" maxlength="255" autocomplete="off" /></td>
                        			</tr>
                        </table>
                    </section>
                </div>
            </div>

        </section>



<!--		<table>-->
<!--            <th><h3>Anmeldung</h3></th>-->
<!--			<tr>-->
<!--				<th>Neues Kennwort</th>-->
<!--				<td><input type="password" name="password[newPw1]" value="" maxlength="255" autocomplete="off" /></td>-->
<!--			</tr>-->
<!--			<tr>-->
<!--				<th>Kennwort wiederholen</th>-->
<!--				<td><input type="password" name="password[newPw2]" value="" maxlength="255" autocomplete="off" /></td>-->
<!--			</tr>-->
<!--            <th><h3>Kontakt</h3></th>-->
<!--			<tr>-->
<!--				<th>Vorname</th>-->
<!--				<td><input type="text" name="vorname" value="--><?php //echo htmlspecialchars($user['vorname']) ?><!--" maxlength="255" autocomplete="off" /></td>-->
<!--			</tr>-->
<!--			<tr>-->
<!--				<th>Nachname</th>-->
<!--				<td><input type="text" name="name" value="--><?php //echo htmlspecialchars($user['name']) ?><!--" maxlength="255" autocomplete="off" /></td>-->
<!--			</tr>-->
<!--			<tr>-->
<!--				<th>E-Mail</th>-->
<!--				<td><input type="text" name="email" value="--><?php //echo htmlspecialchars($user['email']) ?><!--" maxlength="255" autocomplete="off" /></td>-->
<!--			</tr>-->
<!--			<tr>-->
<!--				<th>Strasse</th>-->
<!--				<td><input type="text" name="strasse" value="--><?php //echo htmlspecialchars($user['strasse']) ?><!--" maxlength="255" autocomplete="off" /></td>-->
<!--			</tr>-->
<!--			<tr>-->
<!--				<th>PLZ</th>-->
<!--				<td><input type="text" name="plz" value="--><?php //echo htmlspecialchars($user['plz']) ?><!--" maxlength="255" autocomplete="off" /></td>-->
<!--			</tr>-->
<!--			<tr>-->
<!--				<th>Ort</th>-->
<!--				<td><input type="text" name="ort" value="--><?php //echo htmlspecialchars($user['ort']) ?><!--" maxlength="255" autocomplete="off" /></td>-->
<!--			</tr>-->
<!--			<tr>-->
<!--				<th>Telefonnummer</th>-->
<!--				<td><input type="text" name="tel" value="--><?php //echo htmlspecialchars($user['tel']) ?><!--" maxlength="255" autocomplete="off" /></td>-->
<!--			</tr>-->
<!--            <th><h3>Kennwort-Bestätigung</h3></th>-->
<!--			<tr>-->
<!--				<th>Aktuelles Kennwort</th>-->
<!--				<td><input type="password" name="password[oldPw]" value="" maxlength="255"></td>-->
<!--			</tr>-->
<!--		</table>-->


	<?php if (!(@$_GET['ajax'] === "true")) {?>
		<table cellspacing="1" cellpadding="0">
		
			<tr>
				<td>
					<input type="submit" value="Speichern">
				</td>
			</tr>
		</table>
	<?php } ?>
</form>
<script>
$(function() {
	$('#form1').submit(function(e){
    	var self = this;
		e.preventDefault();
		$.ajax({
			type: "GET",
			url: "validate.php",
			data: "deutsche_plz="+$("#plz").val(),
			success: function(msg){
				if(msg == "deutsche_plz=OK"){
					self.submit();
				} else {
					$("#plz").focus();
					$("#plz").css('border', '2px solid red');
					alert("Anträge können leider nur aus Deutschland angenommen werden. \n\n Bitte prüfen Sie Ihre Angabe bei  PLZ .");
				}
			},
			error: function(msg){
				alert("error");
				self.submit(); // bei einem Fehler einfach dennoch senden...
			}
         });
	});
});
</script>