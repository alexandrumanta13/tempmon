<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include "/home/circuitp/tempmon.ro/monitor/inc/queries.php";
include "/home/circuitp/tempmon.ro/monitor/inc/mysql_inc.php";


$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if ($contentType === "application/json") {


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
        

        // $start = '2019-06-06 00:00:00';
        // $end = '2019-06-06 23:59:59';
        $start = date('Y-m-d 00:00:00');
        $end = date('Y-m-d 23:59:59');

        if($sensor_id || $sensor_id == "0" && $location_id || $location_id == "0")
        {
            // $query = " SELECT current_temp, DAY(reported_at) AS day, HOUR(reported_at) AS hour
            // FROM temperature_sensor_data_test 
            // WHERE `reported_at` >= '$start' AND `reported_at` <= '$end'
            // AND SensorID = '$sensor_id'
            // AND LocationID = '$location_id'
            // GROUP BY DAY(reported_at), HOUR(reported_at)
            //             ";
            $current_day = date("j");
            $current_month = date("n");
            $query = " SELECT sensorid, locationid, DAY(reported_at) as day, HOUR(reported_at) AS hour, current_temp, reported_at
            FROM temperature_sensor_data_test tsdt
            INNER JOIN (SELECT MAX(reported_at) AS last from temperature_sensor_data_test GROUP BY HOUR(reported_at), DAY(reported_at)) tsdt2 
            ON tsdt2.last = tsdt.reported_at
            WHERE tsdt.SensorID = '$sensor_id' AND tsdt.LocationID = '$location_id' AND DAY(reported_at) = '$current_day' AND MONTH(reported_at) = '$current_month'
            GROUP BY HOUR(reported_at), DAY(reported_at)";

            $result = mysqli_query($mysqli, $query);
            $count = mysqli_num_rows($result);


            if($count > 0){


                $users = array();
                $users["body"] = array();
                $users["count"] = $count;

                while ($row = mysqli_fetch_array($result))
                {

                    array_push($users["body"], array('day' => $row['day'], 'hour' => $row['hour'], 'current_temp' => $row['current_temp']));
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


// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $sensor_id = $_POST['sensor_id'];
//     $location_id = $_POST['location_id'];

//     if($sensor_id || $sensor_id == "0" && $location_id || $location_id == "0")
//     {
//         $query = " SELECT current_temp, DAY(reported_at) AS day, HOUR(reported_at) AS hour
//         FROM temperature_sensor_data_test 
//         WHERE `reported_at` >= '2019-06-06 00:00:00' AND `reported_at` <= '2019-06-06 23:59:59'
//         AND SensorID = '$sensor_id'
//         AND LocationID = '$location_id'
//         GROUP BY DAY(reported_at), HOUR(reported_at)
//                     ";

//         $result = mysqli_query($mysqli, $query);
//         $count = mysqli_num_rows($result);


//         if($count > 0){


//             $users = array();
//             $users["body"] = array();
//             $users["count"] = $count;

//             while ($row = mysqli_fetch_array($result))
//             {

//                 array_push($users["body"], array('day' => $row['day'], 'hour' => $row['hour'], 'current_temp' => $row['current_temp']));
//             }

//             echo json_encode($users);
//         }
//         else {

//             echo json_encode(
//                 array("body" => array(), "count" => 0)
//             );
//         }

//     } else {
//         echo json_encode(
//             array("body" => array(), "count" => 0, "error" => "No data found.")
//         );
//     }
// } else {
//     echo json_encode(
//         array("error" => "Something went wrong.")
//     );
// }


?>