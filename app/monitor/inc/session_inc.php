<?php

session_start();

include "/home/circuitp/tempmon.ro/monitor/inc/mysql_inc.php";
// include "/home/circuitp/tempmon.ro/monitor/inc/queries.php";
   
$email_check = $_SESSION['login_email'];
$user = mysqli_query($mysqli, "select u.id, u.email, u.role_id, ur.type, ul.location_id FROM users u 
INNER JOIN user_roles ur ON u.role_id = ur.id 
INNER JOIN users_locations ul ON ul.user_id = u.id

WHERE u.email = '$email_check'");


$row = mysqli_fetch_array($user,MYSQLI_ASSOC);
$user_email = $row['email'];
$user_role = $row['role_id'];
$location_id = $_SESSION['location_id'];



if($_SERVER['REQUEST_URI'] == '/configuration/' && ($user_role == 3 || $user_role == 2))
{
    header("location: http://tempmon.ro/dashboard");
}


if($_SERVER['REQUEST_URI'] == '/demo/dashboard/' && isset($_GET['locationid']) && $_GET['locationid'] != $location_id)
{
    header("location: http://tempmon.ro/testast");
}

if(!isset($_SESSION['login_email'])){
    header("location: http://tempmon.ro/login.php");
   // die();
}

?>