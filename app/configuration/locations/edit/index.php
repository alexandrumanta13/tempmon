<?php

include "/home/circuitp/tempmon.ro/monitor/inc/queries.php";
include "/home/circuitp/tempmon.ro/monitor/inc/session_inc.php";

?>


<?php


if(isset($_GET['id']))
{
    $data = getLocationByID((int)$_GET['id']);

    for($i = 0; $i < count($data); $i++)
    {
        $name = $data[$i]['name'];
        $address = $data[$i]['address'];
        $location_id = $data[$i]['LocationID'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>tempmon.ro - edit location</title>
    
    <link rel="stylesheet" href="../../../raport/dist/css/main.min.css" type="text/css"/>
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
        <a href="http://tempmon.ro/configuration">
            <i class="icon-home"></i>
        </a>
      </li>
      <li class="user">
          <div class="user-dropdown">
              <div class="dropdown-container">
                <div class="user-meta">
                    <img src="../../../raport/img/user-image.png" alt="">
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
  <main class="main-container">
    <a href="http://tempmon.ro/configuration" class="previous-actions">
      <i class="icon-arrow-back"></i>
      Inapoi
    </a>
    <h1>Editeaza datele locatiei</h1>
    <form action="send.php" method="POST">
      <div class="form-container">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="name" placeholder="Enter name..." name="name" value="<?= $name ?>"/>
        </div>
        <div class="form-group">
            <label for="address">Address:</label>
            <input type="address" placeholder="Enter address" name="address" value="<?= $address ?>"/>
        </div>
        <div class="form-group">
            <label for="locationid">Location ID:</label>
            <input type="text" placeholder="Enter location ID (device settings)" name="location_id" value="<?= $location_id ?>"/>
        </div>
          <div class="form-group">
            <label for="">&nbsp;</label>
            <button class="btn primary" type="submit" name="submit">Salveaza</button>
          </div>
          <input type="hidden" name="user_id" value="<?= $_GET['id'] ?>"/>
        </div>
    </form>
    
  </main>
</div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
</body>
</html>