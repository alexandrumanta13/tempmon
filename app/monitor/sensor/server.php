<?php
require_once __DIR__ . '/vendor/autoload.php';
require "ConnectionsPool.php";

use React\Socket\ConnectionInterface;

// function get_parsed_sensor_data($packet)
// {
//     $sensor_data = explode("|", str_replace(' ', '', substr($packet, strpos($packet, 'sid'), strlen($packet))));
//     $parsed_sensor_data = array();
//     for($i = 0; $i < count($sensor_data); $i++) 
//     {
//         if(count($sensor_data) > 1) 
//         {
//             if(strlen($sensor_data[$i]) !== 0) 
//             {
//                 $parsed_sensor_data[$i] = array();
//                 $chunk = explode(":", $sensor_data[$i]);
//                 $storeId = preg_replace('/[^0-9]/', '', $chunk[0]);
//                 array_push($parsed_sensor_data[$i], $storeId);
//                 for($j = 1; $j < count($chunk); $j++)
//                 {
//                     if(count($chunk) > 1)
//                     {
//                         array_push($parsed_sensor_data[$i], $chunk[$j]);
//                     }
//                 }
//             }
//         }
//     }
//     return $parsed_sensor_data;
// }
        
$uri = '89.33.44.80:12041';

$loop = React\EventLoop\Factory::create();
$socket = new React\Socket\Server($uri, $loop);


$pool = new ConnectionsPool();

$getCurrentDate = "[".date('d.m.Y H:i:s')."]";


echo "$getCurrentDate Spinning up the wheels...\n";
echo "$getCurrentDate Socket server is alive!\n";

$socket->on('connection', function ($connection) use ($pool)
{

    $pool->add($connection);

    // $sensor_config = array();

    // $getCurrentDate = "[".date('d.m.Y H:i:s')."]";

    // $conn->write("$getCurrentDate Hello " . $conn->getRemoteAddress() . "!\n");
    // $conn->write("$getCurrentDate Send out sensor data!\n");

    // $conn->on('data', function ($packet) use ($conn, $sensor_config) 
    // {


    //     $con = mysqli_connect("localhost", "circuitp_sensor", '$G&01iGC[K@}', "circuitp_monitor");
    //     if(!$con)
    //         die("Failed to connect to database!");
    //     echo "Connection with database established!\n";

    //     if(preg_match('/sid=(?P<store_id>\d+):-1/', $packet, $matches_default))
    //     {
    //         $store_id = $matches_default['store_id'];
    //         $conn->write("DATA-FROM-SERVER\r\n");
    //         // $conn->write("10721157297;1;0721157297;2;0721157297;3;0721157297;$store_id;1:0:50:600\r\n");
    //         $conn->write("10000000000;1;0000000000;2;0000000000;3;0000000000;$store_id;1:-20:50:600\r\n");
    //     }
    //     if(preg_match('/sid=(?P<store_id>\d+):(?P<sensor_id>\d+)/', $packet, $matches_current)) {

    //         if(!array_key_exists($sensor_config[$matches_current['store_id'].$matches_current['sensor_id']], $sensor_config))
    //         {
    //             $store_id = $matches_current['store_id'];
    //             $sensor_id = $matches_current['sensor_id'];

    //             $conn->write("DATA-FROM-SERVER\r\n");
    //             // $conn->write("10721157297;1;0721157297;2;0721157297;3;0721157297;$store_id;$sensor_id:0:50:600\r\n");
    //             $conn->write("10000000000;1;0000000000;2;0000000000;3;0000000000;$store_id;$sensor_id:-20:50:600\r\n");

    //             $sensor_config[$store_id.$sensor_id] = 1;
    //         }
    //     }

    //     if(!preg_match('/sid=\d+:-1/', $packet))
    //     {
    //         $getCurrentDate = "[".date('d.m.Y H:i:s')."]";        
        
    //         $file = __DIR__ . '/output.txt';

    //         echo "$getCurrentDate Received sensor data: ".$packet."\n";
    //         echo "Saving it to output.txt...\n";

    //         $parsed_sensor_data = get_parsed_sensor_data($packet);
    //         for($i = 0; $i < count($parsed_sensor_data); $i++)
    //         {
    //             $location_id = null;
    //             $sensor_id = null;
    //             $current_temp = null;
    //             $min_temp = null;
    //             $max_temp = null;
    //             $sensor_vbat = null;
    //             $interval_min_temp = null;
    //             $interval_max_temp = null;
    //             $rec_count = null;
    //             $report_frequency = null;
    //             for($j = 0; $j < count($parsed_sensor_data[$i]); $j++)
    //             {
    //                 $location_id = $parsed_sensor_data[$i][0];
    //                 $sensor_id = $parsed_sensor_data[$i][1];
    //                 $current_temp = $parsed_sensor_data[$i][2];
    //                 $min_temp = $parsed_sensor_data[$i][3];
    //                 $max_temp = $parsed_sensor_data[$i][4];
    //                 $sensor_vbat = $parsed_sensor_data[$i][5];
    //                 $interval_min_temp = $parsed_sensor_data[$i][6];
    //                 $interval_max_temp = $parsed_sensor_data[$i][7];
    //                 $rec_count = $parsed_sensor_data[$i][8];
    //                 $report_frequency = $parsed_sensor_data[$i][9];
    //             }
    //             $stmt = $con->prepare("INSERT INTO temperature_sensor_data_test (SensorID, LocationID, current_temp, last_bat_level, min_temp, max_temp, interval_min, interval_max, rec_count, report_frequency, nothing2) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
    //             $stmt->bind_param("dddddddddd", $sensor_id, $location_id, $current_temp, $sensor_vbat, $min_temp, $max_temp, $interval_min_temp, $interval_max_temp, $rec_count, $report_frequency);
    //             $test = $stmt->execute();
    //             if($test) {
    //                 echo "Statement executed";
    //             } else {
    //                 echo "Something went wrong";
    //             }
    //         }
    //     }
    //     // $current = file_get_contents($file);
    //     // $current .= $data;
    //     // file_put_contents($file, $current);

        
    //     $output = date("d.m.Y H:i:s") . "\n";
    //     $output .= "\t{$packet}\n";
    //     $output .= "\n";
    //     $output .= "----------------------------------------------------------------";
    //     $output .= "\n\n";
    //     file_put_contents(__DIR__ . '/output.log' ,$output, FILE_APPEND);     
    // });
});

$loop->run();

?>