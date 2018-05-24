<?php
/**
 * Created by PhpStorm.
 * User: lvarnholt
 * Date: 23.03.2018
 * Time: 13:13
 */

	require_once("../functions/kind.php");
	require_once("../functions/config.php");
	require_once("../conf.php");
	require_once("../functions/db.php");
	require_once("../functions/core.php");
	require_once("../functions/log.php");
	require_once("../functions/mail.php");
	require_once("../functions/user.php");
	require_once("../functions/tokens.php");
	core_init();
	/*if(config_get("antraege_input_enabled",0) == 0 && config_get("antraege_input_enabled_special",0) == 0){
		header("location: index.php");
	}*/
header('Content-type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Kinderarmut in Deutschland</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <!--		[if lte IE 8]><script src="../js/libs/arcana/ie/html5shiv.js"></script><![endif]-->
        <link rel="stylesheet" href="../frontend_theme/main.css" />
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
            <img src="theme/Images/Logo_Teilhabe1.png" style="max-width: 100%; height: auto">
        </div>
        <div class="content">
        <form action="../index.php?action=do_register_new" method="post" enctype="multipart/form-data" name="form1" id="form1">
            <section class="wrapper style10">
                <table>
                    <tr>
                        <td colspan="2"><h4>Ihre Daten</h4></td>
                    </tr>
                    <tr>
                        <td><label>Ihre Emailadresse:<span style="color:red">*</span></label></td>
                        <td><input name="person_email" id="person_email" type="text" required/></td>
                    </tr>
                    <tr>
                        <td><label>Ihr Passwort:<span style="color:red">*</span></label></td>
                        <td><input name="user_password" id="user_password" type="password" required/></td>
                    </tr>
                    <tr>
                        <td><label>Ihr Passwort wiederholen:<span style="color:red">*</span></label></td>
                        <td><input name="user_passwordCheck" id="user_passwordCheck" type="password" required/></td>
                    </tr>
                    <tr>
                        <td><label>Ihr Vorname:<span style="color:red">*</span></label></td>
                        <td><input name="person_vorname" id="person_vorname" type="text" required/></td>
                    </tr>
                    <tr>
                        <td><label>Ihr Nachname:<span style="color:red">*</span></label></td>
                        <td><input name="person_name" id="person_name" type="text" required/></td>
                    </tr>
                    <tr>
                        <td><label>Ihre Strasse / Nr.:<span style="color:red">*</span></label></td>
                        <td><input name="person_strasse" id="person_strasse" type="text" required/></td>
                    </tr>
                    <tr>
                        <td><label>Ihre PLZ:<span style="color:red">*</span></label></td>
                        <td><input name="person_plz" id="person_plz" type="text" required/></td>
                    </tr>
                    <tr>
                        <td><label>Ihre Stadt:<span style="color:red">*</span></label></td>
                        <td><input name="person_ort" id="person_ort" type="text" required/></td>
                    </tr>
                    <tr>
                        <td><label>Ihre Telefonnummer:<span style="color:red">*</span></label></td>
                        <td><input name="person_tel"  id="person_tel" type="text" required/></td>
                    </tr>
                    <tr>
                        <td><label>Ich habe die Datenschutzrichtlinien gelesen und stimme der Verwendung der Daten zu:<span style="color:red">*</span></label></td>
                        <td><input id="readDatenschutz" name="readDatenschutz" value="yes" type="checkbox" style="height: 0" required/></td>
                    </tr>
                    <tr>
                        <td><label>Bitte laden Sie hier ihren Hartz4-Bescheid hoch:<span style="color:red">*</span></label></td>
                        <td><input type="file" name="bescheid" id="bescheid"/></td>
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
            });
        });
    </script>
</html>


