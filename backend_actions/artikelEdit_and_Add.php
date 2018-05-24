<?php
if (!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin', true);

require_once("functions/artikel.php");
require_once("functions/dateisammlung.php");



if(isset($_GET['id']) && $_GET['id'] != "ADD"){
	$id = (int) $_GET['id'];
	$artikel = artikel_getArr(false, $id);
	$artikel = $artikel[0];
	$bilder = dateisammlung_getById($artikel["Dateisammlung_id"]);
} else {
	$artikelFields = artikel_getValidFields();
	foreach ($artikelFields as $artikelField) {
		$artikel[$artikelField] = "";
	}

	
	$artikel["anzVerbraucht"] = "";
	$artikel["anzVerfuegbar"] = "";
	$artikel["id"] = "";

	$id = "ADD";
	$bilder = array();
}



?>
<style>
	fieldset{
		width:425px;
		float:left;
	}
</style>
	<form method="post" action="do_backend_action.php?action=do_artikelEdit_and_Add" enctype="multipart/form-data">
	<input type="hidden" name="id" value="<?php echo $id ?>">
	<fieldset>
		<legend>Verfügbarkeit</legend>
		<table cellspacing="1" cellpadding="0" >
			<tr>
				<th style="width:200px">Artikel ID</th>
				<td><?php echo $artikel["id"]?></td>
			</tr>
			<tr>
				<th>Anzahl gesamt</th>
				<td><input type="text" name="anzGesamt" value="<?php echo (int) $artikel["anzGesamt"] ?>" maxlength="5"  style="width:50px" autocomplete="off" /></td>
			</tr>
			<tr>
				<th style="width:200px">Anzahl verbraucht/ wartend</th>
				<td><?php echo $artikel["anzVerbraucht"]?></td>
			</tr>
			<tr>
				<th style="width:200px">Anzahl verfügbar</th>
				<td><?php echo $artikel["anzVerfuegbar"]?></td>
			</tr>
			</table>
	</fieldset>

	<fieldset>
		<legend>Preis und Bescheibung</legend>
		<table cellspacing="1" cellpadding="0" >
			<tr>
				<th>Preis</th>
				<td><input type="text" name="preis" value="<?php echo htmlspecialchars(str_replace(".", ",", $artikel["preis"])) ?>" maxlength="255" style="width:50px" autocomplete="off" /> €</td>
			</tr>
			<tr>
				<th style="width:200px">Bestellnummer</th>
				<td><input type="text" name="bestellnummer" value="<?php echo htmlspecialchars($artikel["bestellnummer"]) ?>" maxlength="255" style="width:200px" autocomplete="off" /></td>
			</tr>
			<tr>
				<th style="width:200px">Bezeichnung</th>
				<td><input type="text" name="bezeichnung" value="<?php echo htmlspecialchars($artikel["bezeichnung"]) ?>" maxlength="255" style="width:200px" autocomplete="off" /></td>
			</tr>
			<tr>
				<th>Beschreibung</th>
				<td><textarea name="beschreibung" style="width:100%;"><?php echo htmlspecialchars($artikel["beschreibung"])?></textarea></td>
			</tr>
		</table>
	</fieldset>
	<fieldset>
		<legend>Bilder</legend>
		<table cellspacing="1" cellpadding="0" >
		
		<?php 
		//TODO: evtl Bilder anders behandeln als dateianhänge des frontend, denn die Bilder hier könnten ja im WEB liegen und direkt eingebunden werden..
		if(count($bilder) > 0){
			foreach ($bilder as $bild) { ?>
				<tr>
					<td>
						<div style="width:400px;height:400px; overflow:scroll"><img src="bin.php?id=<?php echo $bild['id'] ?>"></div>
					</td>
				</tr>
				<tr>
					<td>
						<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo CONF_FILEUPLOAD_MAXSIZE ?>" />
						<input type="file" name="bild_<?php echo $bild['id']?>" accept="image/*" />
					</td>
				</tr>
		<?php 
			}
		} else { //TODO: wenn man mehrere Bilder pro artikel zulassen will, einfach folgenden else-zweig immer enblenden :)
		?>
			<tr>
				<td>
					<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo CONF_FILEUPLOAD_MAXSIZE ?>" />
					<input type="file" name="bild_ADD" accept="image/*" />
				</td>
			</tr>
		<?php }	?>
		
		</table>
	</fieldset>
</form>