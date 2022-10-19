<?php

include "/home/circuitp/tempmon.ro/monitor/inc/queries.php";

?>

<?php


if(isset($_POST['submit']))
{
    $id = (int)$_POST['id'];

    try {
        $res = deleteSensor($id);
    } catch (Exception $e) {
        header('Location: http://tempmon.ro/configuration?deleted_sensor=0');
    }

    if($res) {
        header('Location: http://tempmon.ro/configuration?deleted_sensor=1');
    } else {
        header('Location: http://tempmon.ro/configuration?deleted_sensor=0');
    }
}

?>