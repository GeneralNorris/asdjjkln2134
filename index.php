<?php
require_once("conf.php");
require_once("functions/db.php");
require_once("functions/core.php");
require_once("functions/log.php");
require_once("functions/mail.php");
require_once("functions/user.php");
require_once("functions/texts.php");
require_once("functions/tokens.php");
core_init();


$loggedIn       = user_check_login(false);

$action_content = core_get_frontend_action_content(@$_GET['action']);
$action         = @$_GET['action'];

$text_about     = text_getText('text_about');
$text_kontakt   = text_getText('text_kontakt');
$text_projekte  = text_getText('text_projekte');

header('Content-type: text/html; charset=UTF-8');
if($loggedIn) {
    $username   = user_loginByID($_SESSION['userID']);
}
?>
<!DOCTYPE HTML>
<!--
Arcana by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
<!--		<title>Weihnachtsgeschenke gegen Kindernot schenkt ein Lächeln</title>-->
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
        <script src="libs/ckeditor/ckeditor.js"></script>
        <script>
            $(function() {
                //für nicht HTML5 Browser auf required field prüfen
                function attributeSupported(attribute) {
                    return (attribute in document.createElement("input"));
                }
                $("form").submit(function(e){
                    if (!attributeSupported("required") || ($.browser.safari)) {
                        var warn = false;
                        $("form [required]").each(function(index) {
                            if (!$(this).val()) {
                                $(this).css('border','2px solid red');
                                warn = true;
                            }
                        });
                        if(warn == true){
                            alert("Bitte füllen Sie alle erforderlichen Felder aus!");
                            e.stopPropagation();
                            e.stopImmediatePropagation();
                            return false;
                        }
                    }
                });
            });
        </script>
	</head>
	<body>
		<div id="page-wrapper">

			<!-- Header -->
				<div id="header">

					<!-- Logo -->
						<h1><a href="index.php" id="logo">Geschenke.Engelbaum.de</a></h1>

					<!-- Nav -->
						<nav id="nav">
							<ul id="navBar">
								<li id="start" class="current"><a href="index.php">Startseite</a></li>
								<?php if($loggedIn) {?>
                                <li id="overview">
									<a href="index.php?action=overview">Mein Profil</a>
									<ul>
										<li><a href="index.php?action=userEdit">Daten bearbeiten</a></li>
										<li><a href="index.php?action=kinderEdit">Kinder bearbeiten</a></li>
                                        <?php if (config_get("antraege_input_enabled", 0) == 1 && $loggedIn && user_isActivated($_SESSION['userID']) &&!user_hatAntrag($_SESSION['userID'])) {?>
                                        <li><a href="index.php?action=kinderAdd">Kinder eintragen</a></li>
                                        <li><a href="index.php?action=antrag">Geschenke aussuchen</a></li>
										<li><a href="index.php?action=artikel_view">Geschenke Übersicht</a></li>
                                        <?php } ?>
									</ul>
								</li>
                               <?php } ?>
                                <?php if($loggedIn){ ?>
								<li><a href="index.php?action=logout">LOGOUT</a></li>
                                <li>Eingeloggt als <?php echo $username?></li>
                                <?php }else{?>
                                    <li id="login"><a href="index.php?action=login">LOGIN</a></li>
                                <?php }?>

							</ul>
						</nav>

				</div>
                <div>
                    <?php echo $action_content; ?>
                </div>

			<!-- Footer -->
				<div id="footer">
					<div class="container">
						<div class="row">
							<section class="3u 6u(narrower) 12u$(mobilep)">
                                <div id="text_projekte">
                                    <?php echo $text_projekte ?>
                                </div>
<!--								<h3>Weitere Projekte</h3>-->
<!--								<ul class="links">-->
<!--									<li><a href="http://www.kinderarmut-in-deutschland.de">Kinderarmut in Deutschland</a></li>-->
<!--									<li><a href="http://www.engelbaum.de">Projekt Engelbaum</a></li>-->
<!--									<li><a href="index.php?action=impressum">Impressum</a></li>-->
<!--								</ul>-->
							</section>
							<section class="3u 6u$(narrower) 12u$(mobilep)">
                                <div id="text_kontakt">
                                    <?php echo $text_kontakt ?>
                                </div>
<!--								<h3>Kontakt</h3>-->
<!--								<p>Kinderarmut in Deutschland e.V. <br>-->
<!--                                Oberhombach 1<br>-->
<!--								57537 Wissen<br>-->
<!--								Telefon +49 2747 / 911 752<br>-->
<!--								<a href="mailto:office@kinderarmut-in-deutschland.de">office@kinderarmut-in-deutschland.de</a></p>-->
							</section>
							<section class="6u 12u(narrower)">
                                <div id="text_about">
                                    <?php echo $text_about?>
                                </div>
<!--								<h3>Über uns</h3>-->
<!--								<p>1988 von Sandy & Wolfgang Kremer gegründet, kümmern wir uns seitdem mit einem kleinen, ehrenamtlich engagierten Team um benachteiligte Kinder in Deutschland. Engelbaum&reg; Weihnachtsgeschenke für vergessene Kinder startete 1989 mit 6 Kindern. Heute erreichen wir mit unseren Projekten jährlich über 1650 notleidende Kinder in Deutschland.</p>-->
							</section>
						</div>
					</div>

					<!-- Icons -->
						<ul class="icons">
							<li><a href="https://de-de.facebook.com/Kinderarmut-in-Deutschland-172487201217/" class="icon fa-facebook"><span class="label">Facebook</span></a></li>
						</ul>

					<!-- Copyright -->
						<div class="copyright">
							<ul class="menu">
								<li>&copy; Engelbaum&reg; ist ein eingetragenes Warenzeichen</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
							</ul>
						</div>
				</div>
		</div>
	</body>
</html>
    <?php
    if($action == "start"){?>
        <script>
            $("li").removeClass("current");
            $("#start").addClass("current");
        </script>
    <?php
    }if($action == "overview" || $action == "userEdit" || $action == "kinderAdd" || $action == "kinderEdit" || $action == "antrag" || $action == "artikel_view"){ ?>
        <script>
            $("li").removeClass("current");
            $("#overview").addClass("current");
        </script>
    <?php
    }if($action == "login") { ?>
        <script>
            $("li").removeClass("current");
            $("#login").addClass("current");
        </script>
    <?php
    }if(user_check_login(false) && ($action == "" || $action == "spende")) {
        $roles = user_roles($_SESSION['userID']);
        if (in_array('admin', $roles)) { //Admin im Frontend eingeloggt.--> Editier Optionen für Texte einblenden ?>
            <script>
                $("#navBar").append('<li><a href="#" id="makeEditable">Texte editieren</a></li>');
                $("#navBar").append('<li><a href="#" id="saveTexts" style="display: none">Texte speichern</a></li>');
            </script>
    <?php
        }
    }
    ?>

