<?php

include "/home/circuitp/tempmon.ro/monitor/inc/queries.php";

?>



<?php


if(isset($_POST['submit']))
{
    $name = $_POST['name'];
    $address = $_POST['address'];
    $location_id = $_POST['location_id'];
    $user_id = $_POST['user_id'];

    try {
        $res = editLocation($user_id, $name, $address, (int)$location_id);
    } catch (Exception $e)
    {
        header('Location: http://tempmon.ro/configuration?edit_location=0');
    }

    if($res)
    {
        header('Location: http://tempmon.ro/configuration?edit_location=1');
    } else {
        header('Location: http://tempmon.ro/configuration?edit_location=0');
    }
}
?>