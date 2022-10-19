<?php

include "/home/circuitp/tempmon.ro/monitor/inc/queries.php";

include "/home/circuitp/tempmon.ro/monitor/inc/session_inc.php";


if(isset($_GET['id']))
{
    $data = findUserById((int)$_GET['id']);

    for($i = 0; $i < count($data); $i++)
    {
        $email = $data[$i]['email'];
        $role_id = $data[$i]['role_id'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>tempmon.ro - edit user</title>
    
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
    <h1>Editeaza datele utilizatorului</h1>
    <form action="send.php" method="POST">
      <div class="form-container">
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" placeholder="Enter email..." name="email" value="<?= $email ?>"/>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" placeholder="Enter password" name="password"/>
        </div>
        <div class="form-group">
            <label for="role_id">Role:</label>
            <select name="role_id">
            <?php 
              
                for ($x = 0; $x < count(getRoles()); $x++) {
                    
                    ?>
                  <? if(getRoles()[$x]['id'] == $role_id): ?>
                  <option value="<?= getRoles()[$x]['id'] ?>" selected> <?= getRoles()[$x]['type'] ?> </option>
                <? else: ?>
                  <option value="<?= getRoles()[$x]['id'] ?>"> <?= getRoles()[$x]['type'] ?> </option>
                <? endif; ?>
                  <?php
                } 
            ?>
            </select>
        </div>
        <div class="form-group">
            <label for="location_id">Location:</label>
            <select name="location_id">
            <?php 
              
                for ($x = 0; $x < count(getLocations()); $x++) {
                    
            ?>
                
                <? if($x == 0): ?>
                  <option value="<?= getLocations()[$x]['LocationID'] ?>" selected> <?= getLocations()[$x]['name'] ?> </option>
                <? else:  ?>
                  <option value="<?= getLocations()[$x]['LocationID'] ?>"> <?= getLocations()[$x]['name'] ?> </option>    
                <? endif; ?>
                  <?php
                } 
            ?>
            </select>
        </div>
        <div class="form-group">
          <label for="">&nbsp;</label>
          <input type="hidden" name="user_id" value="<?= $_GET['id'] ?>"/>
          <button class="btn primary" type="submit" name="submit">Editeaza</button>
        </div>
      </div>
    </form>
  </main>
</div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
</body>
</html>