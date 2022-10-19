<?php

function countStores() {
    include "mysql_inc.php";

    $query = "SELECT COUNT(DISTINCT(LocationID)) AS count FROM temperature_sensor_data_test";
    $result = mysqli_query($mysqli, $query);
    while($row = mysqli_fetch_array($result)) {
        echo $row['count']." stores";
     }
}

function countSensorsParameters($sensor_type, $period, $period_type) {

    include "mysql_inc.php";

    if($sensor_type == 'outside') {
        $query = "SELECT COUNT(DISTINCT(SensorID)) as count
        FROM `temperature_sensor_data_test` 
        WHERE `current_temp` < `interval_min` 
        AND `current_temp` > `interval_max`
        AND reported_at BETWEEN CURDATE() - INTERVAL $period $period_type AND CURDATE()";
    }
    if($sensor_type == 'inside') {
        $query = "SELECT COUNT(DISTINCT(SensorID)) as count
        FROM `temperature_sensor_data_test` 
        WHERE `current_temp` > `interval_min` 
        AND `current_temp` < `interval_max`
        AND reported_at BETWEEN CURDATE() - INTERVAL $period $period_type AND CURDATE()";
    }

    
    $result = mysqli_query($mysqli, $query);
    while($row = mysqli_fetch_array($result)) {
        echo $row['count']." sensors ".$sensor_type." parameters";
    }
}


function showGatewayStatus($id, $interval, $interval_type, $sensor_id, $location_id) {
    include "mysql_inc.php";

    $query = "SELECT COUNT(DISTINCT(SensorID)) as count
              FROM `temperature_sensor_data_test` 
              WHERE reported_at BETWEEN CURRENT_TIME() - INTERVAL $interval $interval_type AND CURRENT_TIME()
              AND SensorID = '$sensor_id' AND LocationID = '$location_id'";

    
    $result = mysqli_query($mysqli, $query);


    while($row = mysqli_fetch_array($result)) {
            if($row['count'] > 0) {
                return "gateway-online";
            }
            if($row['count'] == 0)
            {
                return "gateway-offline";
            }
    }

}

function showGatewayStatusByFrequency($sensor_id, $location_id) {
    include "mysql_inc.php";

    $query = "SELECT DISTINCT(SensorID), reported_at, report_frequency
              FROM `temperature_sensor_data_test` 
              WHERE SensorID = '$sensor_id' AND LocationID = '$location_id' GROUP BY reported_at DESC LIMIT 1";

   //echo $query;die();
    $result = mysqli_query($mysqli, $query);


    while($row = mysqli_fetch_array($result)) {
   
        $current_datetime =  $old = date('Y-m-d H:i:s');
       
        
        $frequency = date('Y-m-d H:i:s', strtotime("+". 2.5*$row['report_frequency'] ." minutes", strtotime($row['reported_at'])));  
       
    
        if($frequency < $current_datetime) {
            return "gateway-offline";
        }else {
             return "gateway-online";
        }
    }

}


function showLatestBatteryLevel($sensor_id, $location_id) {
    include "mysql_inc.php";

    $query = "SELECT *
              FROM `temperature_sensor_data_test`
              WHERE SensorID = '$sensor_id' 
              AND LocationID = '$location_id'
              ORDER BY reported_at DESC LIMIT 1";

    $result = mysqli_query($mysqli, $query);

    while($row = mysqli_fetch_array($result)) {
        $battery_level = 100 - ((3.15 - $row['last_bat_level']) * 100);
        if($battery_level > 100) {
            echo "100%"; 
        } else {
            echo $battery_level."%";
        } 
    }
}

function showCurrentTemperature($sensor_id, $location_id) {
    include "mysql_inc.php";

    $query = "SELECT *
              FROM `temperature_sensor_data_test` 
              WHERE SensorID = '$sensor_id'
              AND LocationID = '$location_id'
              ORDER BY reported_at DESC LIMIT 1";

    $result = mysqli_query($mysqli, $query);


    $count = mysqli_num_rows($result);
    $zeros = [];

    if($count < 1) {

    } else {

    }
    

    while($row = mysqli_fetch_array($result)) {
        echo $row['current_temp']."&deg;C"; 
    }
}

function testWeek($sensor_id) {
    include "mysql_inc.php";
    //  $query = "SELECT DAY(reported_at) as day, LocationID, current_temp, interval_min, interval_max, reported_at, min_temp, max_temp
    // FROM `temperature_sensor_data_test` tsdt
    // INNER JOIN ( SELECT MAX(reported_at) as last FROM temperature_sensor_data_test GROUP BY SensorID, DAY(reported_at) ) tsdt2
    // ON tsdt.reported_at = tsdt2.last
    // WHERE YEARWEEK(`reported_at`, 1) = YEARWEEK( CURDATE() - INTERVAL 1 WEEK, 1)
    // GROUP BY DAY(reported_at), SensorID
    // HAVING LocationID = 0 AND SensorID = '$sensor_id'
    // ORDER BY day ASC";

    $query = "SELECT DAY(reported_at) as day, LocationID, current_temp, interval_min, interval_max
    FROM `temperature_sensor_data_test` tsdt
    INNER JOIN ( SELECT MAX(reported_at) as last FROM temperature_sensor_data_test GROUP BY SensorID, DAY(reported_at) ) tsdt2
    ON tsdt.reported_at = tsdt2.last
    GROUP BY DAY(reported_at), SensorID
    HAVING LocationID = 0 AND SensorID = '$sensor_id'
    ORDER BY day ASC LIMIT 7";

    // echo $query;die();
    $result = mysqli_query($mysqli, $query);

    while($row = mysqli_fetch_array($result)) {
        $temps[] = $row; 
    }
    return $temps;
}

