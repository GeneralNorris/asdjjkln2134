<?php
/**
 * Created by PhpStorm.
 * User: lvarnholt
 * Date: 30.11.2017
 * Time: 15:00
 */
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin', true);

$antragArr = antrag_getArrNichtFreigegeben();
?>
<div class="actionHead">
    <h1>Großspende eintragen</h1>
    <span class="noprint">
		Suche: <input id="pattern" title="Suche in allen Spalten &#10; &#10;Geburtstag wird mit g20.05.2013 gefunden" autocomplete="off">
	</span>
</div>
<form action="do_backend_action.php?action=do_addSpecialSpende" method="post" id="form1">
    <h2>Spenderdaten eintragen</h2>
    <table cellpadding="0" cellspacing="1">
        <tr>
            <th style="width:200px">Name</th>
            <td><input name="spender_vorname" type="text"  value="" style="width:200px" autocomplete="off" /></td>
        </tr>
        <tr>
            <th>Nachname</th>
            <td><input name="spender_name" type="text"  value="" style="width:200px" autocomplete="off" /></td>
        </tr>
    </table>
<table cellpadding="0" cellspacing="0" id="dataTable" class="tablesorter">
    <thead>
    <tr>
        <th sortcolumn="status" class="noprint"></th>
        <th sortcolumn="vorgang">Vorg.</th>
        <th sortcolumn="eingegangen_am">Datum</th>
        <th sortcolumn="name">Nachname</th>
        <th sortcolumn="kontakt">Kontakt</th>
        <th sortcolumn='geschenke'>Wünsche/Geschenke</th>
        <td style="position: fixed">Summe: <span id="summe" style="font-weight:bold">0</span> <span style="font-weight:bold">€</span>
         <input type="submit" value="Eintragen" /></td>

    </tr>
    </thead>
    <tbody>

    <?php
    foreach ($antragArr as $antrag) {
        $geschenke = geschenk_getArrByAntragId($antrag["Person_id"]);
        ?>
        <tr id="<?php echo $antrag["Vorgang_id"] ?>">
            <td><input type="checkbox" class="checkAll" name="checkAll">Alle Kinder Auswählen</td>
            <td><?php echo $antrag["Vorgang_id"] ?></td>
            <td><?php echo core_dateformat($antrag["eingegangen_am"]) ?></td>
            <td><?php echo htmlspecialchars($antrag["name"]) ?></td>
            <td>
                Mail: <a href="mailto:<?php echo htmlspecialchars($antrag["email"]) ?>?subject=<?php echo CONF_APPNAME?>&body=Hallo <?php echo htmlspecialchars($antrag["vorname"]." ".$antrag["name"])?>"><?php echo htmlspecialchars($antrag["email"])?></a>
                <br>
                Tel: <?php echo htmlentities($antrag["tel"]) ?>
                <br>
                <br>
                <?php echo htmlspecialchars($antrag["vorname"])." ".htmlspecialchars($antrag["name"]) ?><br>
                <?php echo htmlentities($antrag["plz"])." ".htmlspecialchars($antrag["ort"]) ?><br>
                <?php echo htmlspecialchars($antrag["strasse"]) ?>

            </td>
            <td><?php echo geschenk_getSpecialSpendenHtml($geschenke, $antrag['Vorgang_id']) ?></td>
        </tr>
    <?php } ?>

    </tbody>
    <tfoot>
    </tfoot>
</table>
</form>
<script>
    $(function() {
        $('.checkAll').click(function() {
            $(this).closest("tr").find(":checkbox").prop('checked', this.checked);
        });

        $(':checkbox').change(function () {
            if($(this).is(":checked") == false){
                var id = $(this).attr("id");
                $("tr#" +id).find(".checkAll").prop('checked', false);
            }
            var sum = 0.0;
            $('input[name="checkedGeschenke[]"]:checked').each(function(){
                console.log(this);
                sum += parseFloat($(this).attr("preis"));
            });
            var sumstr = sum + "";
            $('#summe').text(sumstr.replace(".", ","));
        });
        $('#form1').click(function(){

        });
    });






</script>
