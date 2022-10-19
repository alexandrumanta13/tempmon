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

    if($location_id || $location_id == 0)
    {
        $query = " SELECT ts.id, ts.name, ts.sensorid, ts.storeid, ts.maxtemp, ts.mintemp, tsdt.current_temp,tsdt.interval_min, tsdt.interval_max, tsdt.report_frequency, tsdt.reported_at FROM temperature_sensors ts 
        INNER JOIN temperature_sensor_data_test tsdt ON ts.sensorid = tsdt.SensorID AND ts.storeid = tsdt.LocationID 
        INNER JOIN (SELECT MAX(reported_at) as last FROM temperature_sensor_data_test GROUP BY SensorID, LocationID) tsdt2 
        ON tsdt2.last = tsdt.reported_at
        WHERE ts.assigned = 1 AND ts.storeid = $location_id
        GROUP BY ts.sensorid, ts.storeid
                 ";
        $result = mysqli_query($mysqli, $query);
        $count = mysqli_num_rows($result);


        if($count > 0){


            $sensors = array();
            $sensors["body"] = array();
            $sensors["count"] = $count;

            while ($row = mysqli_fetch_array($result))
            {

                array_push($sensors["body"], array(
                    'id' => $row['id'], 
                    'name' => $row['name'] ? : 'Senzor '.$row['sensorid'],
                    'SensorID' => $row['sensorid'], 
                    'storeid' => $row['storeid'],
                    'current_temp' => $row['current_temp'],
                    'maxtemp' => $row['maxtemp'],
                    'mintemp' => $row['mintemp'], 
                    'interval_min' => $row['interval_min'], 
                    'interval_max' => $row['interval_max'],
                    'report_frequency' => $row['report_frequency'],
                    'reported_at' => $row['reported_at']
                
                ));
            }

            echo json_encode($sensors);
        }
        else {

            echo json_encode(
                array("body" => array(), "count" => 0)
            );
        }

    } else {
        echo json_encode(
            array("body" => array(), "count" => 0, "error" => "No location identified.")
        );
    }
} else {
    echo json_encode(
        array("error" => "Something went wrong.")
    );
}


?>