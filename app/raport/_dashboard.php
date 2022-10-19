<?php

include_once "/home/circuitp/tempmon.ro/monitor/inc/queries.php";
include "/home/circuitp/tempmon.ro/monitor/inc/session_inc.php";


?>


<!DOCTYPE html>
<html lang="en" style="height: 100%">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>tempmon.ro - demo page</title>
    
    <link rel="stylesheet" href="dist/css/main.min.css" type="text/css"/>
    <!-- <link rel="stylesheet" href="css/style.css" type="text/css"/> -->
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">
</head>

<body style="height: 100%; overflow: auto;">
    <div class="main-content">
        <nav class="nav-side">
            <ul>

                <li class="home">
                    <a>
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="user">
                    <div class="user-dropdown">
                        <div class="dropdown-container">
                            <div class="user-meta">
                                <img src="./img/user-image.png" alt="">
                                <div class="user-details">
                                    <span><?php  echo $_SESSION['login_email']; ?></span>
                                    <small><?php echo $_SESSION['login_role']; ?></small>
                                </div>
                                <a href="http://tempmon.ro/demo/configuration/users/edit?id=<?php echo $_SESSION['user_id'] ?>" class="edit">
                                    <i class="icon-edit"></i>
                                </a>
                            </div>
                            
                        </div>
                        <a href="logout.php" class="btn primary">Logout</a>
                    </div>
                    <a>
                        <i class="icon-user"></i>
                    </a>
                </li>
                <li class="">
                    <a>
                        <i class="icon-battery"></i>
                    </a>
                </li>
                <li class="">
                    <a>
                        <i class="icon-temperature"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="store-container">
            <ul>
                <li class="configuration">
                    <span>Lista senzori</span>
                    <!-- <div class="switch switch--horizontal">
                        <? if(isset($_GET['sensors']) && $_GET['sensors'] == 'unassigned') : ?>
                            <input id="radio-a" type="radio" name="first-switch" value="all">
                            <label for="radio-a">Toti</label>
                            <input id="radio-b" type="radio" name="first-switch" checked="checked" value="unassigned">
                            <label for="radio-b">Neasignati</label>
                            <span class="toggle-outside">
                                <span class="toggle-inside"></span>
                            </span>
                        <? else : ?>
                            <input id="radio-a" type="radio" name="first-switch" checked="checked" value="all">
                            <label for="radio-a">Toti</label>
                            <input id="radio-b" type="radio" name="first-switch" value="unassigned">
                            <label for="radio-b">Neasignati</label>
                            <span class="toggle-outside">
                                <span class="toggle-inside"></span>
                            </span>
                        <? endif; ?>
                    </div>

                    <div class="form-group">
                        <input type="text" placeholder="Cauta magazin">
                    </div> -->

                </li>
                <? for($i = 0; $i < count(getLocations()); $i++) : ?>
                <li>
                    <p><?= getLocations()[$i]['name'] ?></p>
                    <small>
                        <? getNoOfSensorsByLocation(getLocations()[$i]['LocationID']); ?>
                    </small>
                    </small>
                </li>

                <? endfor; ?>
                <!-- <li>
                    <p>Magazin 1</p>
                    <small>24 senzori</small>
                </li>
                <li>
                    <p>Magazin 1</p>
                    <small>24 senzori</small>
                </li>
                <li>
                    <p>Magazin 1</p>
                    <small>24 senzori</small>
                </li> -->
            </ul>

        </div>
        <div class="store-content">
            <h1>Spitalul Foisor</h1>
            <ul class="sensor-header">
                <li>Nume senzor</li>
                <li>temperatura curenta</li>
                <li>Data ultimul incident</li>
                <li>Actiuni</li>
            </ul>
            <?php print_r(getAssignedSensors(1)) ?>
            <ul class="sensor-list">
                <? if(!empty(getAssignedSensors(1))) : ?>
                <? for($i = 0; $i < count(getAssignedSensors(1)); $i++) : ?>
                <li>
                        <div class="raport">
                            <!-- <a class="btn primary" href="http://tempmon.ro/demo/configuration/notifications?sensorid=<?= getAssignedSensors(1)[$i]['sensorid'] ?>">Raport</a> -->
                            <a class="btn primary" href="http://tempmon.ro/demo?sensorid=<?= getAssignedSensors(1)[$i]['sensorid'] ?>&locationid=<?= getAssignedSensors(1)[$i]['storeid'] ?>">Raport</a>
                        </div>
                        <div class="sensor-list-details">
                            <div class="sensor-details">
                                <p><?=getAssignedSensors(1)[$i]['name']?> (ID<?=getAssignedSensors(1)[$i]['sensorid']?>:<?=getAssignedSensors(1)[$i]['storeid']?>)</p>
                                <? if(showGatewayStatus(1, 1, "MINUTE", getAssignedSensors(1)[$i]['sensorid'], getAssignedSensors(1)[$i]['storeid']) == 'gateway-online') : ?>
                                    <small class="online">Online</small>
                                <? else : ?>
                                    <small class="offline">Offline</small>
                                <? endif; ?>
                            </div>

                            <? for($j = 0; $j < count(getLastIncident((int)getAssignedSensors(1)[$i]['sensorid'], (int)getAssignedSensors(1)[$i]['storeid'])); $j++) : ?>
                                <?= getLastIncident((int)getAssignedSensors(1)[$i]['sensorid'], (int)getAssignedSensors(1)[$i]['storeid'])[0]['reported_at']; ?>
                            <? endfor; ?>
                           <!--  <div class="list-actions">
                                <a href="http://tempmon.ro/demo/configuration/sensors/edit?id=<?= getAssignedSensors(1)[$i]['id'] ?>" class="edit">
                                    <i class="icon-edit"></i>
                                    <small>Editeaza</small>
                                </a>
                                <a href="http://tempmon.ro/demo/configuration/sensors/remove?id=<?= getAssignedSensors(1)[$i]['id'] ?>" class="delete">
                                    <i class="icon-delete"></i>
                                    <small>Sterge</small>
                                </a>
                                <a href="http://tempmon.ro/demo/configuration/sensors/assign?id=<?= getAssignedSensors(1)[$i]['id'] ?>" class="assign">
                                    <i class="icon-assign"></i>
                                    <small>Asigneaza</small>
                                </a>
                            </div> -->
                        </div>
                    </li>
                    <? endfor; ?>
                <? endif; ?>
            </ul>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>

    <script>
        $('input[type=radio][name=first-switch]').change(function() {
            if (this.value == 'all') {
                var url = `http://tempmon.ro/demo/dashboard.php?sensors=all`;
                $(location).attr('href', url);
            }
            else if (this.value == 'unassigned') {
                var url = `http://tempmon.ro/demo/dashboard.php?sensors=unassigned`;
                $(location).attr('href', url);
            }
        });

        $.post('http://tempmon.ro/monitor/api/unassigned_sensors.php', {location_id: 0}, function(response){
                
            $('.sensor-type').html('neasignati');

            for(let i = 0; i < response.body.length; i++)
            {
                    id = response.body[i].id;
                    name = response.body[i].name;
                    sensorid = response.body[i].SensorID;
                    storeid = response.body[i].storeid;
                    current_temp = response.body[i].current_temp;
                    maxtemp = response.body[i].maxtemp;
                    mintemp = response.body[i].mintemp;
                    interval_min = response.body[i].interval_min;
                    interval_max = response.body[i].interval_max;
                    report_frequency = response.body[i].report_frequency;
                    reported_at = response.body[i].reported_at;
                
                    html = `
                            <tr>
                                <td>${storeid}:${sensorid}</td>
                                <td>${name}</td>
                                <td>${reported_at}</td>
                                <td>${current_temp}</td>
                                <td>${mintemp}</td>
                                <td>${maxtemp}</td>
                                <td>${report_frequency}</td>
                                <td>
                                <a href="http://tempmon.ro/demo/configuration/sensors/edit?id=${id}" class="edit">
                                    <i class="icon-edit"></i>
                                </a>
                                <a href="http://tempmon.ro/demo/configuration/sensors/remove?id=${id}" class="delete">
                                    <i class="icon-delete"></i>
                                </a>
                                <a href="http://tempmon.ro/demo/configuration/sensors/assign?id=${id}" class="assign">
                                    <i class="icon-assign"></i>
                                </a></td>
                            </tr>`;
                    $('.table-unassigned-sensors').append(html);
                }
            });
    </script>
</body>

</html>