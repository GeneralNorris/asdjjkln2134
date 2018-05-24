<?php
/**
 * Created by PhpStorm.
 * User: lvarnholt
 * Date: 04.08.2016
 * Time: 15:04
 */
if(isset($_POST['pw1'])&&isset($_POST['pw1']) && isset($_POST['changeUserId'])){
    $pw1 = $_POST['pw1'];
    $pw2 = $_POST['pw2'];
    $userId = $_POST['changeUserId'];
    if(strcmp($pw1, $pw2) == 0){
        user_update_password($pw1,$pw2,$userId);
        user_resetLoginFailure($userId);
        $_SESSION["passwordSet"] = array("success" => true);
    }else{
        $_SESSION["passwordSet"] = array("success" => "NoMatch");
        echo "passwörter stimmen nicht überein";
    }
}else{
    $_SESSION["passwordSet"] = array("success" => false);
    echo "Bitte alle Felder ausfüllen";
}
header("location: index.php?action=passwordReset_result");