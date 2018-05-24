<?php 
require_once("conf.php");
require_once("functions/db.php");
require_once("functions/core.php");
require_once("functions/log.php");
require_once("functions/user.php");
require_once("functions/antrag.php");
require_once("functions/geschenk.php");
require_once("functions/artikel.php");

core_init();
user_check_login();
log_add('pageimpression', $_GET['action']);

if( $_GET['action'] === "csvExportSpenden"){
	$spendeArr = spende_getArr();
	foreach ($spendeArr as &$antrag) {
		$antrag["spenden"] = "";
		$geschenke = geschenk_getArrBySpendeId($antrag['id']);
		foreach ($geschenke as $geschenk) {
			$antrag["spenden"] .= implode(",", $geschenk)."\n";
		}
	}
	core_printCSV($spendeArr);
	
} elseif ($_GET['action'] === "csvExportAntraege"){
    $antragArr = antrag_getArr();
	foreach ($antragArr as &$antrag) {
		$antrag["wuensche"] = "";
		$geschenke = geschenk_getArrByAntragId($antrag['id']);
		foreach ($geschenke as $geschenk) {
			$antrag["wuensche"] .= implode(",", $geschenk)."\n";
		}
	}
    core_printCSV($antragArr);

} elseif ($_GET['action'] === "csvExportAntraegeVersand"){
    $antragArr = geschenk_getArr();
    core_printCSV($antragArr);
} elseif ($_GET['action'] === "csvExportArtikel"){
	$antragArr = artikel_getArr();
	core_printCSV($antragArr);
} elseif ($_GET['action'] === "csvExportKinder"){
    $kindArr = kind_getArr();
    core_printCSV($kindArr);
}