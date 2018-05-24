<?php
/**
 * Created by PhpStorm.
 * User: lvarnholt
 * Date: 09.03.2017
 * Time: 15:29
 */
//if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_login();
user_check_access('benutzer,admin,mitarbeiter', true);
$userId = $_SESSION['userID'];
$user = user_byID($userId);
$username = $user['vorname'];
?>
<section class="wrapper style5">
    <div class="container">
        <header>
            <h2>Hallo <?php echo $username?></h2><br><br>
            <?php if(user_isActivated($userId)){?>
                <p><i class="icon major fa-check-square"></i><br>Ihr Account ist Aktiviert</p>
            <?php }else{ ?>
                <p><i class="icon major fa-times-circle" style="background-color: #aa1111"></i><br>Ihr Account ist noch nicht Aktiviert.</br> Ihre Registrierung wird noch überprüft, bitte haben sie noch etwas Geduld.</p>
            <?php }?>
            <?php if(user_isMailConfirmed($userId)){?>
                <p><i class="icon major fa-check-square"></i><br>Ihre Email-Adresse ist Bestätigt</p>
            <?php }else{ ?>
                <p><i class="icon major fa-times-circle" style="background-color: #aa1111"></i><br>Ihre Email-Adresse ist noch nicht Bestätigt</p>
            <?php }?>
        </header>
    </div>
</section>
<?php if(user_isMailConfirmed($userId)){
        if(user_isActivated($userId)){?>
<section class="wrapper style1">
    <div class="container">
        <div class="row 500%">
            <section class="3u 12u(narrower)">
                <div class="box highlight">
                    <a href="index.php?action=artikel_view"><i class="icon  fa-gift"></i></a>
                    <h3><a href="index.php?action=artikel_view">Geschenke Übersicht</a></h3>
                    <p>Übersicht aller Geschenke ansehen</p>
                </div>
            </section>
            <?php if(!user_hatAntrag($userId) && config_get("antraege_input_enabled", 0) == 1){?>
            <section class="3u 12u(narrower)">
                <div class="box highlight">
                    <a href="index.php?action=antrag"><i class="icon  fa-gift"></i></a>
                    <h3><a href="index.php?action=antrag">Geschenke aussuchen</a></h3>
                    <p>Wählen Sie für jedes Ihrer Kindern ein passendes Geschenk aus. Wir verpacken dies und senden es kostenfrei zu.</p>
                </div>
            </section>
            <section class="3u 12u(narrower)">
                <div class="box highlight">
                    <a href="index.php?action=kinderAdd"><i class="icon  fa-plus-circle"></i></a>
                    <h3><a href="index.php?action=kinderAdd">Kinder eintragen</a></h3>
                    <p>Fügen Sie Ihre Kinder hinzu</p>
                </div>
            </section>
            <?php }?>
            <section class="3u 12u(narrower)">
                <div class="box highlight">
                    <a href="index.php?action=kinderEdit"><i class="icon  fa-pencil"></i></a>
                    <h3><a href="index.php?action=kinderEdit">Kinder bearbeiten</a></h3>
                    <p>Bearbeiten Sie die Daten ihrer Kinder</p>
                </div>
            </section>
			<section class="3u 12u(narrower)">
                <div class="box highlight">
                    <a href="index.php?action=userEdit"><i class="icon  fa-pencil"></i></a>
                    <h3><a href="index.php?action=userEdit">Daten bearbeiten</a></h3>
                    <p>Ändern Sie Ihre persönlichen Daten </p>
                </div>
            </section>
        </div>
    </div>
</section>
        <?php } ?>

<?php } ?>