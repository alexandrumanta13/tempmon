<?php

require 'vendor/autoload.php';

use React\Socket\ConnectionInterface;


class ConnectionsPool 
{
    /** @var SplObjectStorage  */
    private $connections;

    public function __construct()
    {
        $this->connections = new SplObjectStorage();
        $this->poolCount = 0;
    }

    public function add(ConnectionInterface $connection)
    {
        $this->initEvents($connection);
        $this->connections->attach($connection);
        $this->poolCount++;
        echo "New connection established! $this->poolCount \n";
    }

    private function initEvents(ConnectionInterface $connection)
    {
        $sensor_config = [];

        $connection->on('data', function ($packet) use ($connection, $sensor_config) {

            $parsedPacket = explode("|", str_replace(' ', '', substr($packet, strpos($packet, 'sid'), strlen($packet))));

            $con = mysqli_connect("localhost", "circuitp_sensor", ']ROMRwo4)aa~', "circuitp_monitor");
            if(!$con)
                die("Failed to connect to database!");
            echo "Connection with database established!\n";

            if(preg_match('/^sid=(?P<store_id>\d+):-1$/', $parsedPacket[0], $matches_default))
            {
                $store_id = $matches_default['store_id'];
                $connection->write("DATA-FROM-SERVER\r\n");
                // $conn->write("10721157297;1;0721157297;2;0721157297;3;0721157297;$store_id;1:0:50:600\r\n");
                $connection->write("10000000000;1;0000000000;2;0000000000;3;0000000000;$store_id;1:-20:50:1\r\n");
            }   
            if(!preg_match('/^sid=(?P<store_id>\d+):-1$/', $parsedPacket[0]) && !preg_match('/^sid=(?P<store_id>\d+):(?P<sensor_id>\d+)$/', $parsedPacket[0]) && !preg_match('/DATA-FROM-INTERFACE/', $parsedPacket[0]))
            // if(preg_match('/^sid=\d*(?:\.\d+)?:\d*(?:\.\d+)?:\d*(?:\.\d+)?:\d*(?:\.\d+)?:\d*(?:\.\d+)?:\d*(?:\.\d+)?:\d*(?:\.\d+)?:\d*(?:\.\d+)?:\d*(?:\.\d+)?:\d*(?:\.\d+)?:1[|]$/', $parsedPacket[0]))
            {
                $getCurrentDate = "[".date('d.m.Y H:i:s')."]";        
            
                $file = __DIR__ . '/output.txt';
                
                echo "$getCurrentDate Received sensor data: ".$packet."\n";
                echo "Saving it to output.txt...\n";

                $parsed_sensor_data = $this->getParsedSensorData($packet);
                for($i = 0; $i < count($parsed_sensor_data); $i++)
                {
                    $location_id = null;
                    $sensor_id = null;
                    $current_temp = null;
                    $min_temp = null;
                    $max_temp = null;
                    $sensor_vbat = null;
                    $interval_min_temp = null;
                    $interval_max_temp = null;
                    $rec_count = null;
                    $report_frequency = null;
                    for($j = 0; $j < count($parsed_sensor_data[$i]); $j++)
                    {
                        $location_id = $parsed_sensor_data[$i][0];
                        $sensor_id = $parsed_sensor_data[$i][1];
                        $current_temp = $parsed_sensor_data[$i][2];
                        $min_temp = $parsed_sensor_data[$i][3];
                        $max_temp = $parsed_sensor_data[$i][4];
                        $sensor_vbat = $parsed_sensor_data[$i][5];
                        $interval_min_temp = $parsed_sensor_data[$i][6];
                        $interval_max_temp = $parsed_sensor_data[$i][7];
                        $rec_count = $parsed_sensor_data[$i][8];
                        $report_frequency = $parsed_sensor_data[$i][9];
                    }
                    $stmt = $con->prepare("INSERT INTO temperature_sensor_data_test (SensorID, LocationID, current_temp, last_bat_level, min_temp, max_temp, interval_min, interval_max, rec_count, report_frequency, nothing2) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)");
                    $stmt->bind_param("dddddddddd", $sensor_id, $location_id, $current_temp, $sensor_vbat, $min_temp, $max_temp, $interval_min_temp, $interval_max_temp, $rec_count, $report_frequency);
                    

                    $type = '';
                    if((int)$current_temp < (int)$interval_min_temp)
                    {
                        $type = 'sub interval';
                    } elseif((int)$current_temp > (int)$interval_max_temp)
                    {
                        $type = 'peste interval';
                    }
                    else {
                        $type = 'online';
                    }

                    if($type == 'sub interval' || $type == 'peste interval') 
                    {
                        $stmt2 = $con->prepare("INSERT INTO temperature_logs (`type`, `sensor_id`, `location_id`) VALUES(?, ?, ?)");
                        $stmt2->bind_param("sdd", $type, $sensor_id, $location_id);
                        $test2 = $stmt2->execute();
                    }
                    // $stmt2 = $con->prepare("INSERT INTO temperature_logs (`type`, `sensor_id`, `location_id`) VALUES(?, ?, ?)");
                    // $stmt2->bind_param("sdd", $type, $sensor_id, $location_id);

                    $test = $stmt->execute();
                    // $test2 = $stmt2->execute();


                    $stmt4 = $con->prepare("SELECT * FROM temperature_sensors WHERE storeid = ? AND sensorid = ? ORDER BY created_at DESC LIMIT 1");
                    $stmt4->bind_param("dd", $location_id, $sensor_id);
                    $stmt4->execute();
                    $stmt4->store_result();

                    if($stmt4->num_rows == 0)
                    {
                        $phone_initial = "12345";
                        echo "new sensor added\n";
                        $stmt5 = $con->prepare("INSERT INTO `temperature_sensors` (sensorid, storeid, maxtemp, mintemp, phone, report_freq) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt5->bind_param("ddddsd", $sensor_id, $location_id, $interval_max_temp, $interval_min_temp, $phone_initial, $report_frequency);
                        $stmt5->execute();
                    }


                   
                    $stmt3 = $con->prepare("SELECT id, mintemp, maxtemp, phone, report_freq FROM temperature_sensors WHERE storeid = ? AND sensorid = ? ORDER BY created_at DESC LIMIT 1");
                    $stmt3->bind_param("dd", $location_id, $sensor_id);
                    $test3 = $stmt3->execute();
                    $stmt3->bind_result($id, $mintemplogs, $maxtemplogs, $phonelogs, $reportfreqlogs);
                    $flag = 0;
                    while($stmt3->fetch())
                    {
                        if((int)$interval_min_temp !== (int)$mintemplogs || (int)$interval_max_temp !== (int)$maxtemplogs || (int)$report_frequency !== (int)$reportfreqlogs)
                        {
                            echo "Sent DATA-FROM-SERVER\r\n1$phonelogs;1;0;2;0;3;0;$location_id;$sensor_id:$mintemplogs:$maxtemplogs:$reportfreqlogs\r\n";
                            // $flag = 1;
                            $connection->write("DATA-FROM-SERVER\r\n");
                            $connection->write("1$phonelogs;1;0;2;0;3;0;$location_id;$sensor_id:$mintemplogs:$maxtemplogs:$reportfreqlogs\r\n");
                        }

                    }
                    // if($flag == 1)
                    // {
                    //     $stmt6 = $con->prepare("UPDATE `temperature_sensors` SET mintemp = ?, maxtemp = ?, report_freq = ? WHERE id = ?");
                    //     $stmt6->bind_param("dddd", $interval_min_temp, $interval_max_temp, $report_frequency, $id);
                    //     $stmt6->execute();
                    // } 
                    
                    if($test) {
                        echo "Statement executed (1) \n";
                    } else if($test2) {
                        echo "Statement executed (2) \n";
                    } else if($test3) {
                        echo "Statement executed (3) \n";
                    
                    } else {
                        echo "Something went wrong. \n";
                    }
                }
            }
            if(preg_match('/^sid=(?P<store_id>\d+):(?P<sensor_id>\d+)$/', $parsedPacket[0], $matches_current)) {

                if(!array_key_exists($sensor_config[$matches_current['store_id'].$matches_current['sensor_id']], $sensor_config))
                {
                    $store_id = $matches_current['store_id'];
                    $sensor_id = $matches_current['sensor_id'];

                    $connection->write("DATA-FROM-SERVER\r\n");
                    // $conn->write("10721157297;1;0721157297;2;0721157297;3;0721157297;$store_id;$sensor_id:0:50:600\r\n");
                    $connection->write("10000000000;1;0000000000;2;0000000000;3;0000000000;$store_id;$sensor_id:-20:50:600\r\n");

                    $sensor_config[$store_id.$sensor_id] = 1;
                }
            }

            if(preg_match('/DATA-FROM-INTERFACE/', $parsedPacket[0])) {

                echo "Sent ".str_replace('INTERFACE', 'SERVER', $parsedPacket[0])." configuration to the device.\n";
                $this->sendAll(str_replace('INTERFACE', 'SERVER', $parsedPacket[0]));
                $this->connections->detach($connection);
                $connection->close();

            }

        });

        $connection->on('close', function() use ($connection){
            $this->connections->detach($connection);
            $this->poolCount--;
        });
    }

    private function sendAll($data) {
        foreach ($this->connections as $conn) {
            $conn->write($data);
        }
    }

    private function getParsedSensorData($packet) {
        $sensor_data = explode("|", str_replace(' ', '', substr($packet, strpos($packet, 'sid'), strlen($packet))));
        $parsed_sensor_data = array();
        for($i = 0; $i < count($sensor_data); $i++) 
        {
            if(count($sensor_data) > 1) 
            {
                if(strlen($sensor_data[$i]) !== 0) 
                {
                    $parsed_sensor_data[$i] = array();
                    $chunk = explode(":", $sensor_data[$i]);
                    $storeId = preg_replace('/[^0-9]/', '', $chunk[0]);
                    array_push($parsed_sensor_data[$i], $storeId);
                    for($j = 1; $j < count($chunk); $j++)
                    {
                        if(count($chunk) > 1)
                        {
                            array_push($parsed_sensor_data[$i], $chunk[$j]);
                        }
                    }
                }
            }
        }
            return $parsed_sensor_data;
        }
}


?>