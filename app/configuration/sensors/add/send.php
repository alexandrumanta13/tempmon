<?php

include "/home/circuitp/tempmon.ro/monitor/inc/queries.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
error_reporting(E_ERROR | E_PARSE);
?>

<?php

if(isset($_POST['submit']))
{
    $sensor_id = $_POST['sensor_id'];
    $location_id = $_POST['location_id'];
    $mintemp = $_POST['mintemp'];
    $maxtemp = $_POST['maxtemp'];
    $phone = $_POST['phone'];
    $report_freq = $_POST['report_freq'];

    $res = addSensor((int)$sensor_id, (int)$location_id, (int)$mintemp, (int)$maxtemp, $phone, (int)$report_freq);

    if($res)
    {
        header('Location: http://tempmon.ro/demo/configuration?sensor_added=1');
    } else {
        header('Location: http://tempmon.ro/demo/configuration?sensor_added=0');
    }

}

?>