<?php
//2010-06-09 
require_once("conf.php");
require_once("functions/db.php");
require_once("functions/core.php");
require_once("functions/datei.php");
core_init();

if(isset($_GET["id"])){
	
	$id = (int) $_GET["id"];
	$datei = datei_getById($id);
	if(empty($datei)){
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
		header("Status: 404 Not Found");
		$_SERVER['REDIRECT_STATUS'] = 404;
		exit;
	}
	
	$dst_width = false;
	$dst_height = false;
	if(isset($_GET["d"])){
		$d =  explode("x", $_GET["d"]);
		if(count($d) == 2){
			$dst_width = min((int) $d[0], 600);
			$dst_height = min((int) $d[1], 600);
		}
	}
	
	$filename = $datei["name"];
	if (!empty($datei["erweiterung"])) {
		$filename .= '.'.$datei["erweiterung"];
	}
	
	header('Last-Modified: '.gmdate('D, d M Y H:i:s', time()).' GMT', true, 200);
	header('Expires: '.gmdate('D, d M Y H:i:s', time()+3600).' GMT'); //TODO: prüfen wie man den chache des Browses besser nutzt... muss doch http://pritomkumar.blogspot.de/2013/09/php-image-output-and-browser-caching.html
	header('Content-type: '.$datei["mimetype"]);
	header('Content-disposition: inline; filename="'.$filename.'"');
	header('Content-transfer-encoding: binary');
	
	//header('Content-length: '.$datei["size"]);
	
	
	
	//FESTSTELLEN, ob es sich um ein verarbeitbares Image handelt
	$alogSuffix = $datei["erweiterung"];
	if($datei["mimetype"] == 'image/jpeg' || $datei["mimetype"] == 'image/pjpeg'){ 
		$alogSuffix = 'jpeg';
	}
	if($dst_width && function_exists('ImageCreateFrom' . $alogSuffix) === true){
		// Get new sizes
		list($src_width, $src_height) = getimagesize(CONF_FILEUPLOAD_DESTPATH.$datei['uid']);
		$scale =  min( $dst_width/$src_width, $dst_height/$src_height);
		$newwidth = $src_width * $scale;
		$newheight = $src_height * $scale;
		
		// Load
		$source = call_user_func('ImageCreateFrom' . $alogSuffix, CONF_FILEUPLOAD_DESTPATH.$datei['uid']);
		$thumb = imagecreatetruecolor($newwidth, $newheight);
		
		// Resize && Output
		//transparenz retten
	
		  //TODO: ins dateisystem schreiben und gleiche auflösung nicht immer wieder erstellen (bei prüfung datei uploadzeit prüfen)
		switch ($alogSuffix) {
			case 'gif':
				imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $src_width, $src_height);
				ImageGIF($thumb, null);
				break;
			case 'png':
				imagealphablending($thumb, false);
				imagesavealpha($thumb, true);
				imagealphablending($source, true);
				imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $src_width, $src_height);		
				ImagePNG($thumb, null, 9);
				break;
			case 'jpeg':
			case 'jpg':
				 imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $src_width, $src_height);
				 ImageJPEG($thumb, null, 90);
				break;
			default:
				;
			break;
		}
	
		imagedestroy($thumb);
		imagedestroy($source);
	} else {
		readfile(CONF_FILEUPLOAD_DESTPATH.$datei['uid']);
	}
}

