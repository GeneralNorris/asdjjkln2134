<?php 
if(config_get("spenden_input_enabled",0) == 0){
	header("location: index.php");
}

require_once("functions/kind.php");
require_once("functions/geschenk.php");
require_once("functions/artikel.php");
require_once("functions/dateisammlung.php");

$offeneWuenscheFreigegeben = geschenk_getOffeneWuensche();
//shuffle($offeneWuensche);

$offeneWuenscheCount = geschenk_getCountGeschenkeUnbezahlt();
if(count($offeneWuenscheFreigegeben)){
?>
    <body>
    <section class="wrapper style5">
    <h1><?php echo $offeneWuenscheCount ?> Kinder warten noch<br /> auf einen Geschenkpaten!</h1>
    <div id="text_spendenseite">
    <?php $text = text_getText("text_spendenseite");
            echo $text;
    ?>
    </div>
    </section>

        <form action="index.php?action=do_spendeAdd" method="post" id="spenderForm" name="spenderForm">
        <div>
            <table>
            <?php
            foreach ($offeneWuenscheFreigegeben as $wunsch) {
                $artikel = artikel_getArr(false, $wunsch["Artikel_id"]);
                $artikel = $artikel[0];
                $dateien = dateisammlung_getById($artikel["Dateisammlung_id"]);
                $imagesHTML = '<img src="bin.php?id='.$dateien[0]['id'].'&d=150x150" class="img" title="'.htmlspecialchars($artikel["beschreibung"]).'">';
            ?>

                <tr align="center">
                    <td width="160" colspan="2"><?php echo $imagesHTML ?></td>
				</tr>
				<tr align="center">
					<td><b><?php echo htmlspecialchars($wunsch["vorname"])?></b> wünscht sich: <b><?php echo htmlspecialchars($artikel["bezeichnung"]) ?></b><br>
                    <label style="cursor: pointer;">
						Für <?php echo str_replace(".", ",", $artikel["preis"]) ?>€ in den Warenkorb legen
						<br>
							<div class="checkbox"><input type="checkbox" class="checkbox" name="geschenke[]" preis="<?php echo $artikel["preis"]?>" value="<?php echo $wunsch["Geschenk_id"] ?>"></div>
						</label></td>
                </tr>
				<tr align="center"><td><a href="#form">Spende abschließen</a><br><br></td></tr>
            <?php } ?>

            </table>
        </div>
        <br>
        <table id="form">
			
			<tr align="center">
					<td><br><br><br>
                    <h4>Vielen Dank für Ihre Auswahl.<br>Sie haben Engelbaum® Geschenke<br><u>im Wert von <span id="summe" style="font-weight:bold">0</span> <span style="font-weight:bold">€</span></u><br>gewählt.</h4>
                </td>
            </tr>

			<tr align="center">
                    <td><h4>Tragen Sie bitte hier Ihre Daten ein</h4></td>
                </tr>
			<tr align="center">
                    <td><input name="spender_vorname" type="text" placeholder="Vorname*" size="50" required/></td>
                </tr>
			<tr align="center">
                    <td><input name="spender_name" type="text" placeholder="Nachname*" size="50" required/></td>
                </tr>
			<tr align="center">
                    <td><input name="spender_strasse" type="text" placeholder="Strasse / Nr.:*" size="50" required/></td>
                </tr>
			<tr align="center">
                    <td><input name="spender_plz" type="text" placeholder="PLZ*" size="50" required/></td>
                </tr>
			<tr align="center">
                    <td><input name="spender_ort" type="text" placeholder="Stadt*" size="50" required/></td>
                </tr>
			<tr align="center">
                    <td><input name="spender_tel" type="text" placeholder="Telefonnummer" size="50" /></td>
                </tr>
			<tr align="center">
                    <td><input name="spender_email" type="email" placeholder="Email-Adresse*" size="50" required/></td>
                </tr>

            <tr>
					<td><br></td>
            </tr>

			<tr>
					<td><h4><input name="Senden" type="submit" value="Jetzt Spende bestätigen" /></h4></td>

            </tr>
        </table>
        </form>
    </body>
<?php
}else{
    echo "<h4 style='color: red'>Es sind noch keine Wünsche vorhanden!</h4>";
}?>	

<script type="text/javascript">
$(function(){
    $('#spenderForm').click(function(){
        var sum = 0.0;
        $('#spenderForm input:checked').each(function(){
            sum += parseFloat($(this).attr("preis"));
        });
        var sumstr = sum + "";
        $('#summe').text(sumstr.replace(".", ","));
    });

});

$(document).ready(function(){
    var texts = $('body').find('#text_spendenseite');
    $( "#makeEditable" ).bind({
        click: function() {
            makeEditable(texts);
        }
    });
    $( "#saveTexts" ).bind({
        click: function() {
            saveTexts(texts);
        }
    });
});

function makeEditable(text){
    text.attr('contenteditable', 'true');
    text.css('outline', '#000000 dotted thin');
    CKEDITOR.disableAutoInline = true;
    editorText1 = CKEDITOR.inline('text_spendenseite');
    $('#makeEditable').hide();
    $('#saveTexts').show();

}

//Texte und deren Stellen in Arrays übertagen und anschließend per Ajax an saveTexts senden
//Anschließend alle Textfelder wieder non-Editable machen, Speicher-Button entfernen
function saveTexts(text){
    var texts = [];
    var sites = [];
    texts[0] = CKEDITOR.instances.text_spendenseite.getData();
    sites[0] = "text_spendenseite";

    $.ajax({
        url: 'index.php?action=do_saveTexts',
        type: 'POST',
        data: {
            texte : texts,
            sites : sites
        },
        success: function(msg) {
            if(msg.indexOf("success") != -1){
                alert("Text erfolgreich gespeichert!")
            }else if(msg.indexOf("false") != -1){
                alert("Es ist ein Fehler aufgetreten - Text konnte nicht gespeichert werden.")
            }
        }
    });
    $('#saveTexts').hide();
    for(name in CKEDITOR.instances)
    {
        CKEDITOR.instances[name].destroy(true);
    }
    text.attr('contenteditable', 'false');
    text.css('outline','none');
    $('#makeEditable').show();
}
</script>

