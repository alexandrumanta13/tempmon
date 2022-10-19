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
    $name = $_POST['name'];
    $address = $_POST['address'];
    $location_id = $_POST['location_id'];


    $res = addLocation($name, $address, (int)$location_id);

    if($res)
    {
        header('Location: http://tempmon.ro/configuration?location_added=1');
    } else {
        header('Location: http://tempmon.ro/configuration?location_added=0');
    }

}

?>