function showCurrentTemperatureThisWeek($sensor_id) {
    include "mysql_inc.php";

    $currentDay = (int)date('d');
    $existingDays = [];
    $totalDays = [];


    $query = "SELECT DAY(reported_at) as day, LocationID, current_temp, interval_min, interval_max
    FROM `temperature_sensor_data_test` tsdt
    INNER JOIN ( SELECT MAX(reported_at) as last FROM temperature_sensor_data_test GROUP BY SensorID, DAY(reported_at) ) tsdt2
    ON tsdt.reported_at = tsdt2.last
    GROUP BY DAY(reported_at), SensorID
    HAVING LocationID = 0 AND SensorID = '$sensor_id'
    ORDER BY day ASC LIMIT 7";
    // $query = "SELECT DAY(reported_at) as day, LocationID, current_temp, interval_min, interval_max, reported_at, min_temp, max_temp
    // FROM `temperature_sensor_data_test` tsdt
    // INNER JOIN ( SELECT MAX(reported_at) as last FROM temperature_sensor_data_test GROUP BY SensorID, DAY(reported_at) ) tsdt2
    // ON tsdt.reported_at = tsdt2.last
    // WHERE YEARWEEK('$date') = YEARWEEK(NOW())
    // GROUP BY DAY(reported_at), SensorID
    // HAVING LocationID = 0 AND SensorID = '$sensor_id'
    // ORDER BY day ASC";

    $result = mysqli_query($mysqli, $query);

    while($row = mysqli_fetch_array($result))
    {
        array_push($existingDays, (int)$row['day']);
    }


    $temporary_table = "CREATE TABLE IF NOT EXISTS temperature_sp_week (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        day INT(11) NOT NULL,
        LocationID INT(11) NOT NULL,
        current_temp VARCHAR(45) NOT NULL,
        interval_min VARCHAR(45) NOT NULL,
        interval_max VARCHAR(45) NOT NULL

    )";
    $abc = mysqli_query($mysqli, $temporary_table);


    for($i = $currentDay - 6; $i <= $currentDay; $i++)
    {
        array_push($totalDays, $i);
    }
    
    $missingDays = array_diff($totalDays, $existingDays);

    for($i = 0; $i < count($missingDays); $i++)
    {
        $query = "INSERT INTO `temperature_sp_week`(`day`, `LocationID`, `current_temp`, `interval_min`, `interval_max`) VALUES ('$missingDays[$i]', '0', '0', '0', '0')";
        $result = mysqli_query($mysqli, $query);
    }


    $union = "SELECT DAY(reported_at) as day, LocationID, current_temp, interval_min, interval_max
    FROM `temperature_sensor_data_test` tsdt
    INNER JOIN ( SELECT MAX(reported_at) as last FROM temperature_sensor_data_test GROUP BY SensorID, DAY(reported_at) ) tsdt2
    ON tsdt.reported_at = tsdt2.last
    GROUP BY DAY(reported_at), SensorID
    HAVING LocationID = 0 AND SensorID = '$sensor_id'
    UNION 
    SELECT day, LocationID, current_temp, interval_min, interval_max FROM temperature_sp_week
    ORDER BY day ASC
    LIMIT 7";

    $result_union = mysqli_query($mysqli, $union);

    while($row = mysqli_fetch_array($result_union))
    {
        $data[] = $row;
    }
    deleteTemporaryTable('temperature_sp_week');
    return $data;

}

