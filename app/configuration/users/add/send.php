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
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role_id = $_POST['role_id'];
    $location_id = $_POST['location_id'];


    $res = addUser($email, $password, (int)$role_id);

    if($res)
    {
        assignUserToLocation((int)$location_id, (int)$res);
        header('Location: http://tempmon.ro/configuration?user_added=1');
    }

}

?>