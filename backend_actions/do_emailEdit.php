<?php
/**
 * Created by PhpStorm.
 * User: lvarnholt
 * Date: 14.07.2016
 * Time: 15:43
 */
require_once("functions/texts.php");
$text= $_POST['text'];
$site= $_POST['site'];


if(text_save($site, $text)){
    $_SESSION["textEdit"] = "success";
}else{
    $_SESSION['textEdit'] = "false";
}

header("location: portal.php?action=EmailEdit");