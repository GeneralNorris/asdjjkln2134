<?php 
//2014-06-04 
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin,mitarbeiter', true);
require_once("functions/spende.php");
require_once("functions/geschenk.php");

if(isset($_GET['id']) && is_numeric($_GET['id']) ){
	$spendeId = (int) $_GET['id'];
} else {
	exit;
}


$spende = spende_getById($spendeId);
$geschenke = geschenk_getArrBySpendeId($spendeId);

$_SESSION["pdf_filename"] = $spende["Vorgang_id"]."_".core_securechars($spende["name"])."_".core_dateformat(time(), "d_m_Y-H_m_s").".pdf";
?>
<style>
	th{
		font-weight:bold;
	}
</style>
<table cellpadding="0" cellspacing="1">
  <tr>
  	<td width="450" ><?php echo CONF_APPNAME ?>, Oberhombach 1, 57537 Wissen</td>
  	<td width="170" style="border:1px solid black;" ><b>S<?php echo (int)$spende["Vorgang_id"] ?> <?php echo htmlspecialchars($spende["name"]) ?></b></td>
  </tr>
</table>
<p></p>
<p></p>
<p><br></p>
<table>
	<tr>
		<td style="font-size:1.2em">
			<b><?php echo htmlspecialchars($spende["vorname"]." ".$spende["name"]) ?></b><br>
			<?php echo htmlspecialchars($spende["plz"]." ".$spende["ort"]) ?><br>
			<?php echo htmlspecialchars($spende["strasse"]) ?>
		</td>
	</tr>
</table>
<p></p>
<p></p>
<p></p>
<p></p>
<table cellpadding="7"  border="1">
	<tr>
		<th width="50">Nr.</th>
		<th width="520">Artikel Bez.</th>
		<th width="55">Preis</th>
	</tr>
<?php foreach ($geschenke as $geschenk) {?>
	<tr>
		<td><b><?php echo htmlspecialchars($geschenk["bestellnummer"]) ?></b></td>
		<td><?php echo htmlspecialchars($geschenk["bezeichnung"]) ?></td>
		<td><?php echo $geschenk["preis"] ?></td>
	</tr>
<?php } ?>
	<tr>
		<td colspan="2" align="right"><b>Summe</b></td>
		<td><b><?php echo $spende["summe"] ?></b></td>
	</tr>
</table>
<p></p>
<table>
	<tr>
		<th width="100">Vorgang:</th>
		<td><?php echo (int)$spende["Vorgang_id"] ?></td>
	</tr>
	<tr>
		<th>Eingang:</th>
		<td><?php echo core_dateformat($spende["eingegangen_am"], "d.m.Y, H:m:s") ?></td>
	</tr>
	<tr>
		<th>Bestätigt:</th>
		<td><?php echo core_dateformat($spende["zahlung_bestaetigt_am"], "d.m.Y, H:m:s") ?></td>
	</tr>
	<tr>
		<th>Druck:</th>
		<td><?php echo core_dateformat(time(), "d.m.Y, H:m:s") ?></td>
	</tr>
	<tr>
		<th>Kontakt:</th>
		<td>
			<?php echo htmlspecialchars($spende["vorname"]." ".$spende["name"]) ?><br>
			<?php echo htmlspecialchars($spende["plz"]." ".$spende["ort"]) ?><br>
			<?php echo htmlspecialchars($spende["strasse"]) ?>
			<?php echo htmlspecialchars($spende["tel"]) ?><br>
			<?php echo htmlspecialchars($spende["email"]) ?>
		</td>
	</tr>
</table>