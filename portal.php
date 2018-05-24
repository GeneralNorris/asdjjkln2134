<?php

$start = microtime(true);
//2010-06-24 
require_once("conf.php");
require_once("functions/db.php");
require_once("functions/core.php");
require_once("functions/user.php");
require_once("functions/mail.php");
require_once("functions/log.php");

core_init();
user_check_login();
log_add('pageimpression', $_GET['action']);

$action_content = core_get_action_content($_GET['action']);
$action_js = core_get_action_jsPath($_GET['action']);

header('Content-type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo strip_tags(CONF_APPNAME) ?></title>
	<link rel="stylesheet" href="backend_theme/tablesorter/tablesorter.css" type="text/css" />
	<link rel="stylesheet" href="backend_theme/custom-theme/jquery-ui-1.8.11.custom.css" type="text/css" />
	<link rel="stylesheet" href="backend_theme/multiselect/jquery.multiselect.css" type="text/css" />
	<link rel="stylesheet" href="backend_theme/style.css" type="text/css" />
	<link rel="stylesheet" href="backend_theme/print_style.css" type="text/css" media="print" />
	
	<link rel="stylesheet" href="backend_theme/pager.css" type="text/css" />

	<script type="text/javascript" src="js/libs/jquery-1.6.1.min.js"></script>
	<script type="text/javascript" src="js/libs/jquery-ui-1.8.11.custom.min.js"></script>
	<script type="text/javascript" src="js/libs/jquery.ui.datepicker-de.js"></script>
	<script type="text/javascript" src="js/jquery.multiselect.js"></script>
	<script type="text/javascript" src="js/libs/jquery.tablesorter.js"></script>
	<script type="text/javascript" src="js/jquery.multiselect.js"></script>
	<script type="text/javascript" src="js/jquery.tablesorter.cutom_parsers.js"></script>
	<script type="text/javascript" src="js/libs/dialogextend/jquery.dialogextend.1_0_1.js"></script>
	<script type="text/javascript" src="js/portal.js"></script>

	<?php echo $action_js?>
	<script type="text/javascript">
		//Während Inhalte geladen werden, einen Layer über die Seite legen, damit nicht geklickt werden kann
		$(window).unload(function() {
			$('#preload').show();
			$('body').css('cursor','wait');

		});
		$(window).load(function() {
			$('#preload').fadeOut('slow');
			$('body').css('cursor','auto');
		});
	</script>
</head>
<body>
	<div id="preload" style="z-index:1000; position:absolute; left:0; top:0; height:100%; width:100%; text-align:right; background:url(backend_theme/images/loader.gif) no-repeat  3px 10px ;"></div>
	<div id="header">
		<h1><?php echo CONF_APPNAME?> <span style="font-size:0.4em; color:#B0BFE1; font-weight:normal">Version <?php echo CONF_APPVERSION?></span></h1>
	</div>
	<div id="menu_horiz">
	<nobr>
		<ul style="float:left">
			<li>
				<a href="portal.php?action=antraege" class="<?php echo ($_GET['action']==="antraege")? "active":"" ?>">Anträge</a>
			</li>
			<li>
			   <a href="portal.php?action=spenden" class="<?php echo ($_GET['action']==="spenden")? "active":"" ?>">Spenden</a>
			</li>
			<li>
			   <a href="portal.php?action=artikel" class="<?php echo ($_GET['action']==="artikel")? "active":"" ?>">Artikel</a>
			</li>

			<?php if (user_check_access('admin', false)) { ?>
				<li>
					<a href="portal.php?action=statistic" class="<?php echo ($_GET['action']==="contactstat")? "active":"" ?>">Statistik</a>
				</li>
			<?php } ?>
			<?php if (user_check_access('admin', false)) { ?>
				<li>
					<a href="portal.php?action=user" class="<?php echo ($_GET['action']==="user")? "active":"" ?>">Mütter</a>
				</li>
			<?php } ?>
			<?php if (user_check_access('admin', false)) { ?>
				<li>
					<a href="portal.php?action=mitarbeiter" class="<?php echo ($_GET['action']==="mitarbeiter")? "active":"" ?>">Mitarbeiter</a>
				</li>
			<?php } ?>
			<?php if (user_check_access('admin', false)) { ?>
				<li>
					<a href="portal.php?action=config" class="<?php echo ($_GET['action']==="config")? "active":"" ?>">Konfiguration</a>
				</li>
			<?php } ?>
			<?php if (user_check_access('admin', false)) { ?>
				<li>
					<a href="portal.php?action=tokens" class="<?php echo ($_GET['action']==="config")? "active":"" ?>">Einmal-Links</a>
				</li>
			<?php } ?>
			<?php if (user_check_access('admin', false)) { ?>
				<li>
					<a href="portal.php?action=EmailEdit" class="<?php echo ($_GET['action']==="config")? "active":"" ?>">Emails editieren</a>
				</li>
			<?php } ?>
		</ul>
		<ul style="float:right; white-space: nowrap;">
			<li>
				<a href="portal.php?action=userEdit" <?php echo ($_GET['action']==="accountConfig")? "class=\"active\"":"" ?>>Mein Profil</a>
			</li>
			<li>
				<a href="geschenke_login.php" style="color:#A40049">Abmelden</a>
			</li>
		</ul>
		</nobr>
	</div>
	<div id="action">
		<?php echo $action_content ?>
	</div>
	<!-- <?php echo microtime(true)-$start.' Sekunden in der Ausführung'?> -->
</body>
</html>