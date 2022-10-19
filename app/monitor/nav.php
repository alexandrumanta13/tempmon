<?php

include "./inc/mysql_inc.php";

?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">

    <a class="navbar-brand" href="index.php">Sensors dashboard</a>

    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">

      <span class="navbar-toggler-icon"></span>

    </button>

    <div class="collapse navbar-collapse" id="navbarResponsive">

      <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">

          <a class="nav-link" href="index.php">

            <i class="fa fa-fw fa-dashboard"></i>

            <span class="nav-link-text">Dashboard</span>

          </a>

        </li>

       

       

        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Menu Levels">

          <a class="nav-link nav-link-collapse " data-toggle="collapse" href="#" data-parent="#exampleAccordion">

            <i class="fa fa-fw fa-sitemap"></i>

            <span class="nav-link-text">Sensors Listing</span>

          </a>

          <ul class="sidenav-second-level " id="collapseMulti">
          
          <?PHP

            $mysqli = new mysqli($server, $username, $password, $database);

            // Check for errors
            if ($mysqli->connect_errno) {
              printf("Connection failed: %s\n", $mysqli->connect_error);
              exit();
            }

            $query="SELECT DISTINCT LocationID as storeid FROM `temperature_sensor_data_test` order by LocationID";

            $q = mysqli_query($mysqli, $query);

            while($r=mysqli_fetch_array($q)){

            echo "<li>

              <a class=\"nav-link\" href=\"tables.php?sid=".$r['storeid']."\">Store ".$r['storeid']."</a>

            </li>";

            }

          ?>  

          </ul>

        </li>

      </ul>

      <ul class="navbar-nav sidenav-toggler">

        <li class="nav-item">

          <a class="nav-link text-center" id="sidenavToggler">

            <i class="fa fa-fw fa-angle-left"></i>

          </a>

        </li>

      </ul>

      <?PHP

      //include "alerts.php";

      ?>

    </div>

  </nav><?PHP

//   <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Tables">

//          <a class="nav-link" href="tables.php">

//            <i class="fa fa-fw fa-table"></i>

//            <span class="nav-link-text">Tables</span>

//          </a>

//        </li>

//  <li>

//              <a href="#">Second Level Item</a>

//            </li>

//            <li>

//              <a href="#">Second Level Item</a>

//            </li>

//            <li>

//              <a class="nav-link-collapse collapsed" data-toggle="collapse" href="#collapseMulti2">Third Level</a>

//              <ul class="sidenav-third-level collapse" id="collapseMulti2">

//                <li>

//                  <a href="#">Third Level Item</a>

//                </li>

//                <li>

//                  <a href="#">Third Level Item</a>

//                </li>

//                <li>

//                  <a href="#">Third Level Item</a>

//                </li>

//              </ul>

//            </li>

  ?>