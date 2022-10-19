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
    $location_id = $_POST['location_id'];
    $telephone = $_POST['telephone'];

    $res = setPhoneToLocation($location_id, $telephone);


    if($res)
    {
        header('Location: http://tempmon.ro/dashboard?phone_set=1');
    } else {
        header('Location: http://tempmon.ro/dashboard?phone_set=0');
    }

}

?>