<?php 
//2013-11-17 
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin,mitarbeiter', true);
require_once("functions/antrag.php");
require_once("functions/geschenk.php");

if(isset($_GET['id']) && is_numeric($_GET['id']) ){
	$antragId = (int) $_GET['id'];
} else {
	exit;
}


$antrag = antrag_getById($antragId);
$geschenke = geschenk_getArrByAntragId($antragId);

$_SESSION["pdf_filename"] = $antrag["Vorgang_id"]."_".core_securechars($antrag["name"])."_".core_dateformat(time(), "d_m_Y-H_m_s").".pdf";
?>
<style>
	th{
		font-weight:bold;
	}
</style>
<table cellpadding="0" cellspacing="1">
  <tr>
  	<td width="450" ><?php echo CONF_APPNAME ?>, Oberhombach 1, 57537 Wissen</td>
  	<td width="170" style="border:1px solid black;" ><b><?php echo (int)$antrag["Vorgang_id"] ?> <?php echo htmlspecialchars($antrag["name"]) ?></b></td>
  </tr>
</table>
<p></p>
<p></p>
<p><br></p>

<table>
	<tr>
		<td style="font-size:1.2em">
			<b><?php echo htmlspecialchars($antrag["vorname"]." ".$antrag["name"]) ?></b><br>
			<?php echo htmlspecialchars($antrag["strasse"]) ?><br>
			<?php echo htmlspecialchars($antrag["plz"]." ".$antrag["ort"]) ?>
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
		<th width="190">Artikel Bez.</th>
		<th width="250">Kind</th>
		<th width="30"> </th>
		<th width="50"> </th>
		<th width="50">OK</th>
	</tr>
<?php foreach ($geschenke as $geschenk) {?>
	<tr>
		<td><b><?php echo htmlspecialchars($geschenk["bestellnummer"]) ?></b></td>
		<td><?php echo htmlspecialchars($geschenk["bezeichnung"]) ?></td>
		<td><?php echo htmlspecialchars($geschenk["vorname"]." ".$geschenk["name"]) ?></td>
		<td><?php echo $geschenk["geschlecht"]  ?></td>
		<td align="right"><?php echo $geschenk["alter"] ?>J</td>
		<td></td>
	</tr>
<?php } ?>
</table>
<p></p>
<table cellpadding="7"  border="1">
	<tr>
		<th width="80">Komplett</th>
		<th width="540">Notiz</th>
	</tr>
	<tr>
		<td><br><br></td>
		<td></td>
	</tr>
</table>
<p></p>
<table>
	<tr>
		<th width="100">Vorgang:</th>
		<td><?php echo (int)$antrag["Vorgang_id"] ?></td>
	</tr>
	<tr>
		<th>Eingang:</th>
		<td><?php echo core_dateformat($antrag["eingegangen_am"], "d.m.Y, H:m:s") ?></td>
	</tr>
	<tr>
		<th>Druck:</th>
		<td><?php echo core_dateformat(time(), "d.m.Y, H:m:s") ?></td>
	</tr>
	<tr>
		<th>Kontakt:</th>
		<td>
			<?php echo htmlspecialchars($antrag["vorname"]." ".$antrag["name"]) ?><br>
			<?php echo htmlspecialchars($antrag["tel"]) ?><br>
			<?php echo htmlspecialchars($antrag["email"]) ?>
		</td>
	</tr>
</table>
