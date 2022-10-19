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
        $tr = $location_id == "0" ? 0 : $location_id;
        $query = " SELECT ts.id, tsdt.last_bat_level, tsdt.reported_at, tsdt.SensorID, tsdt.current_temp, tsdt.LocationID, IF(`reported_at` BETWEEN CURRENT_TIME() - INTERVAL `report_frequency` SECOND AND CURRENT_TIME(), 'Online', 'Offline') AS status
        FROM temperature_sensor_data_test tsdt
        INNER JOIN (SELECT MAX(id) AS maxid
                    FROM temperature_sensor_data_test sec
                    GROUP BY sec.SensorID) sec2
          ON (tsdt.id = sec2.maxid)
        INNER JOIN temperature_sensors ts ON ts.SensorID = tsdt.SensorID
        GROUP BY tsdt.sensorid
        HAVING tsdt.LocationID = $tr
                 ";

        $result = mysqli_query($mysqli, $query);
        $count = mysqli_num_rows($result);


        if($count > 0){


            $sensors = array();
            $sensors["body"] = array();
            $sensors["count"] = $count;

            while ($row = mysqli_fetch_array($result))
            {

                array_push($sensors["body"], array('id' => $row['id'], 'SensorID' => $row['SensorID'], 'battery' => $row['last_bat_level'], 'current_temp' => $row['current_temp'], 'status' => $row['status']));
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