<?php
//2010-06-09 
require_once("conf.php");
require_once("functions/db.php");
require_once("functions/core.php");
require_once("functions/user.php");
require_once("functions/log.php");

core_init();
user_check_login();
log_add('pageimpression', $_GET['action']);
$action_content = core_get_action_content($_GET['action']);

echo $action_content;