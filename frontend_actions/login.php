<?php
if(isset($_SESSION["passwordChange"]["success"]) && $_SESSION["passwordChange"]["success"]) {
	?>
    <br>
    Sie haben Erfolgreich ihr Passwort geändert.<br>
<?php }
if(isset($_REQUEST["faulty"]) && $_REQUEST["faulty"]) {
	?>
	<br>
	Sie haben zu oft eine Falsche Benutzernamen/Passwort kombination eingeben!<br>
	Bitte ändern Sie ihr Passwort oder schreiben sie uns eine Email.<br>
<?php }
if(isset($_REQUEST["wrong"]) && $_REQUEST["wrong"]) {
	?>
	<br>
	Benutzername oder Passwort sind Falsch!<br>
<?php }
if(isset($_SESSION["emailChange"]["success"]) && $_SESSION["emailChange"]["success"]) {
	?>
	<br>
	Sie haben Erfolgreich ihre Email-Adresse geändert.<br>
	Sie erhalten eine Email zur Bestätigung ihrer neuen Email-Adresse.<br>
<?php }

?>
<html>
<body onload="document.forms[0].login.focus()" onkeydown="checkkey(event)">
<div id="space"><br /></div>
<div id="loginbox">
<noscript><p style="color:red; font-weight:bold">FEHLER: JavaScript muss aktiviert sein!</p></noscript>
    <form action="index.php?action=do_login_user" method="post" target="_top" class="noJQuery">
        <table cellspacing="3" cellpadding="0" border="0">
            <tr>
                <td>Benutzername:</td>
                <td><input type="search" name="login" maxlength="255"/></td>
            </tr>
            <tr>
                <td>Passwort:</td>
                <td><input type="password" name="password" maxlength="255"/></td>
            </tr>
            <tr>
                <td><a title="passwort vergessen" href="index.php?action=password">Passwort vergessen</a></td>
                <td><input type="button" value="Anmelden"onclick="document.forms[0].submit()"/></td>
            </tr>
        </table>
    </form>
	</div>
</body>
</html>

<script>
    function checkkey(e) {
         var key=e.keyCode? e.keyCode : e.charCode
         if(key == 13) {
             document.forms[0].submit();
         }
    }
</script>
<?php
unset($_SESSION["passwordChange"]["success"]);
unset($_SESSION["emailChange"]["success"]);