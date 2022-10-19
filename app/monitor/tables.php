<?PHP
include "./inc/session_inc.php";
include "head.php";


include "variables_inc.php";
include $include_path."mysql_inc.php";


$myData=new myDataClass();
$query="select * from temperature_sensor_data_test where LocationID like '".$_GET['sid']."%' LIMIT 25";
//echo $query;
$q=mysqli_query($mysqli, $query);
$sensors_log="";
while($r=mysqli_fetch_array($q)){
  $sensors_log.="<tr><td align=\"left\">[".date("d.m.Y H:i:s",$r['reported_at'])."]&nbsp;&nbsp;&nbsp;Sensor(".$r['SensorID'].")&nbsp;&nbsp;&nbsp;temp. ".$r['current_temp'].",&nbsp;&nbsp;&nbsp;bat. ".$r['last_bat_level']."V</td></tr>";
}
$myData->sensors_log=($sensors_log!=""?"<table>".$sensors_log."</table>":"");


$query="select * from temperature_sensor_data_test where LocationID='".$_GET['sid']."' order by reported_at desc";
$q=mysqli_query($mysqli, $query);
$sensors_list="";
//$sensors_list_header="<tr style=\"background-color:#eeeeee;\">"
//        . "<td style=\"border:1px solid black\">Last rec.</td>"
//        . "<td style=\"border:1px solid black\">Sensor</td>"
//        . "<td style=\"border:1px solid black\">Min. Temp.</td>"
//        . "<td style=\"border:1px solid black\">Max. Temp.</td>"
//        . "<td style=\"border:1px solid black\">SMS number</td>"
//        . "<td style=\"border:1px solid black\">Sec. Until Alert</td>"
//        . "<td style=\"background-color:#ddd;\">&nbsp;</td>"
//        . "</tr>";
$row = mysqli_fetch_array($q, MYSQLI_ASSOC);
while($r=mysqli_fetch_array($q, MYSQLI_ASSOC)){
    $query="select * from temperature_sensor_data_test where LocationID='".$row['SensorID']."' order by data desc LIMIT 1,1";
    //echo $query."<br>";
    // $qq=mysqli_query($mysqli, $query);
    // $qq2 = mysqli_fetch_array($qq, MYSQLI_ASSOC);
    // if($rr=mysqli_fetch_array($qq)){
    //     $last_temp=$qq2['current_temp'];
    //     $last_vbat=$qq2['last_bat_level'];
    //     $last_reccount=$qq2['rec_count'];
    //     }
  $sensors_list.="<tr >"
          . "<td >".$r['reported_at']."</td>"
          . "<td >".$r['current_temp']."</td>"
          . "<td >".$r['last_bat_level']."</td>"
          . "<td >Sensor ".$r['SensorID']." </td>"
          . "<td >".$r['min_temp']."</td>"
          . "<td >".$r['max_temp']."</td>"
          
          . "<td><button  >Edit </button></td>"
          . "</tr>";
}
// $myData->sensors_list=($sensors_list!=""?"<table width=100% style=\"\">".$sensors_list_header.$sensors_list."</table>":"");


?>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
  <?PHP
  include "nav.php";
  ?>
  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="index.php">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Shop details</li>
      </ol>
      <!-- Example DataTables Card-->
      <div class="card mb-3">
        <div class="card-header">
          <i class="fa fa-table"></i> Sensor List</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered" id="dataTable1" width="100%" cellspacing="0"><!--  data-page-length='25' -->
              <thead>
                <tr>
                  <th>Last rec.</th>
                  <th>Last temp.</th>
                  <th>Last Bat.Level</th>
                  <th>Sensor</th>
                  <th>Min. Temp.</th>
                  <th>Max. Temp.</th>
                  <th>&nbsp;</th>
                  
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th>Last rec.</th>
                  <th>Last temp.</th>
                  <th>Last Bat.Level</th>
                  <th>Sensor</th>
                  <th>Min. Temp.</th>
                  <th>Max. Temp.</th>
                  <th>&nbsp;</th>
                  
                </tr>
              </tfoot>
              <tbody>
                  <?PHP
                  echo $sensors_list;
                  ?>
                
                
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer small text-muted">Updated yesterday at <?PHP echo date("H:i ");?></div>
      </div>
    </div>
    <!-- /.container-fluid-->
    <!-- /.content-wrapper-->
    <footer class="sticky-footer">
      <div class="container">
        <div class="text-center">
          <small>Copyright © AH Circuit Design SRL 2018</small>
        </div>
      </div>
    </footer>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fa fa-angle-up"></i>
    </a>
    <!-- Logout Modal-->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="login.php">Logout</a>
          </div>
        </div>
      </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Page level plugin JavaScript-->
    <script src="vendor/datatables/jquery.dataTables.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin.min.js"></script>
    <!-- Custom scripts for this page-->
    <script src="js/sb-admin-datatables.min.js"></script>
    <script>
    //alert(Date("23.01.2018 00:22:03"))
    </script>
  </div>
</body>

</html>
<?PHP
class myDataClass {
    public $device_id = "5";
    public $device_status  = "off";
    public $model_name = "";
    public $filter_sn = "string";
    public $filter_life_percentage=56;
    public $gallons_filtered=1200;
    public $days_to_change=14;
    public $manual_power_down=TRUE;
    public $power_down_time="18:00";
    public $power_up_time="08:00";
    public $power_status="on";
    public $leak="No leak detected";
    public $time="12:32";
    public $date="8/9/2016";
    public $errors_log="";
    public $sensors_log="";
    public $sensors_list="";
}
class myError{
    public $error_code = "1";
    public $error_msg = "No id selected";
    
}

?>