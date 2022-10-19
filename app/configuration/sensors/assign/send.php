<?php

include "/home/circuitp/tempmon.ro/monitor/inc/queries.php";

?>

<?php

if(isset($_POST['submit']))
{
    $id = $_POST['id'];
    $locationid = $_POST['location_id'];


    try {
        $res = assignSensorToLocation($id, $locationid);
        $d = editSensor((int)$id, '', '', '', '', '', '', '', 1);
    } catch (Exception $e)
    {
        header('Location: http://tempmon.ro/configuration?assigned_sensor=0');
    }

    if($res && $d)
    {
        header('Location: http://tempmon.ro/configuration?assigned_sensor=1');
    } else {
        header('Location: http://tempmon.ro/configuration?assigned_sensor=0');
    }
}

?>