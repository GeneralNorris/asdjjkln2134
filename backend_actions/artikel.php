<?php 
//2013-11-17
require_once("functions/artikel.php");
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin', true);

if(isset($_SESSION["artikelDelete"]) && $_SESSION["artikelDelete"] == "success") {   ?>
    <br>
    <p style="color: green; font-size: 14px"> Artikel wurde Erfolgreich gelöscht!</p>
<?php  } else if(isset($_SESSION["artikelDelete"]) && $_SESSION["artikelDelete"]=="antrag_spende"){   ?>
    <br>
    <p style="color: red; font-size: 14px"> Artikel konnte nicht gelöscht werden, da bereits Anträge und/oder Spenden vorhanden sind. Bitte löschen Sie diese vorher!</p>
<?php  } else if(isset($_SESSION["artikelDelete"]) && $_SESSION["artikelDelete"]=="config"){     ?>
    <br>
    <p style="color: red;font-size: 14px"> Artikel konnte nicht gelöscht werden, da der Geschenkeshop oder die Spendenannahme aktivert sind.
                                                        Bitte deaktivieren Sie alle Checkboxen unter Konfiguration!</p>
<?php } else if(isset($_SESSION["artikelDelete"]) && $_SESSION["artikelDelete"]=="false"){     ?>
    <br>
    <p style="color: red;font-size: 14px"> Unbekannter Fehler: Artikel konnte nicht gelöscht werden!</p>
<?php }

$artikelArr = artikel_getArr();

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
	<h1>Artikel</h1>
	<div class="buttonbar">
		<a style="margin-right:9px" title="Liste drucken"  href="#" id="print"><img src="backend_theme/images/icons/printer.png"></a>
		<a style="margin-right:9px" title="CSV-Export"  target="_blank" href="csv.php?action=csvExportArtikel" ><img src="backend_theme/images/icons/page_excel.png"></a>
		<a title="Neuen Artikel hinzufügen" href="do_backend_action.php?action=artikelEdit_and_Add" class="add"><img src="backend_theme/images/icons/application_form_add.png"></a>
	</div>
	<span class="noprint">Suche: <input id="pattern" title="Suche in allen Spalten" autocomplete="off"></span>		
</div>

<table cellpadding="0" cellspacing="0" id="dataTable" class="tablesorter">
	<thead>
	<tr>
		<th width="45" sortcolumn='id'>ID</th>
		<th width="45" sortcolumn='bestellnummer'>Best.-Nr.</th>
		<th width="45" sortcolumn='preis'>Preis[€]</th>
		<th width="50" sortcolumn='anzgesamt'>Anzahl gesamt</th>
		<th width="50" sortcolumn='anzverbraucht'>Anzahl verbraucht/ in Prüfung</th>
		<th width="50" sortcolumn='anzverfuegbar'>Anzahl verfügbar</th>
		<th width="50" sortcolumn='bezeichnung'>Bezeichnung</th>
		<th sortcolumn='beschreibung' class="noprint">Beschreibung</th>
		<th width="20" class="noprint"></th>
        <th width="20" class="noprint"></th>
	</tr>
	</thead>
	<tbody>
<?php 
foreach ($artikelArr as $artikel) {
	$alert = '';
	if($artikel["anzVerfuegbar"] < 0){
		$alert = 'class="alert"';
	}
?>
	<tr <?php echo $alert ?> id="<?php echo $artikel["id"] ?>">
		<td><?php echo $artikel["id"] ?></td>
		<td><?php echo $artikel["bestellnummer"] ?></td>
		<td><?php echo str_replace(".", ",", $artikel["preis"]) ?></td>
		<td><?php echo $artikel["anzGesamt"] ?></td>
		<td><?php echo $artikel["anzVerbraucht"] ?></td>
		<td <?php echo $alert ?>><?php echo $artikel["anzVerfuegbar"] ?></td>
		<td><?php echo $artikel["bezeichnung"] ?></td>
		<td class="noprint"><?php echo $artikel["beschreibung"] ?></td>
		<td style="text-align:center" class="noprint">
			<a title="Artikel editieren" href="do_backend_action.php?action=artikelEdit_and_Add&id=<?php echo $artikel['id']?>" class="edit"><img src="backend_theme/images/icons/application_form_edit.png"></a>
		</td>
        <td>
            <a title="Artikel löschen" href="do_backend_action.php?action=do_artikelDelete&deleteArtikelID=<?php echo $artikel['id']?>" class="deleteUser">löschen</a>
        </td>
	</tr>
<?php } ?>
	</tbody>
	<tfoot>
	</tfoot>
</table>
<?php
unset($_SESSION["artikelDelete"]);