function showCurrentTemperaturesLastMonth($sensor_id)
{
    include "mysql_inc.php";

    $existingDays = [];
    $totalDays = [];
   
    // $query = "SELECT day(tsdt2.last) as day, current_temp, interval_min, interval_max FROM temperature_sensor_data_test tsdt
    // INNER JOIN (SELECT MAX(reported_at) as last FROM temperature_sensor_data_test GROUP BY SensorID, DAY(reported_at) HAVING SensorID = '$sensor_id') tsdt2
    // ON tsdt.reported_at = tsdt2.last
    // WHERE SensorID = 3
    // AND reported_at BETWEEN FIRST_DAY(NOW()) AND LAST_DAY(DATE_ADD(NOW(), INTERVAL 1 DAY))";
    $query = "SELECT day(tsdt2.last) as day, current_temp, interval_min, interval_max FROM temperature_sensor_data_test tsdt
    INNER JOIN (SELECT MAX(reported_at) as last FROM temperature_sensor_data_test GROUP BY SensorID, DAY(reported_at) HAVING SensorID = '$sensor_id') tsdt2
    ON tsdt.reported_at = tsdt2.last
    WHERE SensorID = 3
    ORDER BY reported_at ASC LIMIT 30";
    // echo $query;die();

    

    $result = mysqli_query($mysqli, $query);

    while($row = mysqli_fetch_array($result)) {
        $temps[] = $row; 
    }
    return $temps;

    // $result = mysqli_query($mysqli, $query);
    // while($row = mysqli_fetch_array($result))
    // {
    //     array_push($existingDays, (int)$row['day']);
    // }

    // $temporary_table = "CREATE TABLE IF NOT EXISTS temperature_sp_month (
    //     id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    //     day INT(11) NOT NULL,
    //     current_temp VARCHAR(45) NOT NULL,
    //     interval_min VARCHAR(45) NOT NULL,
    //     interval_max VARCHAR(45) NOT NULL
    // )";
    // $abc = mysqli_query($mysqli, $temporary_table);

    // for($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, date('n'), date('Y')); $i++)
    // {
    //     array_push($totalDays, $i);
    // }

    // $missingDays = array_diff($totalDays, $existingDays);
    
    // for($i = 0; $i <= cal_days_in_month(CAL_GREGORIAN, date('n'), date('Y')); $i++)
    // {
    //     if($missingDays[$i])
    //     {
    //         $query = "INSERT INTO `temperature_sp_month`(`day`, `current_temp`, `interval_min`, `interval_max`) VALUES ('$i', '0', '0', '0')";
    //         $result = mysqli_query($mysqli, $query);
    //     }
    // }


    // $union = "SELECT day(tsdt2.last) as day, current_temp, interval_min, interval_max FROM temperature_sensor_data_test tsdt
    // INNER JOIN (SELECT MAX(reported_at) as last FROM temperature_sensor_data_test GROUP BY SensorID, DAY(reported_at) HAVING SensorID = '$sensor_id') tsdt2
    // ON tsdt.reported_at = tsdt2.last
    // WHERE SensorID = 3
    // AND reported_at BETWEEN FIRST_DAY(NOW()) AND LAST_DAY(DATE_ADD(NOW(), INTERVAL 1 DAY))
    // UNION
    // SELECT day, current_temp, interval_min, interval_max FROM temperature_sp_month
    // ORDER BY day ASC";

    // $result_union = mysqli_query($mysqli, $union);


    // while($row = mysqli_fetch_array($result_union))
    // {
    //     $data[] = $row;
    // }

    // deleteTemporaryTable('temperature_sp_month');

    // return $data;

    // $query = "SELECT a2.maxtemp, a2.day, a2.month
    // FROM temperature_sensor_data_test a1
    // INNER JOIN
    // (
    //   SELECT max(reported_at) as max, max(current_temp) as maxtemp, day(reported_at) as day, month(reported_at) as month
    //   FROM temperature_sensor_data_test
    //   WHERE reported_at BETWEEN (SELECT DATE_ADD(DATE_ADD(LAST_DAY(CURRENT_DATE()),INTERVAL 1 DAY), INTERVAL - 1 MONTH)) AND CURRENT_DATE()
    //   GROUP BY date(reported_at)
    // ) a2
    //   ON a1.reported_at = a2.max
    // ORDER BY reported_at ASC";

    // $result = mysqli_query($mysqli, $query);
    

    // while($row = mysqli_fetch_array($result)) {
    //     $temps[] = $row['current_temp']; 
    // }
    // return $temps;
}

function testLastHours($sensor_id) {
    include "mysql_inc.php";
    $beginningOfCurrentDate = date('Y-m-d 00:00:00');
 
    $currentDate = date('Y-m-d 23:59:59');
    $query = "SELECT current_temp, DAY(reported_at) AS day, HOUR(reported_at) AS hour
    FROM temperature_sensor_data_test 
    WHERE `reported_at` >= '$beginningOfCurrentDate' AND `reported_at` <= '$currentDate'
    AND SensorID = '$sensor_id'
    GROUP BY DAY(reported_at), HOUR(reported_at)";
    //echo $query;die();

    

    $result = mysqli_query($mysqli, $query);

    while($row = mysqli_fetch_array($result)) {
        $temps[] = $row; 
    }
    return $temps;
}


