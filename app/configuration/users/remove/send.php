<?php
    include "/home/circuitp/tempmon.ro/monitor/inc/queries.php";
?>

<?php


//TODO: make delete assignation from locations associated with this user
if(isset($_POST['submit']))
{
    $user_id = $_POST['user_id'];

    try {
        $res = deleteUser($user_id);
    } catch (Exception $e)
    {
        header('Location: http://tempmon.ro/configuration?success=false');
    }

    if($res)
    {
        header('Location: http://tempmon.ro/configuration?deleted_user=1');
    } else {
        header('Location: http://tempmon.ro/configuration?deleted_user=0');
    }
}

?>