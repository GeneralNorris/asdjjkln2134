<?php
//2011-08-04 
if(!(defined('CONF_APPNAME'))) {header("location: ../index.php");exit();};
user_check_access('admin,contactor_manager', true);

require_once("functions/statistic.php");
require_once("functions/user.php");


?>
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="excanvas.min.js"></script><![endif]-->
<script src="js/libs/flot/jquery.flot.js" type="text/javascript" charset="utf-8"></script>
<style>
	td,th {
		vertical-align:top;
		text-align:left;
		font-weight:normal;
	}
	.inputs td{
		padding:0;
		background:white;
	}
	input[type=checkbox], input[type=radio] {
	    vertical-align: middle;
	    position: relative;
	    bottom: 2px;
	 }
	#logyears, #logactions, #users{
		max-height:220px;
		padding:5px;
	}

	#logyears, #logactions, #users, #legend{
		background:white;
		overflow-y:auto;
		overflow-x:visible;
		white-space:nowrap;
	}

	#dataTable {
		table-layout:fixed;
	}
	#dataTable td,#dataTable th {
		text-align:right;
		font-weight:normal;
	}
	#dataTable th:first-child {
		width:150px;
	}
</style>
<div class="actionHead">
	<h1>Statistik</h1>
	<div class="buttonbar">
		
	</div>
</div>
<p><br></p>
<table style="width:auto" cellspacing="0" cellpadding="0">
	<caption style="width:100%">Aktionen der Benutzer pro Monat</caption>
	<tr>
		<td colspan="3" height="310">
			<div id="holder" style="width:700px;height:300px;"></div>
		</td>
		<td rowspan="4">
			<div id="legend"></div>
		</td>
	</tr>
	<tr>
		<th height="20">Jahre</th>
		<th>Aktionen</th>
		<th>Benutzer</th>
	</tr>
	<tr class="inputs">
		<td>
			<div id="logyears">
			<?php echo statitic_getYearsCheckboxesHTML(); ?>
			</div>
		</td>
		<td>
			<div id="logactions">
			<?php echo statistic_getLogactionCheckboxesHTML(); ?>
			</div>
		</td>
		<td>
			<div id="users">
			<?php echo statistic_getLogUserCheckboxesHTML(); ?>
			</div>
		</td>
	</tr>
	<tr>
		<th colspan="3" height="20">
		</th>
	</tr>
</table>