function showCurrentTemperaturesLastHours($sensor_id)
{
    include "mysql_inc.php";

    $hours = [];
    $totalHours = [];
    $missingHours = [];
    $currentDay = date('d');

    if (!function_exists('array_key_first')) {
        function array_key_first(array $arr) {
            foreach($arr as $key => $unused) {
                return $key;
            }
            return NULL;
        }
    }

    if (! function_exists("array_key_last")) {
        function array_key_last($array) {
            if (!is_array($array) || empty($array)) {
                return NULL;
            }
            
            return array_keys($array)[count($array)-1];
        }
    }

    $beginningOfCurrentDate = date('Y-m-d 00:00:00');
    // $beginningOfCurrentDate = '2019-04-20 00:00:00';
    // $currentDate = '2019-04-20 23:59:59';
    // // $currentDate = date('Y-m-d H:i:s', strtotime('+8 hours'));
    // // $currentDate = date('Y-m-d H:i:s', strtotime('+8 hours'));
    $currentDate = date('Y-m-d 23:59:59');


    $temporary_table = "CREATE TABLE IF NOT EXISTS temperature_sp_hour (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        current_temp INT(11) NOT NULL,
        day INT(11) NOT NULL,
        hour INT(11) NOT NULL
    )";
    $abc = mysqli_query($mysqli, $temporary_table);

    $query = "SELECT table1.current_temp, DAY(reported_at) AS day, HOUR(reported_at) AS hour
    FROM temperature_sensor_data_test table1
    INNER JOIN(
      SELECT max(`reported_at`) as last FROM `temperature_sensor_data_test` GROUP BY DATE_FORMAT(`reported_at`,'%Y%m%d%H')
    ) table2
      on table1.reported_at = table2.last
    WHERE `reported_at` >= '$beginningOfCurrentDate' AND `reported_at` <= '$currentDate'
    AND SensorID = '$sensor_id'
    GROUP BY DAY(reported_at), HOUR(reported_at)";

    $result = mysqli_query($mysqli, $query);

    while($row = mysqli_fetch_array($result))
    {
        $temp[] = $row;
    }

    for($i = 0; $i < count($temp); $i++)
    {
        array_push($hours, (int)$temp[$i]['hour']);
    }

    for($i = 0; $i <= 23; $i++)
    {
        array_push($totalHours, $i);
    }

    $results = array_diff($totalHours, $hours);

    // return '<pre>' . var_export($results, true) . '</pre>';

    foreach($results as $value)
    {
        $query = "INSERT INTO `temperature_sp_hour`(`current_temp`, `day`, `hour`) VALUES ('0', '$currentDay', '$value')";
        $result = mysqli_query($mysqli, $query);
    }
    // for($i = 0, $j = 0; $i < count($totalHours), $j < 23; $i++, $j++)
    // {
    //     if(isset($results[$i]) || isset($results[$i]) == "0")
    //     {
    //         $query = "INSERT INTO `temperature_sp_hour`(`current_temp`, `day`, `hour`) VALUES ('0', '$currentDay', '$results[$i]')";
    //         $result = mysqli_query($mysqli, $query);
    //     }
    //     if($i == count($results) && isset($results[$i]) && $results[$i] !== $j)
    //     {
    //         $query = "INSERT INTO `temperature_sp_hour`(`current_temp`, `day`, `hour`) VALUES ('0', '$currentDay', '$j')";
    //         $result = mysqli_query($mysqli, $query);
    //     }
    // }


    $union = "SELECT table1.current_temp, DAY(reported_at) AS day, HOUR(reported_at) AS hour
    FROM temperature_sensor_data_test table1
    INNER JOIN(
      SELECT max(`reported_at`) as last FROM `temperature_sensor_data_test` GROUP BY DATE_FORMAT(`reported_at`,'%Y%m%d%H')
    ) table2
      on table1.reported_at = table2.last
    WHERE `reported_at` >= '$beginningOfCurrentDate' AND `reported_at` <= '$currentDate'
    AND SensorID = '$sensor_id'
    GROUP BY DAY(reported_at), HOUR(reported_at)
    UNION
    SELECT current_temp, day, hour FROM `temperature_sp_hour`
    ORDER BY hour ASC";

    $result_union = mysqli_query($mysqli, $union);
    while($row = mysqli_fetch_array($result_union))
    {
        $final[] = $row;
    }

    deleteTemporaryTable('temperature_sp_hour');


    return $final;
}

function deleteTemporaryTable($name)
{
    include "mysql_inc.php";

    $query = "DROP TABLE `$name`";
    $result = mysqli_query($mysqli, $query);
}


function insertLatestConfiguration($sensorID, $storeID, $tel1, $tempMin, $tempMax, $report_frequency)
{
    include "mysql_inc.php";

    $current_configuration = "SELECT * FROM temperature_sensors WHERE storeid = $storeID AND sensorid = $sensorID ORDER BY created_at DESC LIMIT 1";
    $configResult = mysqli_query($mysqli, $current_configuration);

    if(mysqli_num_rows($configResult) == 1)
    {
    
        $config = mysqli_fetch_object($configResult);
        $id = $config->id;
        $query = "UPDATE temperature_sensors SET `sensorid` = $sensorID, `storeid` = $storeID, `phone` = $tel1, `mintemp` = $tempMin, `maxtemp` = $tempMax, `report_freq` = $report_frequency WHERE id = $id";
        $result = mysqli_query($mysqli, $query);
    } else {
        $query = "INSERT INTO temperature_sensors (sensorid, storeid, phone, mintemp, maxtemp, report_freq) VALUES ($sensorID, $storeID, $tel1, $tempMin, $tempMax, $report_frequency)";
        $result = mysqli_query($mysqli, $query);
    }
    return true;
}


