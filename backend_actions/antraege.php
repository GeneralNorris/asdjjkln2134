<?php 
//2013-11-17 
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin,mitarbeiter', true);

require_once("functions/antrag.php");
if(isset($_GET['status'])){
	if($_GET['status'] == 0){
		$antragArr = antrag_getArrNichtFreigegeben();
	}
	if($_GET['status'] == 1){
		$antragArr = antrag_getArrWartend();
	}
	if($_GET['status'] == 2){
		$antragArr = antrag_getArrGespendetNichtBezahlt();
	}
	if($_GET['status'] == 3){
		$antragArr = antrag_getArrGespendetUndBezahlt();
	}
}else{
	$antragArr = antrag_getArr();
}
$geschenkeCountArr = geschenk_getCountArr();

/*
	<tr>
		<th colspan="11" class="pagernav">
			<div style="float:left"><?php $pager->display_info(); ?></div>
			<div style="float:right"><?php $pager->display_nav(); ?></div>
		</th>
	</tr>
*/

?>
<div class="actionHead">
	<h1>Anträge</h1>
	<div class="buttonbar">
		<a style="margin-right:9px" title="Liste drucken"  href="#" id="print"><img src="backend_theme/images/icons/printer.png"></a>
		<a style="margin-right:9px" title="CSV-Export-Alt"  target="_blank" href="csv.php?action=csvExportAntraege" ><img src="backend_theme/images/icons/page_excel.png"></a>
		<a title="Neuen Antrag hinzufügen" href="do_backend_action.php?action=antragEdit_and_Add" class="add"><img src="backend_theme/images/icons/application_form_add.png"></a>
		<a style="margin-right:9px" title="CSV-Export-Versand"  target="_blank" href="csv.php?action=csvExportAntraegeVersand" ><img src="backend_theme/images/icons/page_excel.png"></a>
        <a style="margin-right:9px" title="CSV-Export-Kinder"  target="_blank" href="csv.php?action=csvExportKinder" ><img src="backend_theme/images/icons/page_excel.png"></a>
	</div>
	<span class="noprint">
		<span style="padding-right: 25px">
			<a href="portal.php?action=antraege"><span class="count" title="<?php echo $geschenkeCountArr["total"];?> Geschenke gesamt">&#8721; <?php echo $geschenkeCountArr["total"];?></span></a>
			<a href="portal.php?action=antraege&status=0"><span class="count" title="<?php echo $geschenkeCountArr["nicht_freigegeben"];?> Geschenke noch nicht freigegeben"><img src="backend_theme/images/icons/flag_red.png" height="12" /><?php echo $geschenkeCountArr["nicht_freigegeben"];?></span></a>
			<a href="portal.php?action=antraege&status=1"><span class="count" title="<?php echo $geschenkeCountArr["wartend"];?> Geschenke sind zur Spende freigegeben"><img src="backend_theme/images/icons/flag_yellow.png" height="12"/><?php echo $geschenkeCountArr["wartend"];?></span></a>
			<a href="portal.php?action=antraege&status=2"><span class="count" title="<?php echo $geschenkeCountArr["gespendet_nicht_bezahlt"];?> Geschenke sind gespendet, aber noch nicht bezahlt"><img src="backend_theme/images/icons/flag_blue.png" height="12" /><?php echo $geschenkeCountArr["gespendet_nicht_bezahlt"];?></span></a>
			<a href="portal.php?action=antraege&status=3"><span class="count" title="<?php echo $geschenkeCountArr["gespendet_bezahlt"];?> Geschenke wurden gespendet und bezahlt" ><img src="backend_theme/images/icons/flag_green.png" height="12" /><?php echo $geschenkeCountArr["gespendet_bezahlt"];?></span></a>
		</span>
		Suche: <input id="pattern" title="Suche in allen Spalten &#10; &#10;Geburtstag wird mit g20.05.2013 gefunden" autocomplete="off">
	</span>
</div>

<table cellpadding="0" cellspacing="0" id="dataTable" class="tablesorter">
	<thead>
	<tr>
		<th width="20" sortcolumn="status" class="noprint"></th>
		<th width="35" sortcolumn="vorgang">Vorg.</th>
		<th width="50" sortcolumn="eingegangen_am">Datum</th>

		<th width="100" sortcolumn="name">Nachname</th>
		<th width="200" sortcolumn="kontakt">Kontakt</th>
		<th sortcolumn='geschenke'>Wünsche/Geschenke</th>

		<th width="50" sortcolumn="datei" class="noprint">Anhang</th>
		
		<th width="90" class="noprint"></th>
	</tr>
	</thead>
	<tbody>
