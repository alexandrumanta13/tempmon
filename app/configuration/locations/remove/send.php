<?php

include "/home/circuitp/tempmon.ro/monitor/inc/queries.php";

?>

<?php

if(isset($_POST['submit']))
{
    $location_id = $_POST['location_id'];


    try {
        $res = deleteLocation((int)$location_id);
    } catch (Exception $e)
    {
        header('Location: http://tempmon.ro/configuration?deleted_location=0');
    }

    if($res)
    {
        header('Location: http://tempmon.ro/configuration?deleted_location=1');
    } else {
        header('Location: http://tempmon.ro/configuration?deleted_location=0');
    }
}

?>