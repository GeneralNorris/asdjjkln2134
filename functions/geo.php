<?php 

function geo_check_deutsche_plz($plz){
	$query = db_query("SELECT count(*) AS count FROM sys_plz WHERE plz=".(int)$plz);
	$rs = db_fetch_array($query);

	if ($rs['count'] > 0) {
        return true;
	}
	return false;
}