function getConfiguration($value, $sensorID, $storeID)
{
    include "mysql_inc.php";

    $query = null;

    switch($value) {
        case 'telefon':
            $query = "SELECT phone from temperature_sensors WHERE sensorid = $sensorID AND storeid = $storeID ORDER BY created_at DESC LIMIT 1";
            break;
        case 'tempMin':
            $query = "SELECT mintemp from temperature_sensors WHERE sensorid = '$sensorID' AND storeid = '$storeID' ORDER BY created_at DESC LIMIT 1";
            break;
        case 'tempMax':
            $query = "SELECT maxtemp from temperature_sensors WHERE sensorid = '$sensorID' AND storeid = '$storeID' ORDER BY created_at DESC LIMIT 1";
            break;
    }

    if($query) {
        $result = mysqli_query($mysqli, $query);
        
        while($row = mysqli_fetch_array($result)) {
            if($value === 'telefon') {
                echo $row['phone'];
            } elseif($value === 'tempMin') {
                echo $row['mintemp'];
            } else {
                echo $row['maxtemp'];
            }
        }
    }
}


function getRoles()
{
    include "mysql_inc.php";

    $query = "SELECT * FROM user_roles";
    $result = mysqli_query($mysqli, $query);
    $temp = [];

    while($row = mysqli_fetch_array($result))
    {
        $temp[] = $row;
    }

    return $temp;
}

function getUsers($locationID)
{

    include "mysql_inc.php";

    $query = " SELECT id, email, ur.type as type FROM users u 
                   INNER JOIN users_locations ul ON ul.user_id = u.id 
                   INNER JOIN locations l ON l.id = ul.location_id 
                   INNER JOIN user_roles ur ON ur.id = u.role_id
                   WHERE l.id = '$locationID' 
                 ";

    $result = mysqli_query($mysqli, $query);

    return $result;

}

function addUser($email, $pass, $role_id)
{
    include "mysql_inc.php";

    $hash = password_hash($pass, PASSWORD_BCRYPT);
    $check = findUserByEmail($email);
    
    if($check)
    {
        //echo "true";die();
        header('Location: http://tempmon.ro/demo/configuration?email_used=1');
        return false;
    }
    
    $query = "INSERT INTO `users`(`email`, `password`, `role_id`) VALUES ('$email', '$hash', '$role_id')";
    $result = mysqli_query($mysqli, $query);
        
    return (int)mysqli_insert_id($mysqli);
}

function findUserByEmail($email)
{
    include "mysql_inc.php";

    $value = [];

    $query = " SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($mysqli, $query);

    if(mysqli_num_rows($result) > 0)
    {
        return true;
    } else {
        return false;
    }
}

function findUserById($id)
{
    include "mysql_inc.php";

    $value = [];

    $query = " SELECT * FROM `users` WHERE id = '$id'";
    $result = mysqli_query($mysqli, $query);

    while($row = mysqli_fetch_array($result))
    {
        $value[] = $row;
    }

    return $value;
}

function lastUserId()
{
    include "mysql_inc.php";
    $query = " SELECT * FROM `users`";
    $result = mysqli_query($mysqli, $query);

    return mysqli_insert_id($mysqli);
}


function assignUserToLocation($location_id, $user_id)
{
    include "mysql_inc.php";


    $query = " INSERT INTO `users_locations`(`location_id`, `user_id`) VALUES ('$location_id', '$user_id') ";
    $result = mysqli_query($mysqli, $query);

    return true;
}

function editLocationOfUser($location_id, $user_id)
{
    include "mysql_inc.php";

    $query = " UPDATE `users_locations`(`location_id`, `user_id`) SET `location_id` = '$location_id' WHERE `user_id` = '$user_id'";
    $result = mysqli_query($mysqli, $query);

    return true;
}

function editUser($id, $email = '', $password = '', $role_id = '', $location_id = '')
{
    include "mysql_inc.php";

    if($email)
    {
        $query = "UPDATE `users` SET email = '$email' WHERE id='$id'";
        $result = mysqli_query($mysqli, $query);
    }
    if($password)
    {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $query = "UPDATE `users` SET password = '$password' WHERE id='$id'";
        $result = mysqli_query($mysqli, $query);
    }
    if($role_id)
    {
        $hash = password_hash($password);
        $query = "UPDATE `users` SET `role_id` = '$role_id' WHERE id='$id'";
        $result = mysqli_query($mysqli, $query);
    }
    if($location_id || $location_id == "0")
    {
        $query = "UPDATE `users_locations` SET `location_id` = '$location_id' WHERE `user_id`='$id'";
        $result = mysqli_query($mysqli, $query);
    }
    
    return true;
}

function deleteUser($id)
{
    include "mysql_inc.php";

    $query = "DELETE FROM `users` WHERE id = '$id'";
    $result = mysqli_query($mysqli, $query);

    return true;
}



function getLocations()
{
    include "mysql_inc.php";

    $query = " SELECT * FROM locations ";
    $result = mysqli_query($mysqli, $query);

    $temp = [];

    while($row = mysqli_fetch_array($result)) {
        $temp[] = $row;
    }

    return $temp;
}

function getLocationByID($id)
{
    include "mysql_inc.php";

    $query = " SELECT * FROM `locations` WHERE id = '$id' LIMIT 1";
    $result = mysqli_query($mysqli, $query);

    $temp = [];

    while($row = mysqli_fetch_array($result)) {
        $temp[] = $row;
    }

    return $temp;
}

