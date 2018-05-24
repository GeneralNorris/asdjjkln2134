<?php //2010-07-12 
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin,mitarbeiter', true);
require_once("functions/antrag.php");

if(isset($_GET['id'])){
	
	$antragId = (int) $_GET['id'];
	antrag_setFreigabe($antragId);
	mail_send_antragFreigeschaltet($antragId);
	
}
header("location: portal.php?action=antraege");
exit();