<?php

include "/home/circuitp/tempmon.ro/monitor/inc/queries.php";
include "/home/circuitp/tempmon.ro/monitor/inc/session_inc.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>tempmon.ro - configuration</title>
    
    <link rel="stylesheet" href="../raport/dist/css/main.min.css" type="text/css"/>
    <!-- <link rel="stylesheet" href="css/style.css" type="text/css"/> -->
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/fontawesome.min.css"/>
    
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css"> -->
</head>
<body class="configuration" style="height: 100%; overflow: auto;">
    <div class="main-content">
      <nav class="nav-side">
        <ul>
      <!-- <a href="#">
        <li class="logo"><a href="/demo">tempmon</a></li>
    </a> -->
    <li class="home">
        <a href="http://tempmon.ro/configuration">
            <i class="icon-home"></i>
        </a>
    </li>
    <?php

    include "/home/circuitp/tempmon.ro/configuration/components/_sidebar.php";

    ?>
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
</ul>
</nav>
<main class="main-container configuration-page">
    <div class="main-grid" style="flex-wrap: wrap;">
        <div class="user" style="height: 40rem; flex: 1 0 33%">
            <div class="table-header">
                <div class="table-header-1">
                    Utilizatori
                </div>
                <a class="btn primary" href="users/add">Adauga</a>
            </div>
            <div class="card">
                <div class="card-inner">
                    <div class="form-group">
                        <input class="darken" type="text" placeholder="Introdu email-ul utilizatorului..."/>
                    </div>
                    <h3 class="msg-select-info">Te rog selecteaza locatia dorita.</h3>
                    <ul class="users-table">

                    </ul>
                </div>
            </div>
        </div>
        <div class="locations" style="height: 40rem; flex: 1 0 33%">
            <div class="table-header">
                <div class="table-header-1">
                    Locatii
                </div>
                <a class="btn primary" href="locations/add">Adauga</a>
            </div>
            <div class="card">
                <div class="card-inner">
                    <div class="form-group">
                        <input class="darken" type="text" placeholder="Introdu numele magazinului..."/>
                    </div>
                    <!-- <div class="list-options">
                        <a href="">
                            <small>Selecteaza toate</small>
                        </a> 
                        <small>/</small>
                        <a href="">
                            <small>Deselecteaza toate</small>
                        </a>
                    </div> -->
                    <ul>
                        <li>
                            <a onclick="deselectAll()">Delesecteaza</a>
                        </li>
                        <?php 
                        for ($x = 0; $x < count(getLocations()); $x++) {

                            ?>
                            <li>
                                <div class="form-group">
                                    <input type="radio" class="store-input" name="magazin_checkbox" id="magazin_checkbox_<?= getLocations()[$x]['id'] ?>" value="<?= getLocations()[$x]['id']?>" />
                                    <label for="magazin_checkbox_<?= getLocations()[$x]['id'] ?>"></label>
                                    <div class="list-meta">
                                        <span><?= getLocations()[$x]['name'] ?></span>
                                        <small><?= getLocations()[$x]['address'] ?></small>
                                    </div>
                                    <div class="settings-button" onclick="slideSettingsToggle(this)">
                                        <i class="icon-settings"></i>
                                    </div>
                                    <div class="list-actions">
                                        <a href="http://tempmon.ro/configuration/locations/edit?id=<?= getLocations()[$x]['id'] ?>" class="edit">
                                            <i class="icon-edit"></i>
                                            <small>Editeaza</small>
                                        </a>
                                        <a href="http://tempmon.ro/configuration/locations/remove?id=<?= getLocations()[$x]['id'] ?>" class="delete">
                                            <i class="icon-delete"></i>
                                            <small>Sterge</small>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <?php
                        } 
                        ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="gateway" style="min-height: 20rem; flex: 1 1 auto; margin-top: 30px;">
            <div class="table-header" style="margin-bottom: 4.4rem;">
                <div class="table-header-1">
                    Senzori <span class="sensor-type"></span>
                </div>
                <!-- <a class="btn primary" href="sensors/add">Adauga</a> -->
            </div>
            <div class="card">
                <div class="card-inner">
                    <table style="display: table;
                    margin-left: auto;
                    margin-right: auto;
                    border-collapse: separate;
                    border-spacing: 15px;
                    width: 100%">
                    <thead>
                        <tr>
                            <td>ID</td>
                            <td>Nume</td>
                            <td>Data raport</td>
                            <td>Temperatura raportata</td>
                            <td>Interval minim</td>
                            <td>Interval maxim</td>
                            <td>Frecventa de raportare</td>
                        </tr>

                    </thead>
                    <tbody class="table-unassigned-sensors">

                    </tbody>

                </table>
                    <!-- <div class="form-group">
                        <input class="darken" type="text" placeholder="Introdu numele senzorului..."/>
                    </div> -->
                    <!-- <h3 class="msg-select-info">Te rog selecteaza locatia dorita.</h3> -->

                    <ul class="sensor-list">
                        <!-- <li>
                            <a href="">
                                <div class="list-meta">                                  
                                    <span>Gateway</span>
                                    <small class="offline">Offline</small>                             
                                </div>
                            </a>
                            <div class="sensor-details">
                                <div class="sensor temperature">
                                    <i class="icon-temperature"></i> 24&deg; C
                                </div>
                                <div class="sensor battery">
                                    <i class="icon-battery"></i> 100%
                                </div>
                            </div>
                            <div class="settings-button" onclick="slideSettingsToggle(this)">
                                <i class="icon-settings"></i>
                            </div>
                            <div class="list-actions">
                                <a href="" class="edit">
                                    <i class="icon-edit"></i>
                                    <small>Editeaza</small>
                                </a>
                                <a href="" class="delete">
                                    <i class="icon-delete"></i>
                                    <small>Sterge</small>
                                </a>
                                <a href="" class="assign">
                                    <i class="icon-assign"></i>
                                    <small>Asigneaza</small>
                                </a>
                            </div>
                        </li>
                        <li>
                            <a href="">
                                <div class="list-meta">
                                    <span>Gateway</span>
                                    <small class="online">Online</small>
                                </div>
                            </a>
                            <div class="sensor-details">
                                <div class="sensor temperature">
                                    <i class="icon-temperature"></i> 24&deg; C
                                </div>
                                <div class="sensor battery">
                                    <i class="icon-battery"></i> 100%
                                </div>
                            </div>
                            <div class="list-actions">
                                <a href="" class="edit">
                                    <i class="icon-edit"></i>
                                </a>
                                <a href="" class="delete">
                                    <i class="icon-delete"></i>
                                </a>
                                <a href="" class="assign">
                                    <i class="icon-assign"></i>
                                </a>
                            </div>
                        </li> -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>

