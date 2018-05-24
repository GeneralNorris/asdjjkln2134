<?php 
//2013-11-17 
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin,mitarbeiter', true);

require_once("functions/spende.php");
$spendeArr = spende_getArr();
$spendeSummeArr = spende_getSummeArr();
/*
	<tr>
		<th colspan="11" class="pagernav">
			<div style="float:left"><?php $pager->display_info(); ?></div>
			<div style="float:right"><?php $pager->display_nav(); ?></div>
		</th>
	</tr>
*/
if(isset($_SESSION["spende_add"]) && isset($_SESSION["spende_add"]["status_ok"]) && $_SESSION["spende_add"]["status_ok"]) {?>
    <h2>Spende Erfolgreich hinzugefügt!</h2>
<?php } elseif(isset($_SESSION["spende_add"]) && isset($_SESSION["spende_add"]["noPresent"]) && $_SESSION["spende_add"]["noPresent"]) { ?>
    <br>
    <span style="color:red; font-weight:bold">Es ist ein Fehler aufgetreten,<br>
	Bitte wählen Sie mindestens ein Geschenk aus, das Sie spenden möchten.</span>
    <br><br>
<?php } elseif(isset($_SESSION["spende_add"])){ ?>
    <br>
    <span style="color:red; font-weight:bold">Es ist ein Fehler aufgetreten,<br>
	bitte füllen Sie Ihre Kontaktdaten vollständig aus und versuchen Sie es erneut.</span>
    <br><br>
<?php }
unset($_SESSION["spende_add"]);
?>
<div class="actionHead">
	<h1>Spenden</h1>
	<div class="buttonbar">
		<a style="margin-right:9px" title="Liste drucken"  href="#" id="print"><img src="backend_theme/images/icons/printer.png"></a>
		<a style="margin-right:9px" title="CSV-Export"  target="_blank" href="csv.php?action=csvExportSpenden" ><img src="backend_theme/images/icons/page_excel.png"></a>
        <a title="Großspende hinzufügen" href="portal.php?action=specialSpendeAdd"><button type="button" style="background-color: green">Großspende hinzufügen</button></a>
    </div>
	<span class="noprint">
		<span style="padding-right: 25px">
			<span class="count" title="Spenden gesamt">&#8721; <?php echo $spendeSummeArr["summe_total"];             ?> €</span>
			<span class="count" title="Spenden noch nicht geprüft/bezahlt" ><img src="backend_theme/images/icons/flag_red.png" height="12"/><?php echo $spendeSummeArr["summe_nicht_bestätigt"]; ?> €</span> 
			<span class="count" title="Spenden als bezahlt markiert" ><img src="backend_theme/images/icons/flag_green.png" height="12" /><?php echo $spendeSummeArr["summe_bestätigt"]; ?> €</span>
		</span>
		Suche: <input id="pattern" title="Suche in allen Spalten" autocomplete="off">
	</span>	
</div>

<table cellpadding="0" cellspacing="0" id="dataTable" class="tablesorter">
	<thead>
	<tr>
		<th width="20" sortcolumn='status' class="noprint"></th>
		<th width="35" sortcolumn='vorgang'>Spenden- Vorg.</th>
        <th width="35" sortcolumn='summe'>Spenden- summe</th>
        <th width="35" sortcolumn='antrag_vorgang'>Antrag- Vorg.</th>
        <th            sortcolumn='kind_geschenk'>Kind / Geschenk</th>
        <th width="50" sortcolumn='eingegangen_am'>Eintrag vom</th>
		<th width="70" sortcolumn='eingegangen_am'>geprüft/ bezahlt am</th>

		<th width="100" sortcolumn='name'>Nachname</th>
		<th sortcolumn='kontakt'>Kontakt</th>
		
		<th width="60" class="noprint"></th>
	</tr>
	</thead>
	<tbody>
<?php 
foreach ($spendeArr as $spende) {
	$status = SPENDE_STATUS_INPRUEFUNG;
	$statusImg = "flag_red.png";
	$statusTxt = "Die Spende ist noch nicht geprüft/bezahlt";
	
	if($spende["zahlung_bestaetigt_von"]>0){
		$status = SPENDE_STATUS_BEZAHLT;
		$statusImg = "flag_green.png";
		$statusTxt = "Die Spende ist als bezahlt markiert";
	}
    $detailArr = spende_getDetailArr($spende["Vorgang_id"]);
?>
	<tr id="<?php echo $spende["Vorgang_id"] ?>">
		<td style="text-align:center;color:transparent" class="noprint"><?php echo $status?><img src="backend_theme/images/icons/<?php echo $statusImg ?>" title="<?php echo $statusTxt ?>" ></td>
		<td><?php echo $spende["Vorgang_id"] ?></td>
		<td><span style="font-weight:bold;<?php echo (($status == SPENDE_STATUS_BEZAHLT)? "":"color:red;")?>"><?php echo  str_replace(".", ",", $spende["summe"]) ?> €</span></td>
        <td><?php foreach ($detailArr as $detail){ echo $detail['antrag_vorgang_id'] . "<br />";}?></td>
        <td><?php foreach ($detailArr as $detail){ echo $detail['kind_vorname'] . " / " . $detail['artikel_bezeichnung'] . "<br />";}?></td>
        <td><?php echo core_dateformat($spende["eingegangen_am"]) ?></td>
		<td><?php echo ((!empty($spende["zahlung_bestaetigt_am"]))? core_dateformat($spende["zahlung_bestaetigt_am"]):'') ?></td>
		<td><?php echo htmlspecialchars($spende["name"]) ?></td>
		<td>
			<a href="mailto:<?php echo htmlspecialchars($spende["email"]) ?>?subject=<?php echo CONF_APPNAME?>&body=Hallo <?php echo htmlspecialchars($spende["vorname"]." ".$spende["name"])?>"><?php echo htmlspecialchars($spende["email"])?></a>
			<br>
			<?php echo htmlspecialchars($spende["tel"]) ?>
			<br>
			<br>
			<?php echo htmlspecialchars($spende["vorname"])." ".htmlspecialchars($spende["name"]) ?><br>
			<?php echo htmlspecialchars($spende["plz"])." ".htmlspecialchars($spende["ort"]) ?><br>
			<?php echo htmlspecialchars($spende["strasse"]) ?>
		</td>	
		<td style="text-align:right" class="noprint">
			<?php if($status != SPENDE_STATUS_BEZAHLT){ ?>
				<a title="Spender hat bezahlt. Den(Die) Geschenk(e)-Empfänger per Mail informieren" href="do_backend_action.php?action=do_spendeFreigabe&id=<?php echo $spende['id'] ?>" class="freigabe" style="margin-right:9px"><img src="backend_theme/images/icons/tick.png"></a>
				<a style="margin-right:9px" title="ACHTUNG! Spende als ungültig betrachten, LÖSCHEN und Geschenke für neuen Spender freigeben" href="do_backend_action.php?action=do_spendeDelete&id=<?php echo $spende['Person_id']?>" class="delete"><img src="backend_theme/images/icons/delete.png"></a>
			<?php } ?>
			<a style="margin-right:9px" title="drucken" href="pdf.php?action=spende_pdfsource&id=<?php echo $spende['id']?>" class="" target="_blank"><img src="backend_theme/images/icons/printer.png"></a>
		</td>
	</tr>
<?php } ?>
	</tbody>
    <tfoot>
    <tr>
        <td colspan="9"></td>
        <td  style="background-color:#D7DDF0">
        </td>
    </tr>
    </tfoot>
</table>