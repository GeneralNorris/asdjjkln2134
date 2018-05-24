<?php 
//2014-06-03 
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin', true);

require_once("functions/config.php");
require_once("functions/antrag.php");
require_once("functions/user.php");


$_SESSION["config_system_clean_guiID"] = uniqid(time());
$hasAntraege = (count(antrag_getArr()) > 0);
if(isset($_GET['delete'])){
	if($_GET['delete'] == 1){
		echo "Zu alte Kinder wurden gelöscht!";
	}else{
		echo "Keine Zu alten Kinder!";
	}
}


?>

<div class="actionHead">
	<h1>Konfiguration</h1>
	<div class="buttonbar">
	</div>			
</div>

<form id="form_config_system" method="post" action="do_backend_action.php?action=do_config_set&config_system_clean=1">
	<br>
	<label><input id ="spende" name="spenden_input_enabled" type="checkbox" <?php echo ((config_get("spenden_input_enabled",0) > 0)? ' checked="checked"':'')?>>Spenden annehmen</label>
	<br>
	<label><input id = "antrag1" name="antraege_input_enabled" type="checkbox" <?php echo ((config_get("antraege_input_enabled",0) > 0)? ' checked="checked"':'')?>>Geschenke aussuchen möglich</label>
	<br>
	<label><input name="antraege_input_enabled_special" type="checkbox" <?php echo ((config_get("antraege_input_enabled_special",0) > 0)? ' checked="checked"':'')?>>Geschenke aussuchen geheim möglich (Die Geschenk-Annahme funktioniert, es erscheinen aber keine Links im Menü dort hin und es wird keine Email versendet.)</label>
	<br><br>
	<button type="submit" title="">speichern</button>
	<br>
	<button type="button" id="deactivate" >Alle Accounts inaktiv schalten</button>

	<button type="button" id="deleteKinder"  >Zu Alte Kinder Löschen</button>
	<br>
	<button type="button" id="deleteToken">EinmalLinks löschen</button>
</form>

<?php if($hasAntraege && (config_get("antraege_input_enabled",1) == 0) && (config_get("spenden_input_enabled",1) == 0)){?>
<form id="form_config_system_clean" method="post" action="do_backend_action.php?action=do_config_set&config_system_clean=1">
	<input name="config_system_clean_guiID" id="config_system_clean_guiID" type="hidden" value="">
	<br><br>
	<h2>System bereinigen: <button type="submit" style="background-color:red; color:white;" title="System für einen Neustart bereinigen">Alle Anträge und Spenden löschen!</button></h2>
</form>
<?php } ?>

<script>
$(function(){
	$('#form_config_system_clean').submit(function(e){
		var doclean = confirm("ACHTUNG:\n\nWollen Sie wirklich alle Spenden, und Anträge löschen?\n\n");
		if(doclean){
			var doclean2 = confirm("VORSICHT:\n\n\n\nDie Daten werden unwiederruflich GELÖSCHT!\ Sind Sie sich sicher?\n\n\n\n\n\n");
			if(doclean2){
				$('#config_system_clean_guiID').val('<?php echo $_SESSION["config_system_clean_guiID"]; ?>');
				return true;
			}
		} 
		return false;
	});
});
$(function() {
	if ($("#spende").is(':checked') || $("#antrag1").is(':checked'))
		$("#deleteKinder").hide();
	else
		$("#deleteKinder").show();
});
$(function(){
	$('#deleteKinder').click(function(){
		var answer = confirm ("Zu alte Kinder werden gelöscht! Fortfahren?")
		if (answer){
			window.location.assign("portal.php?action=do_delete_tooOld")}
	});
});

$(function(){
	$('#deactivate').click(function(){
			var answer = confirm ("Alle Accounts werden inaktiv geschaltet. Fortfahren?")
			if (answer){
				window.location.assign("portal.php?action=do_deactivate_all")}
	});
});

$(function(){
	$('#deleteToken').click(function(){
		var answer = confirm ("Alle EinmalLinks werden gelöscht. Fortfahren?")
		if (answer){
			window.location.assign("portal.php?action=do_delete_token")}
	});
});
</script>

