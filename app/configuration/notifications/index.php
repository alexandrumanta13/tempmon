
<?php

include "/home/circuitp/tempmon.ro/monitor/inc/queries.php";


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>tempmon.ro - demo configuration</title>
    
    <link rel="stylesheet" href="../../dist/css/main.min.css" type="text/css"/>
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">
</head>
<body>
<div class="main-content add-page notifications-page">
    <nav class="nav-side">
        <ul>
        <li class="home">
            <a href="http://tempmon.ro/demo/configuration">
                <i class="icon-home"></i>
            </a>
        </li>
        
        <!-- <li class="user">
          <div class="user-dropdown">
              <div class="dropdown-container">
                <div class="user-meta">
                    <img src="../../img/user-image.png" alt="">
                    <div class="user-details">
                        <span><?php  echo $_SESSION['login_email']; ?></span>
                        <small><?php echo $_SESSION['login_role']; ?></small>
                    </div>
                    <a href="http://tempmon.ro/demo/configuration/users/edit?id=<?php echo $_SESSION['user_id'] ?>" class="edit">
                        <i class="icon-edit"></i>
                    </a>
                </div>
                
              </div>
              <a href="http://tempmon.ro/demo/configuration/logout.php" class="btn primary">Logout</a>
          </div>
        <a>
            <i class="icon-user"></i>
        </a>
        </li> -->
        </ul>
    </nav>
    <main class="main-container">
        <a href="http://tempmon.ro/demo/configuration" class="previous-actions">
            <i class="icon-arrow-back"></i>
            Inapoi
        </a>
        <div class="title">
        <h1>Notificari</h1>
        <form action="send.php" method="POST">
            <div class="form-container">
                <div class="form-group">
                    <label for="email">Senzor:</label>
                    <select name="sensor_notification_change" class="sensor_notification_change">
                        <option value="">-- Selecteaza senzor</option>
                        <?php for($i = 0; $i < count(getAllSensors()); $i++) : ?>

                            <?php if (!isset($_GET['sensorid']) && getAllSensors()[$i]['SensorID'] == "3") : ?> 
                                <option value="<?= getAllSensors()[$i]['SensorID'] ?>" selected="selected"><?= "Senzor ".getAllSensors()[$i]['SensorID'] ?></option>
                            <?php elseif (isset($_GET['sensorid']) && getAllSensors()[$i]['SensorID'] == $_GET['sensorid']) : ?>
                                <option value="<?= getAllSensors()[$i]['SensorID'] ?>" selected="selected"><?= "Senzor ".getAllSensors()[$i]['SensorID'] ?></option>
                            <?php else : ?>
                                <option value="<?= getAllSensors()[$i]['SensorID'] ?>"><?= "Senzor ".getAllSensors()[$i]['SensorID'] ?></option>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
        </form>
        </div>
        
        <div class="notifications-container">
            <table>
                <thead>
                    <?php 
                        $th = array("Nume magazin", "Senzor ID", "Temperatura curenta", "Status", "Reportat la", "Nivel baterie", "Temperatura minima", "Temperatura maxima", "Interval minim", "Interval maxim");
                    ?>
                    <tr>
                        <?php
                            for($x = 0; $x <= count($th); $x++) {
                                ?>
                                    <th>
                                        <?= $th[$x]; ?>
                                    </th>
                                <?php
                            }
                        ?>
                       
                    </tr>
                </thead>
                <tbody>
                <?php if (!isset($_GET['sensorid'])) : ?>
                    <?php for($x = 0; $x < count(showLast50Logs(3)); $x++) : ?>
                        <tr>
                            <?php for($y = 0; $y < 11; $y++) : ?>
                                <td data-column="<?= $th[$y]; ?>"><?=showLast50Logs(3)[$x][$y]?></td>
                            <? endfor; ?>
                        </tr>
                    <?php endfor; ?>
                
                <?php else : ?>
                    <?php for($x = 0; $x < count(showLast50Logs((int)$_GET['sensorid'])); $x++) : ?>
                        <tr>
                            <?php for($y = 0; $y < 11; $y++) : ?>
                                <td data-column="<?= $th[$y]; ?>"><?=showLast50Logs((int)$_GET['sensorid'])[$x][$y]?></td>
                            <? endfor; ?>
                        </tr>
                    <?php endfor; ?>

                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>


<script>

    $('.sensor_notification_change').change((e) => {
        var url = `http://tempmon.ro/demo/configuration/notifications?sensorid=${e.target.value}`;
        $(location).attr('href', url);
    });

</script>