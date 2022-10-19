<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include "/home/circuitp/tempmon.ro/monitor/inc/queries.php";
include "/home/circuitp/tempmon.ro/monitor/inc/mysql_inc.php";



if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $type = $_POST['type'];
        $sensor_id = $_POST['sensor_id'];
        $location_id = $_POST['location_id'];
        $reported_at = $_POST['reported_at'];

        $query = "INSERT INTO `temperature_logs`(`type`,`sensor_id`,`location_id`,`reported_at`) VALUES ('$type', '$sensor_id', '$location_id', '$reported_at')";
        $result = mysqli_query($mysqli, $query);

        if($result)
        {
            echo json_encode(['success' => true, 'message' => 'Log has been inserted']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Something went wrong.']);
        }



}


?>