<?php
	require_once("functions/artikel.php"); 
	require_once("functions/dateisammlung.php");
	
	$verfuegbareArtikel = artikel_getArr(true);
	$text = text_getText("geschenkseite");
	echo $text;
?>



 	<div style="float:left">
<?php foreach ($verfuegbareArtikel as $artikel) {
	$imagesHTML = "";
	$dateien = dateisammlung_getById($artikel["Dateisammlung_id"]);
	foreach ($dateien as $datei) {
		$imagesHTML .= '<img src="bin.php?id='.$datei['id'].'&d=200x200" class="">';
	}?>
    <section class="wrapper style9">
        <table style="float:left;">
            <tr>
                <td colspan="2"><h2><?php echo htmlspecialchars($artikel["bezeichnung"])?> <span style="color:black"><?php echo htmlspecialchars($artikel["bestellnummer"])?></span></h2></td>
            </tr>
            <tr>
                <td style="vertical-align:top;height:210px;"><div style="display:table-cell; width:210px;height:210px;border:solid 1px green;vertical-align:middle;text-align:center"><?php echo $imagesHTML?></div></td>
            </tr>
            <tr>
                <td style="vertical-align:top;"><div ><?php echo htmlspecialchars($artikel["beschreibung"])?></div></td>
            </tr>
        </table>
    </section>
<?php } ?>
</div>
<div class="clr"></div>