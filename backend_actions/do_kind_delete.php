<?php
require_once("functions/kind.php");
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin,mitarbeiter', true);


if(isset($_POST['delete_kindId'])){
	$kind_id = (int)$_POST['delete_kindId'];
	kind_delete($kind_id);
}
//header("location: portal.php?action=user");