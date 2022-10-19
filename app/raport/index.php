<?php
include_once "/home/circuitp/tempmon.ro/monitor/inc/queries.php";
include "/home/circuitp/tempmon.ro/monitor/inc/session_inc.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>tempmon.ro - raport senzor</title>

  <link rel="stylesheet" href="dist/css/main.min.css" type="text/css"/>
  <!-- <link rel="stylesheet" href="css/style.css" type="text/css"/> -->
  <link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">
</head>
<body>
  <div class="main-content">
    <nav class="nav-side">
      <ul>
      <!-- <a href="#">
        <li class="logo"><a href="/demo">tempmon</a></li>
      </a> -->
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
      <li class="notifications">

        <?php
          $notification = showNotifications((isset($_GET['sensorid']) ? $_GET['sensorid'] : 0 ), (isset($_GET['storeid']) ? $_GET['storeid'] : 0));
        ?>
        <a>
          <span class="alerts"><?= count($notification); ?></span>
          <i class="icon-notifications"></i>
        </a>
        <div class="notifications-container">
          <ul>
            <? for($i = 0; $i < count($notification); $i++) : ?>

              <? if($notification[$i]['type'] == "sub interval" || $notification[$i]['type'] == "peste interval") : ?>
                <li><span class="status warning"></span><p>Senzorul <?=$notification[$i]['sensor_id']?> din magazinul <?=$notification[$i]['name']?>, a inregistrat o valoare <?=$notification[$i]['type'] ?></p></li>
              <? endif; ?>

            <? endfor; ?>
            <? if(showGatewayStatusByFrequency((isset($_GET['sensorid']) ? $_GET['sensorid'] : 3), (isset($_GET['storeid']) ? $_GET['storeid'] : 0)) == 'gateway-online') : ?>
              <li><span class="status success"></span><p>Senzorul <?= isset($_GET['sensorid']) ? $_GET['sensorid'] : 3 ?> din magazinul <?=getLocationNameByLocID((isset($_GET['storeid']) ? $_GET['storeid'] : 0))?> este online</p></li>
            <? else : ?>
              <li><span class="status warning"></span><p>Senzorul <?= isset($_GET['sensorid']) ? $_GET['sensorid'] : 3 ?> din magazinul <?=getLocationNameByLocID((isset($_GET['storeid']) ? $_GET['storeid'] : 0))?> este offline</p></li>
            <? endif; ?>


          </ul>
          <a class="btn primary" href="http://tempmon.ro/configuration/notifications?sensorid=<?= (isset($_GET['sensorid']) ? $_GET['sensorid'] : 0) ?>">Vezi toate inregistrarile</a>
        </div>
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
  <section class="gateway-meta">


    <div class="gateway-inner collapsed">
      <div class="gateway-inner-collapse">
        <i class="icon-settings"></i>
      </div>
      <div class="gateway-meta__status <?= showGatewayStatusByFrequency((isset($_GET['sensorid']) ? $_GET['sensorid'] : 3), (isset($_GET['storeid']) ? $_GET['storeid'] : 0)); ?>">
        <div class='gateway-meta__status-circle'></div>
      </div>
      <div class="gateway-meta__name">
        <? if(isset($_GET['sensorid']) && isset($_GET['storeid'])) : ?>
          <?= getSensorName($_GET['storeid'], $_GET['sensorid']) ?>
        <? else : ?>
          Gateway 1
        <? endif; ?>
        <small>
         <? if(showGatewayStatusByFrequency((isset($_GET['sensorid']) ? $_GET['sensorid'] : 3), (isset($_GET['storeid']) ? $_GET['storeid'] : 0)) == 'gateway-online') : ?>
          Online
        <? else : ?>
          Offline
        <? endif; ?>
      </small>
    </div>
    <div class="gateway-meta__battery">
      <i class="icon-battery"></i>
      <span class="battery_level"><?php showLatestBatteryLevel((isset($_GET['sensorid']) ? (int)$_GET['sensorid'] : 3), (isset($_GET['storeid']) ? (int)$_GET['storeid'] : 0)) ?></span>
    </div>
    <div class="gateway-meta__temperature">
      <i class="icon-temperature"></i>
      <span class="current_temperature_span"><?php showCurrentTemperature((isset($_GET['sensorid']) ? (int)$_GET['sensorid'] : 3), (isset($_GET['storeid']) ? (int)$_GET['storeid'] : 0)) ?></span>
    </div>
    <p class="current_temp_label">Temperatura curenta</p>
    <div class="gateway-meta__info">
    <? if($_SESSION['login_role_id'] !== "3") : ?>
      <form action="saveConfiguration.php" method="POST" id="configurationForm">
        <div class="form-group">
          <label for="mintemp">Temperatura minima</label>
          <input type="text" id="tempMin" name="tempMin" value="<?php echo getConfiguration('tempMin', (isset($_GET['sensorid']) ? (int)$_GET['sensorid'] : 3), (isset($_GET['storeid']) ? (int)$_GET['storeid'] : 0)) ?>"/>
        </div>
        <div class="form-group">
          <label for="mintemp">Temperatura maxima</label>
          <input type="text" id="tempMax" name="tempMax" value="<?php echo getConfiguration('tempMax', (isset($_GET['sensorid']) ? (int)$_GET['sensorid'] : 3), (isset($_GET['storeid']) ? (int)$_GET['storeid'] : 0)) ?>"/>
        </div>
        <div class="form-group">
            <input type="hidden" name="_storeID" value="<?= isset($_GET['storeid']) ? (int)$_GET['storeid'] : 0 ?>"/>
          <input type="hidden" name="_sensorID" value="<?= isset($_GET['sensorid']) ? (int)$_GET['sensorid'] : 3 ?>"/>

          <? if(isset($_GET['sensorid']) && isset($_GET['storeid'])) : ?>
            <input type="hidden" name="_reportFrequency" value="<?= getCurrentReportFrequency((int)$_GET['sensorid'], (int)$_GET['storeid']) ?>"/>
          <? else : ?>
            <input type="hidden" name="_reportFrequency" value="10"/>
          <? endif; ?>
          <button type="submit" class="primary" name="submit" placeholder="Save">Salveaza</button>
        </div>
      </form>
    <? else : ?>
        <div class="form-group">
          <label for="mintemp">Temperatura minima</label>
          <input type="text" id="tempMin" name="tempMin" value="<?php echo getConfiguration('tempMin', (isset($_GET['sensorid']) ? (int)$_GET['sensorid'] : 3), (isset($_GET['storeid']) ? (int)$_GET['storeid'] : 0)) ?>" disabled/>
        </div>
        <div class="form-group">
          <label for="mintemp">Temperatura maxima</label>
          <input type="text" id="tempMax" name="tempMax" value="<?php echo getConfiguration('tempMax', (isset($_GET['sensorid']) ? (int)$_GET['sensorid'] : 3), (isset($_GET['storeid']) ? (int)$_GET['storeid'] : 0)) ?>" disabled/>
        </div>
    <? endif; ?>
    </div>
    <a class="info" href="http://tempmon.ro/dashboard/" style="width: 100%;color: #4b4c4c;">Inapoi</a>
  </div>
  
