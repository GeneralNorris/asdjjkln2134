<?php
$userId = $_POST['userID'];
$kinder = array();
$kinderArray = kind_isChild($userId);
$kinderCount = count($kinder);

foreach ($_POST as $key => $value) {

	@list($type, $subkey) = explode("_", $key);
	if($type == "kind"){
		for ($i = 0; $i < count($value); $i++) {//$value ist ein array mit den Werten pro kind
			$kinder[$i][$subkey] = trim($value[$i]);
		}
	}
}

$kinderCount = count($kinder);
for ($i = 0; $i < $kinderCount; $i++) {
	if(empty($kinder[$i]["vorname"])){ //|| empty($kinder[$i]["artikel"])){
		unset($kinder[$i]);
	} else {
		$kinder[$i]["geburtstag"] = $kinder[$i]["geb-j"]."-".$kinder[$i]["geb-m"]."-".$kinder[$i]["geb-d"];
		unset($kinder[$i]["geb-d"]);
		unset($kinder[$i]["geb-m"]);
		unset($kinder[$i]["geb-j"]);
	}
}
$kinder = array_values($kinder);
if(count($kinder) > 0){
	foreach ($kinder as $kind) {
		kind_add($kind, $userId);
	}
}