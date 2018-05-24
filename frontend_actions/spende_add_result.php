<?php 
if(config_get("spenden_input_enabled", 0) == 0){
	header("location: index.php");
}

require_once("functions/spende.php");

if(isset($_SESSION["spende_add"]) && isset($_SESSION["spende_add"]["status_ok"]) && $_SESSION["spende_add"]["status_ok"]){
	$spende = spende_getById($_SESSION["spende_add"]["spende_id"]);
?>
	
	
<!-- Einleitung -->	
	<div align="center">
		<h2>Vielen Dank für Ihre Spendenzusage</h2>
		Wir bedanken uns auch im Namen Ihres Kindes für Ihre Spende. Sobald Ihre Spende eingegangen ist, kaufen wir das Geschenk für dieses Kind, verpacken es weihnachtlich, fügen einen  Süssigkeiten-Teller hinzu und versenden es rechtzeitig zum Weihnachtsabend.<br>
		<br>Ihre Geschenkspende beinhaltet auch die Verpackungskosten und den Versand des Geschenkes. Ihre steuerlich relevante Spendenbescheinigung erhalten Sie automatisch im Februar des Folgejahres. 
		<br><br>
	
		<h2>Spenden per Überweisung</h2>
		Bitte überweisen Sie Ihre Spende von <span style="color:red; font-weight:bold"><?php echo str_replace(".", ",", $spende["summe"]) ?></span> € innerhalb von 3 Tagen mit Spendennummer: <span style="color:red; font-weight:bold"><?php echo htmlspecialchars($spende["Vorgang_id"])?></span>
		<br><br>
		<table>
			<tr align="center"><td>Kontoinhaber:<br><b>Kinderarmut in Deutschland e.V.</b></td></tr>
			<tr align="center"><td>Spendenkonto:<br><b>400199005</b></td></tr>
			<tr align="center"><td>BLZ:<br><b>57361476</b></td></tr>
			<tr align="center"><td>Bank:<br><b>Volksbank Gebhardshain</b></td></tr>
			<tr align="center"><td>IBAN:<br><b>DE81 5736 1476 0400 1990 05</b></td></tr>
			<tr align="center"><td>BIC:<br><b>GENODED1GBS</b></td></tr>
		</table>
		<br>
	</div>
	
	
<!-- PayPal -->	
	<div align="center">
		<h2>Spenden per PayPal</h2>
		<b>Einfach und schnell:</b><br>Sie spenden mit zwei Klicks.
		Spenden ist bei PayPal immer <b>kostenlos</b>.
		<br><br>

	<!-- PayPal Logo -->
		<table cellspacing="0" cellpadding="10" border="0" align="center">
			<tbody>
				<tr>
					<td>
						<div align="center">
							<a onclick="window.open('https://www.paypal.com/de/webapps/mpp/paypal-popup','WIPaypal','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=780, height=550'); return false;" title="So funktioniert PayPal" href="javascript:void(0)"><img border="0" alt="PayPal Logo" src="https://www.paypalobjects.com/webstatic/de_DE/i/de-pp-logo-100px.png"></a>
							<form target="_blank" method="post" action="https://www.paypal.com/cgi-bin/webscr">
								<input type="hidden" value="_s-xclick" name="cmd">
								<input type="hidden" name="item_name" value="Spendennummer:<?php echo $spende["Vorgang_id"]; ?>">
								<input type="hidden" value="3HXK7DM3AD9LW" name="hosted_button_id">
								<div align="center"><input type="image" border="0" alt="Jetzt einfach, schnell und sicher online bezahlen &ndash; mit PayPal." name="submit" src="https://www.paypalobjects.com/de_DE/DE/i/btn/btn_donateCC_LG.gif"></div>
								<!-- img width="1" height="1" border="0" src="https://www.paypalobjects.com/de_DE/i/scr/pixel.gif" alt="" -->
							</form>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	<!-- PayPal Logo -->
	</div>
	
	<br><br>

	
<?php  } else if(isset($_SESSION["spende_add"]) && isset($_SESSION["spende_add"]["noPresent"]) && $_SESSION["spende_add"]["noPresent"]) { ?>
	<br>
	<span style="color:red; font-weight:bold">Es ist ein Fehler aufgetreten,<br>
	Bitte wählen Sie mindestens ein Geschenk aus, das Sie spenden möchten.</span>
	<br><br>
<?php } else { ?>
	<br>
	<span style="color:red; font-weight:bold">Es ist ein Fehler aufgetreten,<br>
	bitte füllen Sie Ihre Kontaktdaten vollständig aus und versuchen Sie es erneut.</span>
	<br><br>
<?php }

unset($_SESSION["spende_add"]);