<script>
    function getSensorsByStore(val) {

        $('.table-unassigned-sensors').empty();
        $('.users-table').empty();
        $('.notifications-container ul').empty();
        $('.sensor-type').empty();
        $('.msg-select-info').hide();

        val = parseInt(val);

        $.post('http://tempmon.ro/monitor/api/users.php', {location_id: val}, function(response){ 

            for(let i = 0; i < response.body.length; i++)
            {
                console.log(response.body[i]);
                id = response.body[i].id;
                email = response.body[i].email;
                role = response.body[i].type;
                html = `<li>
                <a href="">
                <img src="../raport/img/user-image.png" alt="">
                </a>
                <a href="">
                <div class="list-meta">
                <span>${email}</span>
                <small>${role}</small>
                </div>
                </a>
                <div class="settings-button" onclick="slideSettingsToggle(this)">
                <i class="icon-settings"></i>
                </div>
                <div class="list-actions">
                <a href="http://tempmon.ro/configuration/users/edit?id=${id}" class="edit">
                <i class="icon-edit"></i>
                <small>Editeaza</small>
                </a>
                <a href="http://tempmon.ro/configuration/users/remove?id=${id}" class="delete">
                <i class="icon-delete"></i>
                <small>Sterge</small>
                </a>
                </div>
                </li>`;
                $('.users-table').append(html);
            }
        });

        $.post('http://tempmon.ro/monitor/api/assigned_sensors.php', {location_id: val}, function(response){

            $('.sensor-type').html('activi');

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
                <a href="http://tempmon.ro/configuration/sensors/edit?id=${id}&location_id=${val}" class="edit">
                <i class="icon-edit"></i>
                </a>
                <a href="http://tempmon.ro/configuration/sensors/remove?id=${id}&location_id=${val}" class="delete">
                <i class="icon-delete"></i>
                </a>
                <a href="http://tempmon.ro/configuration/sensors/assign?id=${id}&location_id=${val}" class="assign">
                <i class="icon-assign"></i>
                </a></td>
                </tr>`;
                $('.table-unassigned-sensors').append(html);
            }
        });
        $.post('http://tempmon.ro/monitor/api/logs.php', {location_id: val}, function(response){ 

            count = response.count;

            for(let i = 0; i < response.body.length; i++)
            {
                html = '';
                sensorid = response.body[i].SensorID;
                location_name = response.body[i].LocationName;
                current_temp = response.body[i].current_temp;
                type = response.body[i].type;
                reported_at = response.body[i].reported_at;

                if(type == "online" || type == "offline")
                {
                    html = `<li><span class="status warning"></span><p>Senzorul ${sensorid}, din magazinul ${location_name}, este ${type}</p></li>`;
                }
                else if(type == "sub interval" || type == "peste interval") {
                    html = `<li><span class="status warning"></span><p>Senzorul ${sensorid}, din magazinul ${location_name}, a inregistrat o valoare ${type}</p></li>`;
                }
                $('.notifications-container ul').append(html);
            }
            $('.alerts').html(count);
            
        });
    }

    function getUnassigned() {
        $('.table-unassigned-sensors').empty();
        $.post('http://tempmon.ro/monitor/api/unassigned_sensors.php', {location_id: 0}, function(response){
                console.log(response.body)
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
                <a href="http://tempmon.ro/configuration/sensors/edit?id=${id}" class="edit">
                <i class="icon-edit"></i>
                </a>
                <a href="http://tempmon.ro/configuration/sensors/remove?id=${id}" class="delete">
                <i class="icon-delete"></i>
                </a>
                <a href="http://tempmon.ro/configuration/sensors/assign?id=${id}" class="assign">
                <i class="icon-assign"></i>
                </a></td>
                </tr>`;
                $('.table-unassigned-sensors').append(html);
            }
        });
    }

    function deselectAll() {
        let inputs = document.querySelectorAll('.store-input');
        for (var i = 0; i < inputs.length; i++) {
         inputs[i].checked = false;
        }
        $('.users-table').empty();
        $('.msg-select-info').show();
     getUnassigned();
 }

 $(document).ready(function(){



    let checkbox = '<?=$_GET['location_id']?>';

    if(checkbox > 0) {
        checkbox = parseInt(checkbox);
        let inputs = document.querySelectorAll('.store-input');
        for (var i = 0; i < inputs.length; i++) {
            if(inputs[i].value == checkbox) {
             inputs[i].checked = true;
             getSensorsByStore(checkbox);
         }

     }
 } else {
   getUnassigned()
}


$('input[type=radio]').click(function(){


    let val = parseInt(this.value);

    console.log(val);
    getSensorsByStore(val);


            // $.post('http://tempmon.ro/monitor/api/sensors.php', {location_id: val}, function(response){ 

            // for(let i = 0; i < response.body.length; i++)
            // {
            //         id = response.body[i].id;
            //         sensorid = response.body[i].SensorID;
            //         status = response.body[i].status;
            //         current_temp = response.body[i].current_temp;
            //         battery = response.body[i].battery;
            //         bat_level = Number.parseFloat(100 - ((3.15 - battery) * 100)).toFixed(0); 


            //         html = `<li>
            //                 <a href="">
            //                     <div class="list-meta">                                  
            //                         <span>Sensor ${sensorid}</span>
            //                         <small class="offline">${status}</small>                             
            //                     </div>
            //                 </a>
            //                 <div class="sensor-details">
            //                     <div class="sensor temperature">
            //                         <i class="icon-temperature"></i> ${current_temp}&deg; C
            //                     </div>
            //                     <div class="sensor battery">
            //                         <i class="icon-battery"></i> ${bat_level}%
            //                     </div>
            //                 </div>
            //                 <div class="settings-button" onclick="slideSettingsToggle(this)">
            //                     <i class="icon-settings"></i>
            //                 </div>
            //                 <div class="list-actions">
            //                     <a href="http://tempmon.ro/demo/configuration/sensors/edit?id=${id}" class="edit">
            //                         <i class="icon-edit"></i>
            //                         <small>Editeaza</small>
            //                     </a>
            //                     <a href="http://tempmon.ro/demo/configuration/sensors/remove?id=${id}" class="delete">
            //                         <i class="icon-delete"></i>
            //                         <small>Sterge</small>
            //                     </a>
            //                 </div>
            //             </li>`;
            //         $('.sensor-list').append(html);
            //     }

        });
});
</script>
</body>
</html>