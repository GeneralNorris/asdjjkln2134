<?php
/**
 * Created by PhpStorm.
 * User: lvarnholt
 * Date: 29.07.2016
 * Time: 13:50
 */
if(isset($_SESSION["confirmation"]["success"]) && $_SESSION["confirmation"]["success"]) {

    ?>
    <br>
    <br>Sie haben erfolgreich ihre Email-Adresse bestätigt<br>
    Wir bearbeiten Ihre Registrierung und geben Ihnen Nachricht, sobald Sie Weihnachtsgeschenke für Ihre Kinder aussuchen können. <br>
    Schauen Sie bitte auch in ihren Spam- oder Junkmail Ordner <br>


<?php }
else { ?>
    <br>
    <span style="color:red; font-weight:bold">Es ist ein Fehler bei der Aktivierung aufgetreten<br></span>
    <br><br>

<?php }

unset($_SESSION["confirmation"]["success"]);