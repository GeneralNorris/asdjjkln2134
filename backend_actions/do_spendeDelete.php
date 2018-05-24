
<?php
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin,mitarbeiter', true); 

require_once("functions/spende.php");


if(!@empty($_GET['id'])){
	spende_delete((int) $_GET['id']);
}

header("location: portal.php?action=spenden");
exit;