</section>
<main class="main-section">
 
    <!-- <div class="page-actions">
      <div class="form-group">
        <label for="">Magazine</label>
        <select name="" id="">
          <option value=""  selected="selected">-- Selecteaza magazin --</option>
          <option value="">Magazin 1</option>
          <option value="">Magazin 2</option>
          <option value="">Magazin 3</option>
          <option value="">Magazin 4</option>
        </select>
      </div>
      <div class="form-group">
        <label for="">Senzori</label>
        <select name="" id="">
          <option value="" selected="selected">-- Selecteaza senzor --</option>
          <option value="">Senzor 1</option>
          <option value="">Senzor 2</option>
          <option value="">Senzor 3</option>
          <option value="">Senzor 4</option>
        </select>
      </div>
    </div> -->
    <div class="graph graph-today">
      <h3 class="graph-title">
        Interval astazi, <?php echo date('d.m.Y'); ?>
      </h3>
      <div class="card">
        <canvas id="dayCanvas"></canvas>
        <!-- Custom Axis -->
        <div class="axis day-axis">
         <!-- <div class="tick">
              <span class="day-number"></span>
              <span class="day-name"></span>
              <span class="value day-value--this"></span>
            </div> -->
          </div>
        </div>
      </div>
      <span id="sensorid" style="display: none"><?=(isset($_GET['sensorid']) ? $_GET['sensorid'] : 3)?></span>
      <span id="storeid" style="display: none"><?=(isset($_GET['storeid']) ? $_GET['storeid'] : 0)?></span>
      <div class="graph-statistics">
        <div class="graph graph-last-week">
          <h3 class="graph-title">Interval ultima saptamana</h3>
          <!-- <div class="week-start" style="display: none"><?=date('Y-m-d', strtotime('-6 days'))?></div> -->
          <div class="card card-week">
            <canvas id="weekCanvas"></canvas>
            <div class="axis week-axis">
              <!-- <div class="tick">
                <span class="week-number"></span>
                <span class="week-name"></span>
                <span class="value week-value--this"></span>
              </div> -->
            </div>
          </div>
        </div>
        <div class="graph graph-last-month">
          <h3 class="graph-title">Interval ultima luna</h3>

          <div class="card">
            <canvas id="monthCanvas"></canvas>
            <div class="axis month-axis">
              <!-- <div class="tick">
                  <span class="month-number"></span>
                  <span class="month-name"></span>
                  <span class="value month-value--this"></span>
                </div> -->
              </div>
            </div>
          </div>
        </div>
      </main>
      <? if(isset($_GET['offline'])) : ?>
        <div class="alert alert-info on">
          <span class="close">&times;</span>
          Gateway is currently offline.
        </div>
      <? endif; ?>
      <? if(isset($_GET['success'])) : ?>
        <div class="alert alert-info on">
          <span class="close">&times;</span>
          Configuration for the device has been saved.
        </div>

      <? endif; ?>
      <script>
        const alertRemove = document.querySelector('.alert');
        if(alertRemove) {
          window.setTimeout(function() {
            alertRemove.classList.remove('on');
          }, 5000);
        }
      </script>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src="js/moment.js"></script>
    <script src="js/script.js"></script>
  </body>
  </html>