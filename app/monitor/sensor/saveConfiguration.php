<?php

    if (isset($_POST['submit'])) {

        if (filter_has_var(INPUT_POST,'submit')) {

        $tel1 = $_POST['telefon'];
        $tempMin = $_POST['mintemp'];
        $tempMax = $_POST['maxtemp'];

        $storeId = $_POST['_storeID'];
        $sensorId = $_POST['_sensorID'];

        while($fp = fsockopen("89.33.44.80", 12041, $errno, $errstr, 30))
        {
            if (!$fp) {
                echo "$errstr ($errno)<br />\n";
            } else {
                fwrite($fp, "DATA-FROM-INTERFACE\r\n1$tel1;1;0;2;0;3;<Tel4>;$storeId;$sensorId:$tempMin:$tempMax:600\r\n");
                while (!feof($fp)) {
                    echo fgets($fp, 128);
                }
                fclose($fp);
    
                header("Location: http://tempmon.ro/demo/");
            }
        }
    }
}

?>