function getLocationNameByID($id)
{
    include "mysql_inc.php";

    $query = " SELECT `name` FROM `locations` WHERE id = '$id' LIMIT 1";
    $result = mysqli_query($mysqli, $query);

    $temp = [];

    while($row = mysqli_fetch_array($result)) {
        echo $row['name'];
    }
}

function getLocationNameByLocID($id)
{
    include "mysql_inc.php";

    $query = " SELECT `name` FROM `locations` WHERE LocationID = '$id' LIMIT 1";
    $result = mysqli_query($mysqli, $query);

    $temp = [];

    while($row = mysqli_fetch_array($result)) {
        echo $row['name'];
    }
}

function getSensorName($storeid, $sensorid)
{
    include "mysql_inc.php";

    $query = " SELECT `name` FROM `temperature_sensors` WHERE storeid = '$storeid' AND sensorid = '$sensorid' LIMIT 1";
    $result = mysqli_query($mysqli, $query);

    $temp = [];

    while($row = mysqli_fetch_array($result)) {
        echo $row['name'];
    }
}

function addLocation($name, $address, $location_id)
{
    include "mysql_inc.php";

    $query = " INSERT INTO `locations`(`name`,`address`, `LocationID`) VALUES ('$name', '$address', '$location_id')";
    $result = mysqli_query($mysqli, $query);

    return true;
}

function editLocation($id, $name = '', $address = '', $location_id = '')
{
    include "mysql_inc.php";

    if($name)
    {
        $query = "UPDATE `locations` SET `name` = '$name' WHERE id='$id'";
        $result = mysqli_query($mysqli, $query);
    }
    if($address)
    {
        $query = "UPDATE `locations` SET `address` = '$address' WHERE id='$id'";
        $result = mysqli_query($mysqli, $query);
    }
    if($location_id || $location_id == "0")
    {
        $query = "UPDATE `locations` SET `LocationID` = '$location_id' WHERE id='$id'";
        $result = mysqli_query($mysqli, $query);
    }

    return true;
}

function deleteLocation($id)
{
    include "mysql_inc.php";

    $query = "DELETE FROM `locations` WHERE id = '$id'";
    $result = mysqli_query($mysqli, $query);

    return true;
}


function getSensors($location_id)
{
    include "mysql_inc.php";

    $query = " SELECT tsdt.id, tsdt.last_bat_level, tsdt.reported_at, tsdt.SensorID, tsdt.current_temp, tsdt.LocationID, IF(`reported_at` BETWEEN CURRENT_TIME() - INTERVAL `report_frequency` SECOND AND CURRENT_TIME(), 'Online', 'Offline') AS status
               FROM `temperature_sensor_data_test` tsdt
               INNER JOIN (SELECT MAX(id) AS maxid
                    FROM `temperature_sensor_data_test` sec
                    GROUP BY sec.SensorID) sec2
               ON (tsdt.id = sec2.maxid)
               WHERE tsdt.LocationID = '$location_id'
             ";
    $result = mysqli_query($mysqli, $query);

    $temp = [];

    while($row = mysqli_fetch_array($result))
    {
        $temp[] = $row;
    }

    return $temp;
}

function getAllSensors()
{
    include "mysql_inc.php";

    $query = " SELECT tsdt.id, tsdt.last_bat_level, tsdt.reported_at, tsdt.SensorID, tsdt.current_temp, tsdt.LocationID, IF(`reported_at` BETWEEN CURRENT_TIME() - INTERVAL `report_frequency` SECOND AND CURRENT_TIME(), 'Online', 'Offline') AS status
               FROM `temperature_sensor_data_test` tsdt
               INNER JOIN (SELECT MAX(id) AS maxid
                    FROM `temperature_sensor_data_test` sec
                    GROUP BY sec.SensorID) sec2
               ON (tsdt.id = sec2.maxid)
             ";
    $result = mysqli_query($mysqli, $query);

    $temp = [];

    while($row = mysqli_fetch_array($result))
    {
        $temp[] = $row;
    }

    return $temp;
}

function getUnassignedSensors()
{
    include "mysql_inc.php";

    $query = "SELECT id, sensorid, storeid FROM temperature_sensors WHERE assigned = 0 GROUP BY sensorid, storeid";
    $result = mysqli_query($mysqli, $query);

    $temp = [];
    
    while($row = mysqli_fetch_array($result))
    {
        $temp[] = $row;
    }

    return $temp;
}


function getAllSensorsFix()
{
    include "mysql_inc.php";

    $query = "SELECT id, sensorid, storeid FROM temperature_sensors GROUP BY sensorid, storeid";
    $result = mysqli_query($mysqli, $query);

    $temp = [];
    
    while($row = mysqli_fetch_array($result))
    {
        $temp[] = $row;
    }

    return $temp;
}

function getLastIncident($sensorid, $storeid)
{
    include "mysql_inc.php";

    $query = "SELECT `type`, `reported_at` FROM temperature_logs WHERE sensor_id = $sensorid AND location_id = $storeid ORDER BY reported_at DESC LIMIT 1";
    $result = mysqli_query($mysqli, $query);
    
    $temp = [];
    
    while($row = mysqli_fetch_array($result))
    {
        $temp[] = $row;
    }

    return $temp;
}

