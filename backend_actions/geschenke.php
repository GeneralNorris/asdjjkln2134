<?php
//2011-05-24 
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin,contactor_manager', true);
//require_once("functions/geschenke.php");

?>
<style>
	ul.group{
		 list-style-type: none;
		 margin: 0;
		 padding: 0;
		 float: left;
		 margin-right: 10px;
		 background: white;
		 padding: 5px;
		 min-width: 180px;
		 border:1px solid silver;
	}
	ul.group li{
		margin: 5px; padding: 5px;
		font-size: 1.2em;
	}
	ul.group li a{
		float: right;
	}
	ul.group li.ui-widget-header {
		background: #E0E5F3;
	}
</style>

<div class="actionHead">
	<h1>Geschenke</h1>
	<div class="buttonbar">

	</div>
</div>
<br />
<?php
	//team_printFreeContactorHTMLById();
// 	$groups = contactcat_listGroups();
// 	foreach ($groups as $group) {
// 		contactcat_printGroup($group['id'], $group['name']);
// 	}
?>
<div style="clear:both;"></div>