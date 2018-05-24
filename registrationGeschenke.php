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
	if(token_isUsed($token)){
		echo "Der Link ist abgelaufen!";
	}else{?>
		<!DOCTYPE html>
		<html>
		<head>
		<title>Kinderarmut in Deutschland</title>
            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1" />
            <!--		[if lte IE 8]><script src="js/libs/arcana/ie/html5shiv.js"></script><![endif]-->
            <link rel="stylesheet" href="frontend_theme/main.css" />
            <!--[if lte IE 8]><link rel="stylesheet" href="frontend_theme/ie8.css" /><![endif]-->
            <!--[if lte IE 9]><link rel="stylesheet" href="frontend_theme/ie9.css" /><![endif]-->

            <!-- Scripts -->
            <script src="js/libs/arcana/jquery.min.js"></script>
            <script src="js/libs/arcana/jquery.dropotron.min.js"></script>
            <script src="js/libs/arcana/skel.min.js"></script>
            <script src="js/libs/arcana/util.js"></script>
            <!--[if lte IE 8]><script src="js/libs/arcana/ie/respond.min.js"></script><![endif]-->
            <script src="js/libs/arcana/main.js"></script>
            <script src="js/libs/jquery-1.6.1.min.js"></script>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		</head>
		<body>
		<div class="header" id="header">
			<div class="logo">
				<h1><a>Geschenke.Engelbaum.de</a></h1>
			</div>
            <div id="titleBar" style="display:none"></div>
		</div>
			<form action="index.php?action=do_register" method="post" enctype="multipart/form-data" name="form1" id="form1">
            <section class="wrapper style8">
			<table>
				<input type="hidden" name="token" value="<?php echo $token ?>">
				<tr>
					<td colspan="2"><h4>Ihre Daten</h4></td>
				</tr>
				<tr>
					<td><label>Benutzername:<span style="color:red">*</span></label></td>
					<td><input name="user_login" id="user_login" type="text" required/></td>
				</tr>
				<tr>
					<td><label>Passwort:<span style="color:red">*</span></label></td>
					<td><input name="user_password" id="user_password" type="password" required/></td>
				</tr>
				<tr>
					<td><label>Passwort wiederholen:<span style="color:red">*</span></label></td>
					<td><input name="user_passwordCheck" id="user_passwordCheck" type="password" required/></td>
				</tr>
				<tr>
					<td><label>Emailadresse:<span style="color:red">*</span></label></td>
					<td><input name="person_email" id="person_email" type="text" required/></td>
				</tr>
				<tr>
					<td><label>Vorname:<span style="color:red">*</span></label></td>
					<td><input name="person_vorname" id="person_vorname" type="text" required/></td>
				</tr>
				<tr>
					<td><label>Nachname:<span style="color:red">*</span></label></td>
					<td><input name="person_name" id="person_name" type="text" required/></td>
				</tr>
				<tr>
					<td><label>Strasse / Nr.:<span style="color:red">*</span></label></td>
					<td><input name="person_strasse" id="person_strasse" type="text" required/></td>
				</tr>
				<tr>
					<td><label>PLZ:<span style="color:red">*</span></label></td>
					<td><input name="person_plz" id="person_plz" type="text" required/></td>
				</tr>
				<tr>
					<td><label>Stadt:<span style="color:red">*</span></label></td>
					<td><input name="person_ort" id="person_ort" type="text" required/></td>
				</tr>
				<tr>
					<td><label>Telefonnummer:<span style="color:red">*</span></label></td>
					<td><input name="person_tel"  id="person_tel" type="text" required/></td>
				</tr>
				<tr>
					<td colspan="2"><br/> <br/></td>
				</tr>
<!--				<tr>-->
<!--					<td colspan="2"><h4>Daten der Kinder</h4></td>-->
<!--				</tr>-->
<!---->
<!--				--><?php //for ($i = 1; $i < 7; $i++) {
//					echo '<tr><td colspan="2"><h3>Kind ' . $i . '</h3></td></tr>';
//					kind_printRegistrationElements($i);
//				} ?>
            </section>
                <table>
                <tr>
                    <input name="Senden" type="submit" id="send" value="Registrieren"/>
                    <input type="reset" name="button" id="button" value="Zurücksetzen" style="background-color: red"/>
                </tr>




		</form>
		</body>
		<script type="text/javascript" src="js/libs/jquery-1.6.1.min.js"></script>
		<script>
            $(function () {
                $( document ).ready(function() {
                    $("#navPanel").css("visibility", "hidden");

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
							url: "validate.php",
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
								}if(msg.indexOf("login_wrong") != -1) {
									$("#user_login").focus();
									$("#user_login").css("border-color" , "red");
									$("#user_login").parent().append('</br><span class="inputError">Dieser Login Name enthält ungültige Zeichen</span>');
								}if(msg.indexOf("login_used") != -1) {
									$("#user_login").focus();
									$("#user_login").css("border-color" , "red");
									$("#user_login").parent().append('</br><span class="inputError">Dieser Login Name ist bereits vergeben</span>');
								}if(msg.indexOf("password_wrong") != -1) {
									$("#user_password").focus();
									$("#user_password").css("border-color" , "red");
									$("#user_passwordCheck").focus();
									$("#user_passwordCheck").css("border-color" , "red");
									$("#user_password").parent().append('</br><span class="inputError">Kennwort ungültig: Das Kennwort muss mehr als 5 Zeichen enthalten</span>');
									$("#user_passwordCheck").parent().append('</br><span class="inputError">Kennwort ungültig: Die Kennwort Bestätigung muss dem Kennwort entsprechen</span>');
								}if(msg.indexOf("child_empty") != -1) {
									$("#kind_vorname").focus();
									$("#kind_vorname").css("border-color" , "red");
									$("#kind_vorname").append('</br><span class="inputError">Bitte fügen Sie mindestens ein Kind hinzu</span>');
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
		<?php
	}
}else {
	//throw new Exception("Valid token not provided.");
	echo "Keine Berechtigung";

}


