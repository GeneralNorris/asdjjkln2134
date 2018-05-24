<?php
user_check_login();
$userID = isset($_GET['editUserID'])? (int) $_GET['editUserID'] : $_SESSION['userID'];

if (!user_check_editpermission($userID)) {
	$userID = $_SESSION['userID'];
}
if(!user_isMailConfirmed($userID) || user_hatAntrag($userID)){ // || !user_getChildMax($userID)){
	header("location: teilhabe.php?action=userProfile");
}
$i=1;
$kinderAnzahl = user_getKinderAnzahl($userID);
$kinder_list = user_getKinderAdmin($userID);
while ($kind = db_fetch_array($kinder_list)){
	$i++;
}
if (!(@$_GET['ajax'] === "true")) { ?>

<form method="post" id="form1" xmlns="http://www.w3.org/1999/html">
<?php } else { ?>
    <form method="post" id="form1">
<?php } ?>
        <section class="wrapper style10">
            <table>
                <input type="hidden" name="userID" value= <?php echo $userID?>>
                <input type="hidden" id="doneBool" name="kind_letztes" value=false>
                <div class="container">
                <?php
                    if(!user_getChildMax($userID) && $kinderAnzahl < 6){
                        echo '<tr id="title"><td colspan="2"><h6>Kind hinzufügen: Kind Nr.'.($kinderAnzahl +1).'</h6></td></tr>';
                        kind_printAddElements($i);
                    }elseif (!user_getChildMax($userID) && $kinderAnzahl >= 6){
                        echo '<tr id="title"><td colspan="2"><h4>Sie haben bereits die maximale Kinderanzahl erreicht!</h4></td></tr>';
                    }
                    elseif (user_getChildMax($userID)){
                        echo '<tr><td colspan="2"><h6>Kind hinzufügen: Kind Nr.'.($kinderAnzahl +1).'</h6></td></tr>';
                        kind_printAddElements($i);
                    }
                ?>
                </div>
            </table>
        </section>
        <input type="submit" value="Speichern und weiteres Kind hinzufügen">
        <input type="button" style="background-color: red " id="done" value="Speichern und Beenden">
</form>
<script>
    $(function () {
        var last = false;
        $('#form1').submit(function (e) {
            $('input').removeClass('error');
            $('.inputError').remove();
            e.preventDefault();
            var dataString = $('#form1').serialize();
            var link = "teilhabe.php?action=do_kindAdd";
            if(last == true){
                link = "teilhabe.php?action=do_kindAdd&last=true";
            }
            $.ajax({
                type: "POST",
                url: link,
                data: dataString,
                success: function (msg) {
                    if(msg.indexOf("success") != -1){
                        alert('Erfolgreich hinzugefügt!');
                        location.reload();
                    }else if(msg.indexOf("failed") != -1){
                       alert('Fehler! Bitte das Formular vollständig und korrekt ausfüllen!');
                    }else if(msg.indexOf("done") != -1){
                        alert('Erfolgreich hinzugefügt!');
                        location.replace("index.php?action=overview");
                    }
                }
            });
        });
        $('#done').click(function () {
            last = true;
            $('#form1').submit();

        });

    });

</script>
