<?php
/**
 * Created by PhpStorm.
 * User: lvarnholt
 * Date: 08.07.2016
 * Time: 13:45
 */



function text_saveAll($siteArray, $textArray){
    $result = false;
    for($i=0; $i < count($siteArray); $i++){
        $query = db_query("UPDATE text
                  SET text='".db_secure_chars($textArray[$i])."'
                  WHERE site ='".db_secure_chars($siteArray[$i])."'"
        );
        if($query){
            $result = true;
        }else{
            $result = false;
        }
    }
    return $result;
}

function text_getText($site){
    $si = db_secure_chars($site);
    $query = db_query("
                    SELECT text
                    FROM text
                    WHERE LOWER(site)=LOWER('".$si."')
                    ");
    $rs = db_fetch_array($query);
    $text = $rs['text'];
    return $text;
}
function text_save($site, $text){
    $query = db_query("UPDATE text
                  SET text='".db_secure_chars($text)."'
                  WHERE site ='".db_secure_chars($site)."'"
    );
    if($query){
        return true;
    }else{
        return false;
    }
}