<?php
if(isset($_SESSION["register"]["success"]) && $_SESSION["register"]["success"]) {
	
	?>
	<br>
	Ok,<br>Ihre Registrierung war erfolgreich. <br>
	Sie erhalten bald eine Mail, um Ihre Emailadresse zu bestätigen. <br>
	Schauen Sie bitte auch in ihren Spam- oder Junkmail Ordner <br>
    <button id="redirectBtn">Weiter</button>
<script>
    $('#redirectBtn').click(function () {
        document.location.href = 'teilhabe.php?action=login'
    });
</script>
<?php }

unset($_SESSION["register"]["success"]);
