<?php
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin,mitarbeiter', true);
require_once("functions/antrag.php");

$geschenkId = (int) $_GET['id'];

//TODO: mindestens dann nicht löschen, wenn geschenke versendet sind
geschenk_delete($geschenkId);

header("location: portal.php?action=antraege");
