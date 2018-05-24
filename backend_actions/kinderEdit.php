<?php
$userID = isset($_GET['editUserID'])? (int) $_GET['editUserID'] : $_SESSION['userID'];

if (!user_check_editpermission($userID)) {
    $userID = $_SESSION['userID'];
}
$i=1;
$kinder_list = user_getKinderAdmin($userID);?>


<?php if (!(@$_GET['ajax'] === "true")) { ?>
<form method="post" action="portal.php?action=do_kinderEdit">
    <?php } else { ?>
    <form method="post" action="do_backend_action.php?action=do_kinderEdit">
        <?php } ?>

        <input type="hidden" name="userID" value= <?php echo $userID?>>
        <?php
        while ($kind = db_fetch_array($kinder_list)){
            echo '<tr><td colspan="2"><h3>Kind '.$i.'</h3></td></tr>';
            ?>
            <input type="hidden" name="kind_id[]" value ="<?php echo $kind["id"] ?>">
            <fieldset>
                <legend>Kind</legend>
                <table>

                    <tr>
                        <th>Vorname</th>
                        <td><input name="kind_vorname[]" type="text" value="<?php echo htmlspecialchars($kind["vorname"]) ?>"/></td>
                    </tr>
                    <tr>
                        <th>Nachname</th>
                        <td><input name="kind_name[]" type="text" 	value="<?php echo htmlspecialchars($kind["name"]) ?>"/></td>
                    </tr>
                    <tr>
                        <th>Geburtstag</th>
                        <td><input name="kind_geburtstag[]" type="text" class="geburtstag" id="<?php $i ?>" value="<?php echo core_dateformat($kind["geburtstag"]) ?>" /></td>
                    </tr>
                    <tr>
                        <th>Geschlecht</th>
                        <td>
                            <select name="kind_geschlecht[]">
                                <option value="m" <?php echo (($kind["geschlecht"] == "m")? " selected ":"")?>>männlich</option>
                                <option value="w" <?php echo (($kind["geschlecht"] == "w")? " selected ":"")?>>weiblich</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <a data-kind_id= "<?php echo $kind["id"] ?>" title="kind_delete" id="deleteKind" href="#" class="deleteKind">Kind löschen</a>
                    </tr>
                </table>
            </fieldset>
            <?php

            $i++;
        }

        if($i == 1){
            echo "Keine Kinder vorhanden";
        }
        ?>
    </form>
    <script>
        $(function(){
            $(".deleteKind").click(function(){
                var kindId = $(this).data("kind_id");
                var answer = confirm ("Das Kind "+ kindId +" wird gelöscht. Fortfahren?")
                if (answer){
                    $.ajax({
                        url: 'portal.php?action=do_kind_delete',
                        type: 'POST',
                        data: { delete_kindId : kindId},
                        success: function() { window.location.assign("portal.php?action=user") }
                    });

                }
            });
        });

        $(".geburtstag").each(function(){
            $(this).datepicker({ dateFormat: 'dd.mm.yy', showButtonPanel: false, changeMonth: true,
                changeYear: true});
        });
    </script>