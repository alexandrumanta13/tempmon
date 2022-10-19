<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include "/home/circuitp/tempmon.ro/monitor/inc/queries.php";
include "/home/circuitp/tempmon.ro/monitor/inc/mysql_inc.php";



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $location_id = $_POST['location_id'];

    if($location_id || $location_id == "0")
    {
        // $query = " SELECT tsdt.id, loc.name, tsdt.SensorID, tsdt.LocationID, tsdt.current_temp, IF(tsdt.current_temp < tsdt.interval_min, 'sub interval', 
        // IF(tsdt.current_temp > tsdt.interval_max, 'peste interval',
        //    IF(tsdt.reported_at BETWEEN CURRENT_TIME() - INTERVAL tsdt.report_frequency SECOND AND CURRENT_TIME(), 'online', 'offline'))) AS type, tsdt.reported_at
                                                          
                                                          
        // FROM temperature_sensor_data_test tsdt
        // INNER JOIN locations loc ON tsdt.LocationID = loc.id
        // INNER JOIN (SELECT MAX(id) as maxid FROM temperature_sensor_data_test tsdt2 WHERE LocationID = 4 GROUP BY SensorID) aj ON(tsdt.id = aj.maxid)
        // AND tsdt.LocationID IN (SELECT storeid FROM temperature_sensors WHERE storeid = '$location_id')
        // AND tsdt.SensorID IN (SELECT sensorid FROM temperature_sensors)
        //          ";
        $query = "SELECT tsdt.id, loc.name, tsdt.SensorID, tsdt.LocationID, tsdt.current_temp, 
        IF(tsdt.reported_at NOT BETWEEN CURRENT_TIME() - INTERVAL 30 SECOND AND CURRENT_TIME(), 'offline', 
           IF(tsdt.current_temp < tsdt.interval_min, 'sub interval', IF(tsdt.current_temp > tsdt.interval_max, 'peste interval', 'online'))) AS type, tsdt.reported_at
              FROM temperature_sensor_data_test tsdt
                INNER JOIN locations loc ON tsdt.LocationID = loc.LocationID
                INNER JOIN (SELECT MAX(id) as maxid FROM temperature_sensor_data_test tsdt2 WHERE LocationID = '$location_id' AND SensorID IN (SELECT sensorid FROM temperature_sensors) GROUP BY SensorID) aj ON(tsdt.id = aj.maxid)";
        
        $result = mysqli_query($mysqli, $query);
        $count = mysqli_num_rows($result);


        if($count > 0){


            $logs = array();
            $logs["body"] = array();
            $logs["count"] = $count;

            while ($row = mysqli_fetch_array($result))
            {

                array_push($logs["body"], array('SensorID' => $row['SensorID'], 'name' => $row['name'] ? : 'Senzor '.$row['sensorid'],'LocationID' => $row['LocationID'], 'LocationName' => $row['name'], 'current_temp' => $row['current_temp'], 'type' => $row['type'], 'reported_at' => $row['reported_at']));
            }

            echo json_encode($logs);
        }
        else {

            echo json_encode(
                array("body" => array(), "count" => 0)
            );
        }

    } else {
        echo json_encode(
            array("body" => array(), "count" => 0, "error" => "No logs found for location.")
        );
    }
} else {
    echo json_encode(
        array("error" => "Something went wrong.")
    );
}


?>