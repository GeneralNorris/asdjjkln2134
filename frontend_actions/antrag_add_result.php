<?php 
require_once("functions/antrag.php");

if(config_get("antraege_input_enabled",0) == 0 && config_get("antraege_input_enabled_special",0) == 0) {
	exit;
}

if(isset($_SESSION["antrag_add"]["status_ok"]) && $_SESSION["antrag_add"]["status_ok"]) {
	$antrag = antrag_getById($_SESSION["antrag_add"]["antrag_id"]);
?>
	<br>
	Ok,<br>wir haben Ihre Geschenkewünsche erhalten und melden uns, wenn alles geprüft ist. <br>
	Sollten Sie uns noch Unterlagen zukommen lassen wollen, können Sie dies jederzeit gern per Post oder per Email tun.<br>
	Bitte geben Sie dazu immer Ihre Vorgangsnummer: <span style="color:red; font-weight:bold"><?php echo htmlspecialchars($antrag["Vorgang_id"])?></span> mit an.
	<br><br>
	<a href="index.php?action=overview">Fortfahren</a>

<?php  } else { ?>
	<br>
	<span style="color:red; font-weight:bold">Es ist ein Fehler aufgetreten,<br>
	bitte füllen Sie die Geschenkwünsche vollständig aus und versuchen Sie es erneut.</span>
	<br><br>
	
	
<?php }

unset($_SESSION["antrag_add"]["status_ok"]);
