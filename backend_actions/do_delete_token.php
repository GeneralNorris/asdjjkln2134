<?php
/**
 * Created by PhpStorm.
 * User: lvarnholt
 * Date: 29.08.2016
 * Time: 14:16
 */
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin,mitarbeiter', true);
require_once("functions/tokens.php");
token_delete();
header("location: portal.php?action=config");
