<?php 
    include "/home/circuitp/tempmon.ro/monitor/inc/queries.php";
?>

<?php
    if ( isset( $_POST['submit'] ) ) {

        if ( filter_has_var( INPUT_POST, 'submit' ) ) {

        $tel1 = $_POST['tel1'];
        $tempMin = $_POST['tempMin'];
        $tempMax = $_POST['tempMax'];

        $storeId = $_POST['_storeID'];
        $sensorId = $_POST['_sensorID'];


        $reportFrequency = $_POST['_reportFrequency'];


        $fp = fsockopen("89.33.44.80", 12041, $errno, $errstr, 30);
        if (!$fp) {
            header('Location: http://tempmon.ro/raport?offline=1&sensorid='.$sensorId.'&storeid='.$storeId);
        } else {
            // fwrite($fp, "DATA-FROM-INTERFACE\r\n1$tel1;1;0000000000;2;0000000000;3;0000000000;$storeId;$sensorId:$tempMin:$tempMax:$reportFrequency\r\n");
            fwrite($fp, "DATA-FROM-INTERFACE\r\n10;1;0000000000;2;0000000000;3;0000000000;$storeId;$sensorId:$tempMin:$tempMax:$reportFrequency\r\n");
             insertLatestConfiguration((int)$sensorId, (int)$storeId, $tel1, $tempMin, $tempMax, $reportFrequency);
            while (!feof($fp)) {
                echo fgets($fp, 128);
            }
            fclose($fp);

            header('Location: http://tempmon.ro/raport?success=1&sensorid='.$sensorId.'&storeid='.$storeId);
        }
    }
}
?>

