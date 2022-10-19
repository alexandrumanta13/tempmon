<?PHP



$server = 'localhost';
$username = 'circuitp_sensor';
$password = ']ROMRwo4)aa~';
$database = 'circuitp_monitor';


$conn = mysqli_connect($server, $username, $password, $database);
$mysqli = new mysqli($server, $username, $password, $database);
if ($mysqli->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


?>