function getNoOfSensorsByLocation($location_id)
{
    include "mysql_inc.php";

    $query = "SELECT COUNT(id) as count FROM temperature_sensors ts WHERE assigned = 1 AND storeid = $location_id";
    $result = mysqli_query($mysqli, $query);

    while($row = mysqli_fetch_array($result))
    {
        echo $row['count']." senzori";
    }

    return true;
}

function findSensorByID($id)
{
    include "mysql_inc.php";

    $query = " SELECT * FROM `temperature_sensors` WHERE `id` = '$id' LIMIT 1";
    $result = mysqli_query($mysqli, $query);
    $temp = [];

    while($row = mysqli_fetch_array($result))
    {
        $temp[] = $row;
    }

    return $temp;
}

function addSensor($sensorid, $storeid, $maxtemp, $mintemp, $phone, $report_freq)
{
    include "mysql_inc.php";

    $query = " INSERT INTO `temperature_sensors`(`sensorid`,`storeid`,`maxtemp`,`mintemp`,`phone`,`report_freq`) VALUES ('$sensorid', '$storeid', '$maxtemp', '$mintemp', '$phone', '$report_freq')";
    $result = mysqli_query($mysqli, $query);

    return true;
}

function editSensor($id, $name = '', $sensorid = '', $storeid = '', $maxtemp = '', $mintemp = '', $phone = '', $report_freq = '', $assigned = '')
{
    include "mysql_inc.php";


    if($name)
    {
        $query = "UPDATE `temperature_sensors` SET `name` = '$name' WHERE id='$id'";
        $result = mysqli_query($mysqli, $query);
    }
    if($assigned)
    {
        $query = "UPDATE `temperature_sensors` SET `assigned` = '$assigned' WHERE id='$id'";
        $result = mysqli_query($mysqli, $query);
    }
    if($sensorid)
    {
        $query = "UPDATE `temperature_sensors` SET `sensorid` = '$sensorid' WHERE id='$id'";
        $result = mysqli_query($mysqli, $query);
    }
    if($storeid)
    {
        $query = "UPDATE `temperature_sensors` SET `storeid` = '$storeid' WHERE id='$id'";
        $result = mysqli_query($mysqli, $query);  
    }
    if($maxtemp)
    {
        $query = "UPDATE `temperature_sensors` SET `maxtemp` = '$maxtemp' WHERE id='$id'";
        $result = mysqli_query($mysqli, $query); 
    }
    if($mintemp)
    {
        $query = "UPDATE `temperature_sensors` SET `mintemp` = '$mintemp' WHERE id='$id'";
        $result = mysqli_query($mysqli, $query); 
    }
    if($phone)
    {
        $query = "UPDATE `temperature_sensors` SET `phone` = '$phone' WHERE id='$id'";
        $result = mysqli_query($mysqli, $query); 
    }
    if($report_freq)
    {
        $query = "UPDATE `temperature_sensors` SET `report_freq` = '$report_freq' WHERE id='$id'";
        $result = mysqli_query($mysqli, $query);
    }

    return true;
}

function deleteSensor($id)
{
    include "mysql_inc.php";

    $query = "DELETE FROM `temperature_sensors` WHERE id='$id'";
    $result = mysqli_query($mysqli, $query); 

    return true;
}


function assignSensorToLocation($id, $locationid)
{
    include "mysql_inc.php";

    $location_id = (int)$locationid;
    $id = (int)$id;

    $slquery = "SELECT * FROM `sensors_locations` WHERE `temperature_sensor_id` = '$id' LIMIT 1";
    $slresult = mysqli_query($mysqli, $slquery);


    $count = mysqli_num_rows($slresult);

    if($count == 1)
    {
        while($row = mysqli_fetch_array($slresult))
        {
            $slid = (int)$row['id'];
        }

        $query = " UPDATE `sensors_locations` SET `location_id` = '$location_id' WHERE `id` = '$slid' ";
        $result = mysqli_query($mysqli, $query);
    } else {
        $query = " INSERT INTO `sensors_locations`(`temperature_sensor_id`,`location_id`) VALUES ('$id', '$location_id')";
        $result = mysqli_query($mysqli, $query);
    }

    return true;
}

function getCurrentReportFrequency($sensorid, $storeid)
{
    include "mysql_inc.php";

    $query = "SELECT report_freq FROM temperature_sensors WHERE sensorid = '$sensorid' AND storeid = '$storeid' LIMIT 1";
    $result = mysqli_query($mysqli, $query);

    while($row = mysqli_fetch_array($result))
    {
        echo $row['report_freq'];
    }

}


