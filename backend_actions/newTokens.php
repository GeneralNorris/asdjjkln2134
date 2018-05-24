<?php
require_once ("functions/tokens.php");
/**
 * Created by PhpStorm.
 * User: lvarnholt
 * Date: 23.06.2016
 * Time: 13:49
 */
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin', true);

if(isset($_POST['anzahl'])){

    $anzahl = $_POST['anzahl'];

    for($i= 0; $i< $anzahl; $i++){
        token_generate();
    }
}
if(isset($_REQUEST['token']) && !empty($_REQUEST['token'])){

    $token = $_REQUEST['token'];
    token_markCopied($token);
    header("location: portal.php?action=tokens");
}
