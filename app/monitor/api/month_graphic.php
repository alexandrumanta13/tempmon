<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include "/home/circuitp/tempmon.ro/monitor/inc/queries.php";
include "/home/circuitp/tempmon.ro/monitor/inc/mysql_inc.php";



$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';


if($contentType == 'application/json') {
    $content = trim(file_get_contents("php://input"));
  
    $decoded = json_decode($content, true);
  
    //If json_decode failed, the JSON is invalid.
    if(! is_array($decoded)) {
        echo json_encode(
            array("error" => "Something went wrong.")
        );
    } else {
        $sensor_id = $decoded['sensor_id'];
        $location_id = $decoded['location_id'];


        if($sensor_id || $sensor_id == "0" && $location_id || $location_id == "0")
        {
            $query = " SELECT day(tsdt2.last) as day, month(tsdt2.last) as month, current_temp, interval_min, interval_max FROM temperature_sensor_data_test tsdt
            INNER JOIN (SELECT MAX(reported_at) as last FROM temperature_sensor_data_test GROUP BY SensorID, DAY(reported_at) HAVING SensorID = $sensor_id) tsdt2
            ON tsdt.reported_at = tsdt2.last
            WHERE SensorID = '$sensor_id'
            AND LocationID = '$location_id'
            AND reported_at BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()
            ORDER BY reported_at ASC
                        ";

            $result = mysqli_query($mysqli, $query);
            $count = mysqli_num_rows($result);


            if($count > 0){


                $users = array();
                $users["body"] = array();
                $users["count"] = $count;

                while ($row = mysqli_fetch_array($result))
                {

                    array_push($users["body"], array('day' => $row['day'], 'month' => $row['month'], 'current_temp' => $row['current_temp'], 'interval_min' => $row['interval_min'], 'interval_max' => $row['interval_max']));
                }

                echo json_encode($users);
            }
            else {

                echo json_encode(
                    array("body" => array(), "count" => 0)
                );
            }

        } else {
            echo json_encode(
                array("body" => array(), "count" => 0, "error" => "No data found.")
            );
            }
        }
}

?>