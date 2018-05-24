<?php
/**
 * Created by PhpStorm.
 * User: lvarnholt
 * Date: 04.08.2016
 * Time: 13:54
 */

if(isset($_SESSION["passwordReset"]["textId"]) && $_SESSION["passwordReset"]["textId"] == 0) {

    ?>
    <br>
    Sie haben eine Email zum Zurücksetzen ihres Passwortes erhalten


<?php  } else if(isset($_SESSION["passwordReset"]["textId"]) && $_SESSION["passwordReset"]["textId"] == 1){ ?>
    <br>
    Email-Adresse nicht gefunden!
    Bitte geben Sie eine korrekte Email-Adresse ein!
<?php } else {
    if(isset($_SESSION["passwordSet"]["success"]) && $_SESSION["passwordSet"]["success"]) {

        ?>
        <br>
        Sie haben ihr Passwort erfolgreich geändert


    <?php  } else if(isset($_SESSION["passwordSet"]["success"]) && $_SESSION["passwordSet"]["success"] == "NoMatch"){ ?>
        <br>
        Die Passwörter stimmen nicht überein!
    <?php } else { ?>
        <br>
        Es ist ein Fehler aufgetreten!
    <?php }
}
unset($_SESSION["passwordSet"]["success"]);
unset($_SESSION["passwordReset"]["textId"]);

