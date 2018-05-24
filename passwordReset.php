<?php
require_once("functions/kind.php");
require_once("functions/config.php");
require_once("conf.php");
require_once("functions/db.php");
require_once("functions/core.php");
require_once("functions/log.php");
require_once("functions/mail.php");
require_once("functions/user.php");
require_once("functions/tokens.php");
core_init();
/*if(config_get("antraege_input_enabled",0) == 0 && config_get("antraege_input_enabled_special",0) == 0){
    header("location: index.php");
}*/
header('Content-type: text/html; charset=UTF-8');
if (isset($_GET["token"])&& preg_match('/^[0-9A-F]{40}$/i', $_GET["token"])) {
    $token = $_GET["token"];
    if (token_isUsed($token)) {
        echo "Der Link ist abgelaufen!";

    } else {
        if($userId = token_verify_passwordReset($token)){
            ?>
            <form action="index.php?action=do_passwordReset" method="post">
                <table cellspacing="3" cellpadding="0" border="0">
                    <tr>
                        <td><input type="hidden" name="changeUserId" value = <?php echo $userId ?>/></td>
                    </tr>
                    <tr>
                        <td style="width:125px">Neues Passwort:</td>
                        <td><input type="password" name="pw1" maxlength="255" style="width:200px" /></td>
                    </tr>
                    <tr>
                        <td style="width:125px">Neues Passwort wiederholen:</td>
                        <td><input type="password" name="pw2" maxlength="255" style="width:200px" /></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td><input type="button" value="Abschicken" style="width:200px" onclick="document.forms[0].submit()"/></td>
                    </tr>
                </table>
            </form>
<?php
        }else{
            echo "Der Link ist ungültig!";
        }
    }
}
