<?php
require_once("conf.php");
require_once("functions/db.php");
require_once("functions/core.php");
require_once("functions/user.php");

session_start();
user_logout();
header('Content-type: text/html; charset=UTF-8');

echo '<?xml version="1.0" encoding="ISO-8859-1" ?>';
?>

<html>

<head>
	<title><?php echo strip_tags(CONF_APPNAME); ?> - Anmeldung</title>
	<link rel="stylesheet" href="backend_theme/style.css" type="text/css" />
        <style type="text/css" >
		html, body { /* html und body brauchen diese Angabe */
			height:100%;
		}
		#space {
			width:1px;
			height:50%;
			margin-bottom:-160px; /* die Hälfte der Höhe des Inhalts*/
			float:left;
		}
		#loginbox {
		    width: 320px;
		    height:140px;
		    margin: 0 auto;
		    position:relative; /* hebt den inhalt vor den space  */
			clear:left; /* hebt das float vom space auf  */
			border: 1px solid silver;
			background-color:white;
		}
		#loginbox, #loginbox * {
			-webkit-border-radius: 4px;
			-moz-border-radius: 4px;
			border-radius: 4px;
		}
		#loginbox  .boxtitle{
			background-color:#D7DDF0;
			filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ffffff', endColorstr='#D7DDF0'); /* for IE */
			background: -ms-linear-gradient(top,  #fff,  #D7DDF0); /* for IE */
			background: -webkit-gradient(linear, left top, left bottom, from(#fff), to(#D7DDF0)); /* for webkit browsers */
			background: -moz-linear-gradient(top,  #fff,  #D7DDF0); /* for firefox 3.6+ */
		}
	</style>
	<script>
		function checkkey(e) {
			var key=e.keyCode? e.keyCode : e.charCode
		   	if(key == 13) {
		    	document.forms[0].submit();
		   }
		}
	</script>
</head>
<body onload="document.forms[0].login.focus()" onkeydown="checkkey(event)">
	<div id="space"><br /></div>
	<div id="loginbox">
		<noscript><p style="color:red; font-weight:bold">FEHLER: JavaScript muss aktiviert sein!</p></noscript>
		<table border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="boxtitle" valign="top" style="font-weight:bold;padding-left:15px;border:1px solid white;">
					<?php echo CONF_APPNAME; ?> - Anmeldung
				</td>
			</tr>
			<tr>
				<td style="background-color:white; margin-bottom:1px;">
					<form action="do_login.php" method="post" target="_top" class="noJQuery">
						<table cellspacing="3" cellpadding="0" border="0">
							<tr>
								<td style="width:125px">Benutzername:</td>
								<td><input type="text" name="login" maxlength="255" style="width:200px" /></td>
							</tr>
							<tr>
								<td>Passwort:</td>
								<td><input type="password" name="password" maxlength="255" style="width:200px" /></td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td><input type="button" value="Anmelden" style="width:200px" onclick="document.forms[0].submit()"/></td>
							</tr>
						</table>
					</form>
				</td>
			</tr>
		</table>
	</div>
</body>
</html>