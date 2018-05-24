<?php
function db_connect() {
	$conn = mysql_connect(CONF_DBHOST, CONF_DBUSER, CONF_DBPASSWORD);
	if($conn === false) {
		die ("Konnte keine Verbindung zur Datenbank aufbauen");
	}
	mysql_select_db(CONF_DBNAME);

	//http://www.phpwact.org/php/i18n/utf-8/mysql; http://www.buildblog.de/2009/01/02/mysql-server-auf-utf-8-umstellen/
	mysql_set_charset(CONF_DBCHARSET, $conn);
	//zeitzone einstellen, damit datum/zeit stimmen
	db_query("SET time_zone = '".CONF_TIMEZONE."'");

	return true;
}

function db_secure_chars($value) {
	return mysql_real_escape_string($value);
}

function db_num_rows($result) {
	return mysql_num_rows($result);
}

function db_affected_rows() {
	return mysql_affected_rows();
}

function db_autoinc_id() {
	return mysql_insert_id();
}

function db_query($sql, $dieOnError = true) {

	$result = mysql_query($sql);
	if ($result === false && $dieOnError) {
		die('Ungültige Abfrage: ' . mysql_error() .$sql);
	}
	return $result;
}

function db_result($resultID,$offset) {
	return mysql_result($resultID, $offset);
}

function db_fetch_array($sql, $result_type = 0) {
	if($result_type == 0)
		$result_type = MYSQL_BOTH;
	elseif(1)
		$result_type = MYSQL_ASSOC;
	elseif(2)
		$result_type = MYSQL_NUM;
	else
		$result_type = MYSQL_BOTH;

	return mysql_fetch_array($sql, $result_type);
}