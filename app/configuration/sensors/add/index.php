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
    <title>tempmon.ro - demo - add sensor</title>
    
    <link rel="stylesheet" href="../../../dist/css/main.min.css" type="text/css"/>
    <!-- <link rel="stylesheet" href="css/style.css" type="text/css"/> -->
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">
</head>
<body>
<div class="main-content add-page">
  <nav class="nav-side">
    <ul>
      <!-- <a href="#">
        <li class="logo"><a href="/demo">tempmon</a></li>
      </a> -->
      <li class="home">
        <a href="http://tempmon.ro/demo/configuration">
            <i class="icon-home"></i>
        </a>
      </li>
      <li class="user">
          <div class="user-dropdown">
              <div class="dropdown-container">
                <div class="user-meta">
                    <img src="../../../img/user-image.png" alt="">
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
      </li>
    </ul>
  </nav>
  <main class="main-container">
    <a href="http://tempmon.ro/demo/configuration" class="previous-actions">
      <i class="icon-arrow-back"></i>
      Inapoi
    </a>
    <h1>Adauga senzor</h1>
  
    <form action="send.php" method="POST">
      <div class="form-container">
        <div class="form-group">
            <label for="sensor_id">Sensor ID:</label>
            <input type="text" placeholder="Enter Sensor ID..." name="sensor_id"/>
        </div>
        <div class="form-group">
            <label for="location_id">Location:</label>
            <select name="location_id">
            <?php 
               
                for ($x = 0; $x < count(getLocations()); $x++) {
                    
            ?>
                
                <? if($x == 0): ?>
                   <option value="<?= getLocations()[$x]['id'] ?>" selected> <?= getLocations()[$x]['name'] ?> </option>
                <? else:  ?>
                   <option value="<?= getLocations()[$x]['id'] ?>"> <?= getLocations()[$x]['name'] ?> </option>    
                <? endif; ?>
                  <?php
                } 
            ?>
            </select>
        </div>
        <div class="form-group">
            <label for="mintemp">Minimum temperature:</label>
            <input type="number" name="mintemp" placeholder="Enter minimum temperature..."/>
        </div>
        <div class="form-group">
            <label for="maxtemp">Maximum temperature:</label>
            <input type="number" name="maxtemp" placeholder="Enter maximum temperature..."/>
        </div>
        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="text" name="phone" placeholder="Enter contact phone number..."/>
        </div>
        <div class="form-group">
            <label for="report_freq">Report frequency:</label>
            <input type="text" name="report_freq" placeholder="Enter report frequency..."/>
        </div>
        <div class="form-group">
          <label for="">&nbsp;</label>
          <button class="btn primary" type="submit" name="submit">Adauga</button>
        </div>
      </div>
    </form>
    
  </main>
</div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script src="../js/moment.js"></script>
    <script src="../js/script.js"></script>
</body>
</html>