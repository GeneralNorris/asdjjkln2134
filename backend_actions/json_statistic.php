<?php
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
require_once("functions/statistic.php");
require_once("functions/log.php");
require_once("functions/user.php");
$result=array();

//$year=(int)2011;
//$key="contactedit";
//$userid=-1;
list($year, $key, $userid) =  explode("_", $_REQUEST['dataid']);
$logActions = log_get_actions();
if(array_key_exists($key, $logActions)){
	$yearArr = statitic_getContactsPerMonth((int)$year, $key, (int)$userid);
	$userlogin = ($userid > 0)?user_loginByID($userid):"Alle";
	$result=array("data"=>$yearArr,"label"=>$year."|".$userlogin."|".$logActions[$key]['helptextshort']);
}
echo json_encode($result);
//{
//    "label": "Europe (EU27)",
//    "data": [[1999, 3.0], [2000, 3.9], [2001, 2.0], [2002, 1.2], [2003, 1.3], [2004, 2.5], [2005, 2.0], [2006, 3.1], [2007, 2.9], [2008, 0.9]]
//}