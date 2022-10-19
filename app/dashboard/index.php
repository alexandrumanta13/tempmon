<?php

include_once "/home/circuitp/tempmon.ro/monitor/inc/queries.php";
include "/home/circuitp/tempmon.ro/monitor/inc/session_inc.php";


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>tempmon.ro - dashboard</title>
    
    <link rel="stylesheet" href="../raport/dist/css/main.min.css" type="text/css"/>
    <link rel="stylesheet" href="../raport/dist/css/dashboard.css" type="text/css"/>
    <!-- <link rel="stylesheet" href="css/style.css" type="text/css"/> -->
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">
</head>

<body>
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
                                <img src="../raport/img/user-image.png" alt="">
                                <div class="user-details">
                                    <span><?php  echo $_SESSION['login_email']; ?></span>
                                    <small><?php echo $_SESSION['login_role']; ?></small>
                                </div>
                                <a href="http://tempmon.ro/configuration/users/edit?id=<?php echo $_SESSION['user_id'] ?>" class="edit">
                                    <i class="icon-edit"></i>
                                </a>
                            </div>
                            
                        </div>
                        <a href="http://tempmon.ro/logout.php" class="btn primary">Logout</a>
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
        <!--  <div class="store-container"> -->
         <!--    <ul>
                <li class="configuration">
                    <span>Lista senzori</span> -->
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

                <!-- </li>
                <? for($i = 0; $i < count(getLocations()); $i++) : ?>
                <li>
                    <p><?= getLocations()[$i]['name'] ?></p>
                    <small>
                        <? getNoOfSensorsByLocation(getLocations()[$i]['LocationID']); ?>
                    </small>
                    </small>
                </li>

            <? endfor; ?> -->
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
                <!--  </ul> -->

                <!--   </div> -->
                <div class="store-content">
                    <div class="store-header" style="position: relative;">
                        <h1>Spitalul Foisor</h1>
                        <ol class="alerts"></ol>
                        
                    </div>
                    <div class="change-phone">
                            <div class="form-group">
                            <label for="telephone">Numar de telefon: </label>
                                <form action="save.php" method="POST">
                                    <input type="text" name="telephone" placeholder="Introduceti numarul de telefon..." value="<?= !empty(getLocationPhone($_SESSION['location_id'])) ? getLocationPhone($_SESSION['location_id']) : ''  ?>" />
                                    <input type="hidden" name="location_id" value="<?= $_SESSION['location_id'] ?>"/>
                                    <button type="submit" name="submit" class="btn primary">Salveaza</button>
                                </form>
                            </div>
                        </div>
                   <!--  <ul class="sensor-header">
                        <li>Nume senzor</li>
                        <li>Temperatura curenta</li>
                        <li>Data ultimul incident</li>
                        <li>Actiuni</li>
                    </ul> -->

            <ul class="sensor-list">
               
            </ul>
                  
    </div>
</div>

<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>

<script>
    $('input[type=radio][name=first-switch]').change(function() {
        if (this.value == 'all') {
            var url = `http://tempmon.ro/dashboard.php?sensors=all`;
            $(location).attr('href', url);
        }
        else if (this.value == 'unassigned') {
            var url = `http://tempmon.ro/dashboard.php?sensors=unassigned`;
            $(location).attr('href', url);
        }
    });
    function getData() {
        var locid = $('input[type=hidden][name=location_id]').val();
        $.post('http://tempmon.ro/monitor/api/assigned_sensors.php', {location_id: locid}, function(response){

            $('.sensor-type').html('neasignati');
            console.log( response.body)

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
                battery_level = 100 - ((3.15 - response.body[i].battery_level) * 100);
                if(battery_level > 100) {
                    battery_level = 100;
                }else {
                    battery_level = Math.round(battery_level);
                }

                let oldDateObj = new Date(reported_at);
                let current_datetime = new Date();
                let date = new Date();
                current_datetime.setTime(oldDateObj.getTime() + ((2.5*report_frequency) * 60 * 1000));
                let getTimeReported = current_datetime.getTime();
                let getTimeNow = date.getTime();
               


                html = `
                <li>
                   
                    <div class="sensor-list-details">
                        <div class="sensor-details">
                            <p class="` + (getTimeReported < getTimeNow ? `offline` : `online` ) + `">${name} (ID${storeid}:${sensorid})</p>
                              
                        </div>
                       <div class="current-temperature">
                            <small>Temperatura curenta</small>
                            <i class="icon-temperature"></i>
                            ` + ((current_temp > interval_max || current_temp < interval_min) ? `<span class="offline">${current_temp}</span>` : `<span class="online">${current_temp}</span>` ) 
                            + `
                        </div>
                        <div class="intervals">
                            <div class="min">
                                <small>Interval min</small>
                                <span>${interval_min}</span>
                                |
                                <small>Interval max</small>
                                <span>${interval_max}</span>
                            </div>
                        </div>
                         <div class="battery-level">
                          
                            <span><i class="icon-battery"></i>${battery_level}%</span>
                        </div>
                        
                        <div class="last-incident">
                            <small>Data raport</small>
                            ` + (reported_at.length > 0 ? `<span>${reported_at}</span>` : `<span>N/A</span>`)
                            + `
                        </div>
                    </div>
                     <div class="raport">
                        <a class="btn primary" href="http://tempmon.ro/raport?sensorid=${sensorid}&storeid=${storeid}">Raport</a>
                    </div>
                </li>
                `
                
                $('.sensor-list').append(html);
            }
        });

        $.post('http://tempmon.ro/monitor/api/logs.php', {location_id: 2}, function(response){ 

            count = response.count;
            
               

            for(let i = 0; i < response.count && i < 6; i++)
            {
                html = '';
                sensor_name = response.body[i].name != undefined ? response.body[i].name : '';
                sensorid = response.body[i].SensorID;
                // storeid = response.body[i].storeid;
                storeid = 2

                location_name = response.body[i].LocationName;
                current_temp = response.body[i].current_temp;
                type = response.body[i].type;
                reported_at = response.body[i].reported_at;
                let oldDateObj = new Date(reported_at);
            // let current_datetime = new Date();
            // let date = new Date();
            // current_datetime.setTime(oldDateObj.getTime() + ((2.5*report_frequency) * 60 * 1000));
            // let getTimeReported = current_datetime.getTime();
            // let getTimeNow = date.getTime();

                if(type == "online" || type == "offline")
                {
                    html = `<li>
                        <p>
                            ${reported_at} - <span class="status ${type}">${sensor_name}(${sensorid}:${storeid})</span>
                        </p>
                    </li>`;
                }
                else if(type == "sub interval" || type == "peste interval") {
                    type = 'offline';
                    html = `<li>
                    <p>
                        ${reported_at} - <span class="status ${type}">${sensor_name}(${sensorid}:${storeid})</span>
                        Temperatura in afara intervalului<span class="status ${type}">(${current_temp})</span>
                    </p>
                    </li>`;
                }

                $('.alerts').append(html);
            }
        });
    }
    getData();
    setInterval(() => {
        $('.alerts').empty();
        $('.sensor-list').empty();
        getData();
    }, 30000);
   
</script>
</body>

</html>