<?php
/**
 * Created by PhpStorm.
 * User: lvarnholt
 * Date: 27.04.2017
 * Time: 13:52
 */
require_once("functions/texts.php");

$texts = $_POST['texte'];
$sites = $_POST['sites'];
$result = text_saveAll($sites,$texts);
if($result){
    echo "%success";
}else{
    echo "%false";
}