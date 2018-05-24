<?php
if(isset($_POST['email'])){
    $email = $_POST['email'];
    if($userId = user_idByEmail($email)){
        if($url= token_generate_passwordReset($userId)){
            mail_send_passwordLink($url, $email);
            $_SESSION["passwordReset"] = array("textId" => 0);
        }
    }else{
        $_SESSION["passwordReset"] = array("textId" => 1);
    }
}else{
    $_SESSION["passwordReset"] = array("textId" => 2);
}
header("location: index.php?action=passwordReset_result");