<?php
require_once("functions/config.php");
function core_init() {
	if (version_compare("5.3.1", PHP_VERSION, ">")) {
		echo "PHP_VERSION < 5.3.1";
		exit;
	}
	session_start();
	date_default_timezone_set(CONF_TIMEZONE);
	db_connect();
	config_init();
	//ob_start("ob_gzhandler"); // engelbaum: zip auf server aktivieren, hier den richtigen auswählen
	ob_start();
}

function core_get_action_content($action) {
	ob_start();
	if (!(core_check_include($action) && file_exists(CONF_DOCROOT."/backend_actions/".$action.".php"))) {
		$action = "backend_actions/welcome.php";
	} else {
		$action = "backend_actions/".$action.".php";
	}

	include($action);

	$action_content = ob_get_contents();
	ob_end_clean();

	return $action_content;
}
function core_get_teilhabe_action_content($action) {
	ob_start();
	if (!(core_check_include($action) && file_exists(CONF_DOCROOT."/teilhabe_actions/".$action.".php"))) {
		$action = "teilhabe_actions/login.php";
	} else {
		$action = "teilhabe_actions/".$action.".php";
	}
	include($action);
	$action_content = ob_get_contents();
	ob_end_clean();

	return $action_content;
}

function core_get_frontend_action_content($action){
    ob_start();
    if (!(core_check_include($action) && file_exists(CONF_DOCROOT."/frontend_actions/".$action.".php"))) {
        $action = "frontend_actions/start.php";
    } else {
        $action = "frontend_actions/".$action.".php";
    }
    include($action);
    $action_content = ob_get_contents();
    ob_end_clean();

    return $action_content;
}


function core_get_action_jsPath($action) {
	if (!(core_check_include($action) && file_exists(CONF_DOCROOT."/js/actions/".$action.".js"))) {
		$action = "";
	} else {																		//Anhängen der Version, damit der Browser geänderte Dateien auch sicher neu vom Server holt.
		//$action = '<script type="text/javascript" src="js/actions/'.$action.'.js?v='.CONF_APPVERSION.'"></script>';
		$action = '<script type="text/javascript" src="js/actions/'.$action.'.js"></script>';
	}
	return $action;
}



function core_validHTTPLink($link) {
    if(preg_match("/(http|https):\/\//", $link)) {
        return true;
    } else {
        return false;
    }
}


function core_row_backgroundcolor($shade) {
	$color = "F9F9F9";
	$shade = ($shade%4)*3;

	$r = hexdec(substr($color, 0, 2));
	$g = hexdec(substr($color, 2, 2));
	$b = hexdec(substr($color, 4, 2));
	$sum = ($r + $g + $b);

	$x = (($shade * 3) - $sum) / $sum;
	if ($x < 0)
		$x = -1 * $x;
	$r = intval($x * $r);
	$g = intval($x * $g);
	$b = intval($x * $b);
	$r = dechex($r);
	$g = dechex($g);
	$b = dechex($b);
	return $r.$g.$b;
}

function core_print_message($id, $headline, $text) {
	switch($id)
	{
		case 1:
			$headline="Zugriff verboten";
			$text="Ihr Benutzerkonto hat nicht das Recht, diesen Inhalt anzuzeigen.";
		break;
	}
	?>
		<div class="actionHead"><h1 style="color:red"><?php echo $headline; ?></h1></div>
		<br />
		<p><?php echo $text; ?></p>
		<br />
		<br />
	<?php
}

function core_check_include($name) {
	return preg_match("/^[a-z0-9\-_]{1,}$/i", $name);
}

function core_dateformat($zeit, $format="d.m.Y") {
	if ($zeit) {
		if (!core_is_unsigned_integer($zeit)) {
			$zeit = strtotime($zeit);
		}
		return(date($format, $zeit));
	} else {
		return;
	}
}

function core_timestampFromGermanDate($date) {
	//Wenn bereits ein Timestamp
	if (core_is_signed_integer($date)) {
		return (int) $date;
	}
	else //Wenn deutsches Datum (09.05.1977)
	{
		list($d, $m, $y) = explode(".", $date);
		return mktime(0, 0, 0, (int) $m, (int) $d, (int) $y);
	}
}

function core_is_signed_integer($val) {
	$val = str_replace(" ", "", trim($val));
	return preg_match("/^-?([0-9])+$/i", $val);
}

function core_is_unsigned_float($val) {
	$val = str_replace(" ", "", trim($val));
	return preg_match("/^([0-9])+([\.|,]([0-9])*)?$/i", $val);
}

function core_is_unsigned_integer($val) {
	$val = str_replace(" ", "", trim($val));
	return preg_match("/^([0-9])+$/i", $val);
}

function core_is_signed_float($val) {
	$val = str_replace(" ", "", trim($val));
	return preg_match("/^-?([0-9])+([\.|,]([0-9])*)?$/i", $val);
}

function core_securechars($input) {
	$chars = array( 'ä'=>'ae','Ä'=>'AE',
					'ö'=>'oe','Ö'=>'OE',
					'ü'=>'ue','Ü'=>'UE',
					'ß'=>'ss',
					'@'=>'at','€'=>'eur',
			);
	return(preg_replace("/([^a-z0-9-\.]+)/Ui", "_", strtr($input, $chars)));
}

/**
 * Erhält einen "SQL-String"/"array of assoc arrays" und generiert aus den Ergebnissen eine Excel lesbare CSV-Datei.
 * Diese wird Zeilenweise an den Browser übertragen.
 * @param strring $query
 */
function core_printCSV($dataSource) {
	if(gettype($dataSource) !== "array"){
		$dataSource = db_query($dataSource);
	}
	
	function getRow(&$dataSource, &$i){
		if(gettype($dataSource) === "array"){
			return isset($dataSource[$i])? $dataSource[$i]:false;
		} else {
			$trs = db_fetch_array($dataSource, MYSQL_ASSOC);
			return $trs;
		}
	}
	
	$filetime = gmdate("D, d M Y H:i:s", time())." GMT";
	header("Last-Modified: $filetime");
	header("Content-type: application/vnd.ms-excel");
	header("Content-disposition: attachment; filename=\"".core_securechars(core_dateformat(time(), "Y.m.d H-i-s")).'_'.core_securechars(strip_tags(CONF_APPNAME)).".csv\"");
	header("Content-Transfer-Encoding: chunked");
	header("Connection: Keep-Alive");
	header("Accept-Ranges: bytes");
	$i = 0;
	$replace = array("+49","0049");
	while ($rs = getRow($dataSource, $i)) {
		if ($i == 0) {
			echo '"'.implode('";"', array_keys($rs)).'"'."\r\n";
		}
		foreach ($rs as $key => $value) {
			// adslashes bringt hier nichts weil excel das ignoriert. zellen stehen in "..." damit trennzeichen eindeutig bleibt. alle vorkommen von " werden durch ' ersetzt.
			$rs[$key] = str_replace("\"", "'", utf8_decode($value));
			if($key == 'tel'){ //--> +49 oder 0049 auf führende 0 umwandeln
				$rs[$key] = str_replace($replace,'0',$rs[$key]);
				$rs[$key] = str_replace(" ","",$rs[$key]);
			}
			if(($key == 'tel' || $key == 'plz') && trim($rs[$key]) != ""){
				$rs[$key] = '=""'.$rs[$key].'""';// -> Zelle jedenfalls als String darstellen
			}
		}
		echo '"'.implode('";"', $rs).'"'."\r\n";
		$i++;
		flush();
	}
}