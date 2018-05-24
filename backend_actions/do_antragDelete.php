<?php
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin', true);
require_once("functions/antrag.php");

$antragId = (int) $_GET['id'];

//TODO: mindestens dann nicht löschen, wenn geschenke versendet sind
antrag_delete($antragId);

header("location: portal.php?action=antraege");