function getAssignedSensors($location_id)
{
    include "mysql_inc.php";

    // $query = "SELECT ts.id, ts.name, ts.sensorid, ts.storeid, ts.maxtemp, ts.mintemp, tsdt.current_temp,tsdt.interval_min, tsdt.interval_max, tsdt.report_frequency, tsdt.reported_at FROM temperature_sensors ts 
    //     INNER JOIN temperature_sensor_data_test tsdt ON ts.sensorid = tsdt.SensorID AND ts.storeid = tsdt.LocationID 
    //     INNER JOIN (SELECT MAX(reported_at) as last FROM temperature_sensor_data_test GROUP BY SensorID, LocationID) tsdt2 
    //     ON tsdt2.last = tsdt.reported_at
    //     WHERE ts.assigned = 1 AND ts.storeid IN(0,1)
    //     GROUP BY ts.sensorid, ts.storeid";
    $query = "SELECT ts.id, ts.name, ts.sensorid, ts.storeid, ts.maxtemp, ts.mintemp, tsdt.current_temp,tsdt.interval_min, tsdt.interval_max, tsdt.report_frequency, tsdt.reported_at FROM temperature_sensors ts 
        INNER JOIN temperature_sensor_data_test tsdt ON ts.sensorid = tsdt.SensorID AND ts.storeid = tsdt.LocationID 
        INNER JOIN (SELECT MAX(reported_at) as last FROM temperature_sensor_data_test GROUP BY SensorID, LocationID) tsdt2 
        ON tsdt2.last = tsdt.reported_at
        WHERE ts.assigned = 1 AND ts.storeid = $location_id
        GROUP BY ts.sensorid, ts.storeid";
    $result = mysqli_query($mysqli, $query);

    while($row = mysqli_fetch_array($result))
    {
        $temp[] = $row;
    }

    return $temp;
}



function addLogs($location)
{
    include "mysql_inc.php";

    $query = "SELECT tsdt.SensorID, tsdt.LocationID, tsdt.current_temp, IF(tsdt.current_temp < tsdt.interval_min, 'under interval', 
         IF(tsdt.current_temp > tsdt.interval_max, 'over interval',
         IF(tsdt.reported_at BETWEEN CURRENT_TIME() - INTERVAL tsdt.report_frequency SECOND AND CURRENT_TIME(), 'Online', 'Offline'))) AS type, tsdt.reported_at
                                                        
                                                        
         FROM temperature_sensor_data_test tsdt
         INNER JOIN (SELECT MAX(id) as maxid FROM temperature_sensor_data_test tsdt2 GROUP BY SensorID) aj ON(tsdt.id = aj.maxid) 
         WHERE DATE(reported_at) = CURDATE()
         AND LocationID = '$location'";
    $result = mysqli_query($mysqli, $query);

    $temp = [];

    if(mysqli_num_rows($result) > 0)
    {
        while($row = mysqli_fetch_array($result)) {
            $temp[] = $row;
        }
        return $temp;
    }
    return false;
}

function showLast50Logs($sensorID)
{
    include "mysql_inc.php";

    $query = "SELECT loc.name, tsdt.SensorID, tsdt.current_temp, 
    IF(tsdt.current_temp < tsdt.interval_min, 'sub interval', 
        IF(tsdt.current_temp > tsdt.interval_max, 'peste interval', 
           IF(tsdt.reported_at NOT BETWEEN CURRENT_TIME() - INTERVAL 10 MINUTE AND CURRENT_TIME(), 'offline', 'online'))) 
           
           AS type, tsdt.reported_at, tsdt.last_bat_level, tsdt.min_temp,
    tsdt.max_temp,tsdt.interval_min,tsdt.interval_max
                                              
                                                   
    FROM temperature_sensor_data_test tsdt
    LEFT JOIN locations loc ON tsdt.LocationID = loc.LocationID
    WHERE tsdt.SensorID = $sensorID
    ORDER BY reported_at DESC
    LIMIT 25";

    $result = mysqli_query($mysqli, $query);
    
    $temp = [];
    while($row = mysqli_fetch_array($result))
    {
        $temp[] = $row;
    }

    return $temp;
}


function showNotifications($sensorID, $locationID)
{
    include "mysql_inc.php";

    $query = "SELECT type, sensor_id, location_id, l.name FROM temperature_logs t INNER JOIN locations l ON t.location_id = l.LocationID WHERE t.sensor_id = $sensorID AND t.location_id = $locationID ORDER BY reported_at DESC LIMIT 10";

    $result = mysqli_query($mysqli, $query);
    $temp = [];
    while($row = mysqli_fetch_array($result))
    {
        $temp[] = $row;
    }
    return $temp;
}

function getLocationPhone($location_id)
{
    include "mysql_inc.php";


    $current_configuration = "SELECT * FROM locations WHERE id = '$location_id' LIMIT 1";
    $configResult = mysqli_query($mysqli, $current_configuration);

    while($row = mysqli_fetch_array($configResult))
    {
        echo $row['telephone'];
    }
}

function setPhoneToLocation($location_id, $phone_number)
{

    include "mysql_inc.php";


    $current_configuration = "SELECT * FROM locations WHERE id = '$location_id'";
    $configResult = mysqli_query($mysqli, $current_configuration);

    if(mysqli_num_rows($configResult) == 1)
    {    
        $config = mysqli_fetch_object($configResult);
        $id = $config->id;
        $telephone = $config->telephone;


        $query = "UPDATE locations SET `telephone` = $phone_number WHERE id = $id";
        $result = mysqli_query($mysqli, $query);
    }
    return true;
}




?>
