<?php
//user_check_login();
//$userId = $_POST['userID'];
//$kinder = array();
//
//foreach ($_POST as $key => $value) {
//
//	@list($type, $subkey) = explode("_", $key);
//	if($type == "kind"){
//		for ($i = 0; $i < count($value); $i++) {//$value ist ein array mit den Werten pro kind
//			$kinder[$i][$subkey] = trim($value[$i]);
//		}
//	}
//}
//
//$kinderCount = count($kinder);
//for ($i = 0; $i < $kinderCount; $i++) {
//	if(empty($kinder[$i]["vorname"])){
//		unset($kinder[$i]);
//	} else {
//		$kinder[$i]["geburtstag"] = $kinder[$i]["geb-j"]."-".$kinder[$i]["geb-m"]."-".$kinder[$i]["geb-d"];
//		unset($kinder[$i]["geb-d"]);
//		unset($kinder[$i]["geb-m"]);
//		unset($kinder[$i]["geb-j"]);
//	}
//}
//$kinder = array_values($kinder);
//if(count($kinder) > 0){
//	foreach ($kinder as $kind) {
//		kind_add($kind, $userId);
//	}
////	mail_send_userEdit($userId);
//}
header("location: index.php");