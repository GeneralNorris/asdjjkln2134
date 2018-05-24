<?php
/**
 * Created by PhpStorm.
 * User: lvarnholt
 * Date: 13.04.2018
 * Time: 15:27
 */
require_once("../conf.php");
require_once("../functions/db.php");
require_once("../functions/core.php");
require_once("../functions/log.php");
require_once("../functions/mail.php");
require_once("../functions/user.php");
require_once("../functions/texts.php");
require_once("../functions/tokens.php");
core_init();
$loggedIn       = user_check_login(false);
$action_content = core_get_teilhabe_action_content(@$_GET['action']);
$action         = @$_GET['action'];

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
<body style="background-color: white">
<div class="header" id="header2">
    <img src="theme/Images/Logo_Teilhabe1.png" style="max-width: 100%; height: auto">
</div>
<div style="background-color: white">
    <?php echo $action_content; ?>
</div>
</body>