<?php 
foreach ($antragArr as $antrag) {
	$dateien = dateisammlung_getById($antrag["Dateisammlung_id"]);

	if(!@empty($dateien[0]["id"])){
		$dateiLink = '<a href="bin.php?id='.$dateien[0]["id"].'" target="_blank" title="Anhang anschauen"><img src="backend_theme/images/icons/attach.png"></a>';
	} else {
		$dateiLink ='';
	}

	//status ermitteln
	$statusTxt = 'Alle Geschenke sind gespendet und bezahlt';
	$statusImg = 'flag_green.png';
	$status = ANTRAG_STATUS_GESPENDET_UND_BEZAHLT;
	$geschenke = geschenk_getArrByAntragId($antrag["Person_id"]);
    $tempStatus = 0;
	foreach ($geschenke as $geschenk) {

		if($geschenk["freigabe_fuer_spender_von"] < 1) { //TODO: ANTRAG STATUS ROT! WENN mindestens 1 Geschenk ROT
			$statusImg = "flag_red.png";
			$statusTxt = "Der Antrag ist noch nicht geprüft/genehmigt";
			$status = ANTRAG_STATUS_INPRUEFUNG;
            $tempStatus = 1;

		}
		
		$spende = spende_getById($geschenk["Spende_Person_id"]);
		
		if($geschenk["Spende_Person_id"] < 1 && $tempStatus == 0){
			$statusImg = "flag_yellow.png";
			$statusTxt = "Mindestens ein Geschenk wartet noch auf einen Spender";
			$status = ANTRAG_STATUS_WARTEND;
			$tempStatus = 2;

		} 
		
		if($geschenk["Spende_Person_id"] > 1 && $spende["zahlung_bestaetigt_von"] < 1 && $tempStatus == 0){
			$statusImg = "flag_blue.png";
			$statusTxt = "Mindestens ein Geschenk ist noch nicht bezahlt";
			$status = ANTRAG_STATUS_GESPENDET;

		}

	}
	if($tempStatus == 1){
		$statusImg = "flag_red.png";
		$statusTxt = "Der Antrag ist noch nicht geprüft/genehmigt";
		$status = ANTRAG_STATUS_INPRUEFUNG;
	}else if($tempStatus == 2){
		$statusImg = "flag_yellow.png";
		$statusTxt = "Mindestens ein Geschenk wartet noch auf einen Spender";
		$status = ANTRAG_STATUS_WARTEND;
	}
	
	
?>
	<tr id="<?php echo $antrag["Vorgang_id"] ?>">
		<td style="text-align:center;color:transparent" class="noprint"><?php echo $status ?><img src="backend_theme/images/icons/<?php echo $statusImg ?>" title="<?php echo $statusTxt ?>" ></td>
		<td><?php echo $antrag["Vorgang_id"] ?></td>
		<td><?php echo core_dateformat($antrag["eingegangen_am"]) ?></td>
		<td><?php echo htmlspecialchars($antrag["name"]) ?></td>
		<td>
			Mail: <a href="mailto:<?php echo htmlspecialchars($antrag["email"]) ?>?subject=<?php echo CONF_APPNAME?>&body=Hallo <?php echo htmlspecialchars($antrag["vorname"]." ".$antrag["name"])?>"><?php echo htmlspecialchars($antrag["email"])?></a>
			<br>
			Tel: <?php echo htmlentities($antrag["tel"]) ?>
			<br>
			<br>
			<?php echo htmlspecialchars($antrag["vorname"])." ".htmlspecialchars($antrag["name"]) ?><br>
			<?php echo htmlentities($antrag["plz"])." ".htmlspecialchars($antrag["ort"]) ?><br>
			<?php echo htmlspecialchars($antrag["strasse"]) ?>
			
		</td>
		<td><?php echo geschenk_getHTMLByGeschenkeArr($geschenke, $antrag['id']) ?></td>
		
		<td style="text-align:center" class="noprint"><?php echo $dateiLink ?></td>
		
		<td style="text-align:right" class="noprint">
			<?php if($status == ANTRAG_STATUS_INPRUEFUNG){ ?>
				<a title="Antrag genehmigen und Geschenke zur Spende freigeben" href="do_backend_action.php?action=do_antragFreigabe&id=<?php echo $antrag['id'] ?>" class="freigabe" style="margin-right:9px"><img src="backend_theme/images/icons/tick.png"></a>
			<?php } ?>
			
			<a style="margin-right:9px" title="Kommissionsschein drucken" href="pdf.php?action=antrag_pdfsource&id=<?php echo $antrag['id']?>" class="" target="_blank"><img src="backend_theme/images/icons/printer.png"></a>
			
			<a style="margin-right:9px" title="Antrag editieren" href="do_backend_action.php?action=antragEdit_and_Add&id=<?php echo $antrag['id']?>" class="edit"><img src="backend_theme/images/icons/application_form_edit.png"></a>
			<?php if (user_check_access('admin', false)) { ?>
				<a style="margin-right:9px" title="ACHTUNG! Antrag + Geschenke löschen und die Artikel wieder freigeben" href="do_backend_action.php?action=do_antragDelete&id=<?php echo $antrag['Person_id']?>" class="delete"><img src="backend_theme/images/icons/application_form_delete.png"></a>
			<?php } ?>
		</td>
	</tr>
<?php } ?>
	</tbody>
	<tfoot>
	</tfoot>
</table>