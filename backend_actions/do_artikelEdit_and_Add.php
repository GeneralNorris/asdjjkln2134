<?php
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin', true);
require_once("functions/artikel.php");
require_once("functions/dateisammlung.php");


if(isset($_POST['id'])){
	if($_POST['id'] == "ADD"){
		$dateisammlungId = dateisammlung_add($_FILES, 0);
		artikel_add($_POST,$dateisammlungId);
	} else {
		$oldArtikel = artikel_getArr(false, (int)$_POST['id']);
		dateisammlung_update($oldArtikel[0]['Dateisammlung_id'], $_FILES, 0);
		artikel_update($_POST);
	}
}

header("location: portal.php?action=artikel");