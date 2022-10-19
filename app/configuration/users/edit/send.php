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
    $user_id = $_POST['user_id'];

    try {
    
        $res = editUser((int)$user_id, $email, '', (int)$role_id, (int)$location_id);
    } catch (Exception $e) {
        header('Location: http://tempmon.ro/configuration?edited_user=0');
        return;
    }

    if($res)
    {
        header('Location: http://tempmon.ro/configuration?edited_user=1');
    }
    
}

?>