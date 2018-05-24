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


$loggedIn = user_check_login(false);

$action_content = core_get_frontend_action_content(@$_GET['action']);
$action  = @$_GET['action'];

header('Content-type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<head>
<title>Kinderarmut in Deutschland</title>
<!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="frontend_theme/style.css" />
<script type="text/javascript" src="js/libs/jquery-1.6.1.min.js"></script>
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
<div class="main">
  <div class="main_resize">
    <div class="header">
      <div class="logo">
         <h1><a href="#"><span>Geschenke.Engelbaum.de</span></a></h1>
      </div>
    </div>
    <div class="content">
      <div class="content_bg">
      	<!-- div>
      	<?php if($action != "spende"){
        if ($loggedIn) {
            ?>
      		<a href="index.php?action=overview"><?php echo user_getLogin() ?></a>&nbsp;|&nbsp;
      		<a href="index.php?action=logout"><?php echo "Abmelden" ?></a>
      	<?php } else {
            ?>
      	<a href="index.php?action=login">Login</a>
      	<?php } ?>
      	</div -->
          <div class="menu_nav">
              <ul>
                  <?php if ($loggedIn) { ?>
                      <a href="index.php?action=overview"><?php echo user_getLogin() ?></a>&nbsp;|&nbsp;
                      <a href="index.php?action=logout"><?php echo "Abmelden" ?></a>&nbsp;|&nbsp;
                  <?php } else { ?>
                      <a href="index.php?action=login" class="button">Login</a>&nbsp;|&nbsp;
                  <?php } ?>
                  <?php if (config_get("spenden_input_enabled", 0) == 1 &&!$loggedIn) { ?>
                      <a href="index.php?action=spende">Geschenke spenden</a>&nbsp;|&nbsp;
                  <?php } ?>
                  <?php if (config_get("antraege_input_enabled", 0) == 1 && $loggedIn && user_isActivated($_SESSION['userID']) &&!user_hatAntrag($_SESSION['userID'])) { ?>
                      <a href="index.php?action=antrag">Geschenke aussuchen</a>&nbsp;|&nbsp;
                  <?php } ?>
                  <?php if (config_get("antraege_input_enabled", 0) == 1) { ?>
                      <a href="index.php?action=artikel_view">Geschenke ansehen</a>&nbsp;|&nbsp;
                  <?php } ?>
                  <?php if ($loggedIn) { ?>
                      <a href="index.php?action=userEdit">Mein Profil</a>&nbsp;|&nbsp;
                  <?php }
                  }
                  ?>

          </ul>
        </div>
      <div class="clr"></div>
      <!-- Hier kommt der Text  rein. --> 
        
        <?php echo $action_content; ?>
     
      </div>
      <div class="fbg">
        <div class="fbg_resize">
          <div class="col c1">
          	 <!-- <script type='text/javascript' src='http://de.betterplace.org/widget/project/6338-abenteuerurlaub-fur-benachteiligte-kinder'></script>--></div>
          <div class="col c2">
            <h2><span>Kinderarmut in Deutschland e.V.</span></h2>
            <ul>
                 <li><a href="http://www.kinderarmut-in-deutschland.de" target="_blank">Kinderarmut in Deutschland</a></li>
                 <li><a href="http://www.engelbaum.de" target="_blank">Projekt Engelbaum</a></li>
				 <li><a href="index.php?action=impressum">Impressum</a></li>
            </ul>
          </div>
          <div class="col c3">
            <h2><span>Über uns</span></h2>
            <p>1988 von Sandy & Wolfgang Kremer gegründet, kümmern wir uns seitdem mit einem kleinen, ehrenamtlich engagierten Team um benachteiligte Kinder in Deutschland. Engelbaum® Weihnachtsgeschenke für vergessene Kinder startete 1989 mit 6 Kindern. Heute erreichen wir mit unseren Projekten jährlich über 1650 notleidende Kinder in Deutschland.</p>
          </div>
          <div class="clr"></div>
        </div>
      </div>
    </div>
    <div class="footer">
      <div class="footer_resize">
        <p class="lf">&copy; Copyright Kinderarmut in Deutschland e.V.</p>
        <p class="rf"><a href="#">Seitenanfang</a></p>
        <div class="clr"></div>
      </div>
    </div>
  </div>
</div>
</body>
</html>

