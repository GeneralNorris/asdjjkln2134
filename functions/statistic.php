<?php
require_once("functions/log.php");



/**
 *
 * @param int $year
 * @param string $log_actionid
 * @return array[int] 12 Werte (index 0 Januar ... 11 Dezember)
 */
function statitic_getContactsPerMonth($year, $log_actionid, $userid=-1){
	$and="";
	$logActions = log_get_actions();
	if(!array_key_exists($log_actionid, $logActions)){
		//$log_actionid ist unbekannt, fallback auf contactnew
		$log_actionid = "pageimpression";
	}

	if($userid > 0){
		$and = " AND userid = ".(int)$userid." ";
	}

	$sql = "
		SELECT MONTH(date) AS month, count(*) AS monthcount
		FROM
			sys_log
		WHERE
			YEAR(date) = ".(int)$year."
		".$and."
		AND
			log_actionid = '".db_secure_chars($log_actionid)."'
		GROUP BY MONTH(date)
	";

	$year=array();
	//mit Nullen vorbelegen, falls Monate nicht in der Ergebnisliste vorkommen
	for ($i = 0; $i < 12; $i++) {
		$year[$i] = array($i, 0);
	}
	$res = db_query($sql);

	while ($rs = db_fetch_array($res)) {
		$year[(int)$rs['month']-1] = array((int)$rs['month']-1,(int)$rs['monthcount']);
	}
	return $year;
}

function statistic_getGeschenkeTotal(){
	
}

function statitic_getYearsCheckboxesHTML(){
	$result="";
	$years = log_get_years();
	foreach ($years as $year) {
		$result .= '<label><input type="checkbox" name="year_'.$year.'" value="'.$year.'">'.$year.'</label><br>';
 	}
 	return $result;
}

function statistic_getLogactionCheckboxesHTML(){
	$result="";
	$logactions = log_get_actions();
	foreach ($logactions as $logaction) {
		$result .= '<label><input type="checkbox" name="logaction_'.$logaction['id'].'" value="'.$logaction['id'].'">'.$logaction['helptextshort'].'</label><br>';
 	}
 	return $result;
}

function statistic_getLogUserCheckboxesHTML(){
	$result='';
	$userids = log_get_userids();
	$users = array();
	foreach ($userids as $userid) {
		$users[] = array("id"=>$userid,"login"=>user_loginByID($userid));
	}

	usort($users, function($a,$b){
		return ((strcasecmp( $a['login'] , $b['login']) < 0)? -1:1);
	});

	foreach ($users as $user) {
		$result .= '<label><input type="checkbox" name="userid_'.$user['id'].'" value="'.$user['id'].'">'.$user['login'].'</label><br>';
 	}
 	$result = '<label><input type="checkbox" name="userid_all" value="all">Alle</label><br>'.$result;
 	return $result;
}

