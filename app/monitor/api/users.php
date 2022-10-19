<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include "/home/circuitp/tempmon.ro/monitor/inc/queries.php";
include "/home/circuitp/tempmon.ro/monitor/inc/mysql_inc.php";



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $location_id = $_POST['location_id'];

    if($location_id || $location_id == "0")
    {
        $query = " SELECT u.id, u.email, ur.type, ur.id as roleid FROM users u 
        INNER JOIN users_locations ul ON ul.user_id = u.id 
        INNER JOIN locations l ON l.id = ul.location_id 
        INNER JOIN user_roles ur ON ur.id = u.role_id
        WHERE l.id = '$location_id'
                    ";

        $result = mysqli_query($mysqli, $query);
        $count = mysqli_num_rows($result);


        if($count > 0){


            $users = array();
            $users["body"] = array();
            $users["count"] = $count;

            while ($row = mysqli_fetch_array($result))
            {

                array_push($users["body"], array('id' => $row['id'], 'email' => $row['email'], 'type' => $row['type']));
            }

            echo json_encode($users);
        }
        else {

            echo json_encode(
                array("body" => array(), "count" => 0)
            );
        }

    } else {
        echo json_encode(
            array("body" => array(), "count" => 0, "error" => "No location identified.")
        );
    }
} else {
    echo json_encode(
        array("error" => "Something went wrong.")
    );
}


?>