<?php

include "/home/circuitp/tempmon.ro/monitor/inc/queries.php";

?>

<?php

if(isset($_POST['submit']))
{
    $id = $_POST['id'];
    $name = $_POST['name'];
    $storeid = $_POST['storeid'];
    $sensorid = $_POST['sensorid'];
    $mintemp = $_POST['mintemp'];
    $maxtemp = $_POST['maxtemp'];
    $phone = $_POST['phone'];
    $report_freq = $_POST['report_freq'];
    
    try {
        $res = editSensor((int)$id, $name, '', '', (int)$maxtemp, (int)$mintemp, (int)$phone, (int)$report_freq, '');
    } catch (Exception $e)
    {
        header('Location: http://tempmon.ro/configuration?edited_sensor=0');
    }

    if($res)
    {
        $fp = fsockopen("89.33.44.80", 12041, $errno, $errstr, 30);
        if (!$fp) {
            header('Location: http://tempmon.ro/raport?offline=1');
        } else {
            fwrite($fp, "DATA-FROM-INTERFACE\r\n1$phone;1;0000000000;2;0000000000;3;0000000000;$storeid;$sensorid:$mintemp:$maxtemp:$report_freq\r\n");
            while (!feof($fp)) {
                echo fgets($fp, 128);
            }
            fclose($fp);
            if(isset($_POST['location_id']) && !empty($_POST['location_id'])) {
                header('Location: http://tempmon.ro/configuration?success=1&location_id='. $_POST['location_id']);
            }else {
                header('Location: http://tempmon.ro/configuration?success=1');
            }
            
        }
    } else {
        header('Location: http://tempmon.ro/configuration?edited_sensor=0');
    }
}

?>