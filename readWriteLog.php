<?php
/**
 * Created by PhpStorm.
 * User: lvarnholt
 * Date: 26.10.2017
 * Time: 15:12
 */
$file1 = "c:\\dev1\\de.engelbaum.geschenke-access.log";
//$file1 = "c:\\dev1\\Test.log";
$file2 = "c:\\dev1\\accessLogNew.log";
set_time_limit(0);
if($file_handle1 = fopen($file1, 'r')){
    $file_handle2 = fopen($file2, 'w');
    echo "Start!";
    while (!feof($file_handle1)) {

        $line = fgets($file_handle1);
        if (strpos($line, '2017:') !== false) {
            fwrite($file_handle2,$line);
        }

    }
    echo "Ende!";
    fclose($file_handle1);
    fclose($file_handle2);
}else{
    echo"Failed!";
}

