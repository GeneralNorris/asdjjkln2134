<?php
$userId = $_POST['userID'];
$kinder = array();
$kinderArray = kind_isChild($userId);
$kinderCount = count($kinder);

//print_r($_POST);
//exit;
foreach ($_POST as $key => $value) {

	@list($type, $subkey) = explode("_", $key);
	if($type == "kind"){
		for ($i = 0; $i < count($value); $i++) {
			if(strpos($subkey, "geburtstag") !== false){
				$geburtstag= date("Y-m-d", core_timestampFromGermanDate(trim($value[$i])));
				$kinder[$i][$subkey] = trim($geburtstag);
			}else {
				$kinder[$i][$subkey] = trim($value[$i]);
			}
		}
	}
}
for ($i = 0; $i < $kinderCount; $i++) {
	if(empty($kinder[$i]["id"])|| !in_array($kinder[$i]["id"], $kinderArray)||empty($kinder[$i]['vorname'])){
		unset($kinder[$i]);
	}
}

if (user_check_access('admin', false)) {
	$kinder = array_values($kinder);
	$count = count($kinder);

	for ($i = 0; $i < $count; $i++) {
		$kindId = $kinder[$i]['id'];
		unset($kinder[$i]['id']);
		kind_update($kindId, $kinder[$i]);
		//kind_update_name($kinder[$i]['id'], $kinder[$i]['vorname']);
		//kind_update_geburtstag($kinder[$i]['id'], $kinder[$i]['geburtstag']);
	}

}