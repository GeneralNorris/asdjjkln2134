<?php
user_check_login();
$userID = isset($_GET['editUserID'])? (int) $_GET['editUserID'] : $_SESSION['userID'];

if (!user_check_editpermission($userID)) {
    $userID = $_SESSION['userID'];
}
if(!user_isMailConfirmed($userID)){ //|| !user_getChildMax($userID)){
    header("location: teilhabe.php?action=userProfile");
}
$i=1;
$kinder_list = user_getKinderAdmin($userID);?>

<?php if (!(@$_GET['ajax'] === "true")) { ?>
<form method="post" action="teilhabe.php?action=do_kinderEdit">
<?php } else { ?>
    <form method="post" action="do_frontend_action.php?action=do_kinderEdit">
<?php } ?>
        <h6>Kinder Bearbeiten</h6>
        <input type="hidden" name="userID" value= <?php echo $userID?>>
        <?php
        while ($kind = db_fetch_array($kinder_list)){
            echo '<tr><td colspan="2"><h4>Kind Nr.'.$i.'</h4></td></tr>';
            ?>
            <input type="hidden" name="kind_id[]" value ="<?php echo $kind["id"] ?>">
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
                </table>
            <?php
            $i++;
        }
        if($i == 1){
            echo "Keine Kinder vorhanden <br/>";
        }
        ?>
        <tr>
            <td>
                <input type="submit" value="Speichern">
            </td>
        </tr>
    </form>
    <script>
        $(function() {
            $(".geburtstag").each(function () {
                $(this).datepicker({
                    dateFormat: 'dd.mm.yy', showButtonPanel: false, changeMonth: true,
                    changeYear: true
                });
            });
        });
    </script>
