<?php
/**
 * Created by PhpStorm.
 * User: lvarnholt
 * Date: 08.07.2016
 * Time: 16:46
 */
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin,mitarbeiter', true);
require_once("functions/kind.php");
if(kind_zuAlt()){
    header("location: portal.php?action=config&delete=1");
}else {
    header("location: portal.php?action=config&delete=0");
}