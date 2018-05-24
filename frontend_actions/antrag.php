<?php
user_check_login();
user_check_access('benutzer,admin,mitarbeiter', true);
require_once("functions/kind.php");
	
if(config_get("antraege_input_enabled",0) == 0 && config_get("antraege_input_enabled_special",0) == 0 || !user_isActivated($_SESSION['userID'])){
		header("location: index.php");
}
?>
<!DOCTYPE html>
<?php
$kinderArray = array();
if(user_hatAntrag($_SESSION['userID'])){
	echo '<p><h6>Sie haben bereits Geschenkwünsche eingetragen. Bei Problemen schreiben sie uns bitte eine Email</h6></p>';
}

if(!user_hatAntrag($_SESSION['userID'])){
	$kinder_list = user_getKinder();
    while ($kind = db_fetch_array($kinder_list)){
        array_push($kinderArray,$kind);
    }
    if(!empty($kinderArray)){
        ?>
        <form action="index.php?action=do_antragAdd" method="post" enctype="multipart/form-data" name="form1" id="form1">
        <section class = "wrapper style6">
            <h2 style="text-align: center">Geschenke aussuchen</h2>
            <p style="text-align: center; color: red">Achtung, Sie können dieses Formular nur einmal absenden, also gehen Sie sicher, dass Sie es vollständig und korrekt ausfüllen,
                und dass Sie ihrem Account alle Kinder vollständig hinzugefügt haben.</p>
            <?php
            for($i = 0; $i < count($kinderArray); $i++){
                $j = $i+1;
                echo '<section class="wrapper style7"><tr><td colspan="2"><h3>Kind '.$j.'</h3></td></tr>';
                ?>	    <tr>
                    <td name="kind_vorname">
                        <label>Name: </label><h1><?php echo htmlspecialchars($kinderArray[$i]['vorname'])?></h1>
                        <input type="hidden" name="kind_id[]" value="<?php echo $kinderArray[$i]["id"]?>" >
                    </td>
                </tr>


                <?php
                kind_printAntragElements($i);
                ?>
                </section>
            <?php } ?>
        <tr>
            <td><strong>Diese Daten jetzt</strong></td>
            <td>
                <input name="Senden" type="submit" value="Senden" />
                <input type="reset" name="button" id="button" value="Zurücksetzen" />
            </td>
        </tr>
        </div>
        </section>
        </form>
        <?php
    }else{
        ?>
        <script>
            $(".wrapper").prepend(
               '<p style="text-align: center; color: red">Sie haben keine Kinder in ihrem Account hinzugefügt. Bitte Fügen Sie ihre Kinder hinzu.</p>'
            );
        </script>
        <?php
    }
	?>

<section class="wrapper style1"">
    <div class="container">
        <div class="row 200%">
            <section class="3u 12u(narrower)">
                <div class="box highlight">
                    <i class="icon major fa-pencil"></i>
                    <h3>Geschenke für ihre Kinder aussuchen</h3>
                    <p>Wir freuen uns, wenn wir Ihnen und Ihren Kindern in dieser sicher schwierigen Phase Ihres Lebens helfen können.
                        Sie können bei Engelbaum® für jedes Kind bis 15 Jahre ein schönes, für Sie kostenloses Weihnachtsgeschenk aus unserer
                        <a href="index.php?action=artikel_view">Wunschliste</a> beantragen.
                    </p>
                </div>
            </section>
            <section class="3u 12u(narrower)">
                <div class="box highlight">
                    <i class="icon major fa-question-circle"></i>
                    <h3>Wie erfahre ich, ob mein Kinderwunsch erfüllt wird?</h3>
                    <p>Sie können Ihren Geschenkwunsch im Spendenshop verfolgen. Wenn wir einen Paten gefunden haben, bekommen Sie automatisch eine Bestätigung für Ihr Geschenk.</p>
                </div>
            </section>
            <section class="3u 12u(narrower)">
                <div class="box highlight">
                    <i class="icon major fa-gift"></i>
                    <h3>Wann bekomme ich die Geschenke für mein Kind?</h3>
                    <p>Sobald wir einen Paten gefunden haben, kaufen wir das Geschenk und senden es Ihnen kurz vor Weihnachten per Kurierdienst zu.</p>
                </div>
            </section>
            <section class="3u 12u(narrower)">
                <div class="box highlight">
                    <i class="icon major fa-lock"></i>
                    <h3>Datenschutz</h3>
                    <p>Unsere Datenschutzpraxis steht im Einklang mit dem Bundesdatenschutzgesetz (BDSG) sowie dem Teledienstedatenschutzgesetz (TDDSG).
                        Kinderarmut in Deutschland e.V. speichert Ihre persönlichen Daten nur zur Erfüllung unserer satzungsgemässen Zwecke und Aufgaben.
                        Ihre Daten werden in keinem Fall weitergegeben.</p>
                </div>
            </section>
        </div>
    </div>
    </section>

<div class="clr"></div>
<?php }?>
