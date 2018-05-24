<?php
/**
 * Created by PhpStorm.
 * User: lvarnholt
 * Date: 23.03.2018
 * Time: 13:13
 */

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
header('Content-type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Kinderarmut in Deutschland</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <!--		[if lte IE 8]><script src="../js/libs/arcana/ie/html5shiv.js"></script><![endif]-->
<!--        <link rel="stylesheet" href="../frontend_theme/main.css" />-->
        <link rel="stylesheet" href="teilhabe_theme/teilhabe.css">
		<!-- link rel="stylesheet" href="main.css" / -->
        <!--[if lte IE 8]><link rel="stylesheet" href="../frontend_theme/ie8.css" /><![endif]-->
        <!--[if lte IE 9]><link rel="stylesheet" href="../frontend_theme/ie9.css" /><![endif]-->

        <!-- Scripts -->
        <script src="../js/libs/arcana/jquery.min.js"></script>
        <script src="../js/libs/arcana/jquery.dropotron.min.js"></script>
        <script src="../js/libs/arcana/skel.min.js"></script>
        <script src="../js/libs/arcana/util.js"></script>
        <!--[if lte IE 8]><script src="../js/libs/arcana/ie/respond.min.js"></script><![endif]-->
        <script src="../js/libs/arcana/main.js"></script>
        <script src="../js/libs/jquery-1.6.1.min.js"></script>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <div class="header" id="header2">
            <img src="teilhabe_theme/Images/Logo_Teilhabe1.png" style="max-width: 100%; height: auto">
        </div>
        <div class="content" align="center">
        <form action="teilhabe.php?action=do_register" method="post" enctype="multipart/form-data" name="form1" id="form1">
            <section class="wrapper style10">
                <table>
                    <tr>
                        <td><h4>Ihre Daten</h4></td>
                    </tr>
                    <tr>
                        <td><input name="person_email" id="person_email" placeholder="Ihre Emailadresse*" type="text" required/></td>
                    </tr>
                    <tr>
                        <td><input name="user_password" id="user_password" placeholder="Ihr Passwort*" type="password" required/></td>
                    </tr>
                    <tr>
                        <td><input name="user_passwordCheck" id="user_passwordCheck" placeholder="Ihr Passwort wiederholen" type="password" required/></td>
                    </tr>
                    <tr>
                        <td><input name="person_vorname" id="person_vorname" placeholder="Ihr Vorname*" type="text" required/></td>
                    </tr>
                    <tr>
                        <td><input name="person_name" id="person_name" placeholder="Ihr Nachname*" type="text" required/></td>
                    </tr>
                    <tr>
                        <td><input name="person_strasse" id="person_strasse" placeholder="Ihr Straße / Nr.:*" type="text" required/></td>
                    </tr>
                    <tr>
                        <td><input name="person_plz" id="person_plz" placeholder="Ihre PLZ*" type="text" required/></td>
                    </tr>
                    <tr>
                        <td><input name="person_ort" id="person_ort" placeholder="Ihre Stadt*" type="text" required/></td>
                    </tr>
                    <tr>
                        <td><input name="person_tel"  id="person_tel" placeholder="Ihre Telefonnummer*" type="text" required/></td>
                    </tr>
                    <tr>
                        <td class="box2">
						<label>Bitte laden Sie hier ihren Hartz4-Bescheid hoch:<span style="color:red">*</span></label>
                        <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo CONF_FILEUPLOAD_MAXSIZE ?>"/>
						<input type="file" name="user_bescheidFile" id="user_bescheidFile" accept="image/*"/>
						</td>
                    </tr>
					<tr>
                        <td class="box2">
						<label>Ich habe die <a href="#" target="_blank">Datenschutzrichtlinien</a> gelesen und stimme der Verwendung der Daten zu:<span style="color:red">*</span></label>
						<input id="person_readDatenschutz" name="person_readDatenschutz" type="checkbox" style="height: 20px" required/>
						</td>
                    </tr>
            </section>
            <table>
                <tr>
                    <input name="Senden" type="submit" id="send" value="Senden" style="background-color:forestgreen"/>
                </tr>
        </form>
        </div>
    </body>
    <script type="text/javascript" src="js/libs/jquery-1.6.1.min.js"></script>
    <script>
        $(function () {
            $( document ).ready(function() {
                $("#navPanel").css("visibility", "hidden");
                $("#titleBar").css("display", "none");
            });

        });
        $(function () {
            $('#form1').submit(function (e) {
                var self = this;
                $('input').removeClass('error');
                $('.inputError').remove();
                e.preventDefault();
                var dataString = $('#form1').serialize();

                $.ajax({
                    type: "POST",
                    url: "validateRegistration.php",
                    data: dataString,
                    success: function (msg) {
                        $("br").remove();
                        $(":text").css("border-color", "threedlightshadow");
                        if(msg.indexOf("success") != -1){
                            self.submit();
                        }
                        if (msg.indexOf("plz_wrong") != -1){
                            $("#person_plz").focus();
                            $("#person_plz").css("border-color" , "red");
                            $("#person_plz").parent().append('</br><span class="inputError">Registrierungen können leider nur aus Deutschland angenommen werden.</span>');
                        }if(msg.indexOf("password_wrong") != -1) {
                            $("#user_password").focus();
                            $("#user_password").css("border-color" , "red");
                            $("#user_passwordCheck").focus();
                            $("#user_passwordCheck").css("border-color" , "red");
                            $("#user_password").parent().append('</br><span class="inputError">Kennwort ungültig: Das Kennwort muss mehr als 5 Zeichen enthalten</span>');
                            $("#user_passwordCheck").parent().append('</br><span class="inputError">Kennwort ungültig: Die Kennwort Bestätigung muss dem Kennwort entsprechen</span>');
                        }if(msg.indexOf("email_used") != -1) {
                            $("#person_email").focus();
                            $("#person_email").css("border-color" , "red");
                            $("#person_email").parent().append('</br><span class="inputError">Diese Email-Adresse wird bereits benutzt</span>');
                        }if(msg.indexOf("email_wrong") != -1) {
                            $("#person_email").focus();
                            $("#person_email").css("border-color" , "red");
                            $("#person_email").parent().append('</br><span class="inputError">Diese Email-Adresse ist ungültig</span>');
                        }if(msg.indexOf("checkbox_empty") != -1) {
                            $("#person_readDatenschutz").focus();
                            $("#person_readDatenschutz").css("border-color" , "red");
                            $("#person_readDatenschutz").parent().append('</br><span class="inputError">Bitte akzeptieren Sie unsere Datenschutz-Bestimmungen</span>');
                        }if(msg.indexOf("file_error") != -1) {

                        }
                        if(msg.indexOf("data_empty") != -1) {
                            alert("Bitte füllen Sie alle Felder aus");
                        }
                    },
                    error: function (msg) {
                        alert("error");
                        self.submit(); // bei einem Fehler einfach dennoch senden...
                    }
                });
                if($('#user_bescheidFile')[0].files.length === 0){
                    $("#user_bescheidFile").focus();
                    $("#user_bescheidFile").css("border-color" , "red");
                    $("#user_bescheidFile").parent().append('</br><span class="inputError">Bitte Laden sie Ihren Hartz4-Bescheid hoch</span>');
                }
            });
        });
    </script>
</html>


