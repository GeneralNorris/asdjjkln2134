<?php
/**
 * Created by PhpStorm.
 * User: lvarnholt
 * Date: 20.01.2017
 * Time: 14:00
 */
require_once  ("functions/texts.php");
if(isset($_SESSION['textEdit'])){
    if($_SESSION['textEdit'] == "success"){
        ?>
        <html>
        <p style="color:green; font-size: larger">Erfolgreich bearbeitet!</p>
        </html>
        <?php
    }else{
        ?>
        <html>
        <p style="color:red; font-size: larger">Fehler bei Bearbeitung!</p>
        </html>
        <?php
    }
}
?>
<p>Welche Email wollen Sie bearbeiten?</br></p>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
    <label>Emails:</label>
    <select name="email">
        <option value="default">Auswählen</option>
        <option value="email_geschenkFreigeschaltet">Email:Geschenkwunsch für den Spendenshop freigegeben</option>
        <option value="email_benutzerRegistriert">Email:Benutzer hat sich Registriert</option>
        <option value="email_geschenkeshopOnline">Email:Geschenkeshop jetzt geöffnet</option>
        <option value="email_ConfirmationLink">Email:Bestätigungslink der Email-Adresse</option>
        <option value="email_geschenkwunschEingegangen">Email:Benutzer hat Geschenkwunsch eingereicht</option>
        <option value="email_spendeEingegangen">Email:Spende für Geschenkwunsch gefunden (Email an Mutter)</option>
        <option value="email_anSpender">Email:Neue Spende eingegangen (Email an Spender)</option>
        <option value="email_accountAktiviert">Email:Account vollständig aktiviert</option>
    </select></br>
    <input type="submit" name="send" value="Bearbeiten">
</form>
<?php
if(isset($_POST['send'])) {

    if (isset($_POST['email'])) {

        if (isset($_POST['email']) && $_POST['email'] != "default") {
            $site = $_POST['email'];
            $text = text_getText($_POST['email']);
            $error = false;
        }
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <script src="libs/ckeditor/ckeditor.js">
            </script>
        </head>

        <body>
        <?php
        if (!$error){
            if (!(@$_GET['ajax'] === "true")) { ?>
            <form method="post" id="textForm" action="portal.php?action=do_emailEdit">
                <?php } else { ?>
                <form method="post" id="textForm" action="do_backend_action.php?action=do_emailEdit">
                    <?php } ?>
                    <input type="hidden" name="site" value="<?php echo $site ?>"/>
                    <textarea class="mytextarea " id="text" name="text" cols="120"
                              rows="8"><?php echo htmlspecialchars($text) ?></textarea><br>
                    <input type="submit" value="Senden"/>
                </form>
                <script>
                    CKEDITOR.replace("text");
                </script>
            </body>
            <?php
        }
    }
}

unset($_SESSION["textEdit"]);