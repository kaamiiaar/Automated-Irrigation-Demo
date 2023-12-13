<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Creating Array for JSON response
$response = array();
 
// Check if we got the field from the user
if (isset($_GET['temp']) && isset($_GET['hum']) && isset($_GET['moist'])) {
 
    $temp = $_GET['temp'];
    $hum = $_GET['hum'];
    $moist = $_GET['moist'];
 
    // Include data base connect class
    $filepath = realpath (dirname(__FILE__));
	require_once($filepath."/db_connect.php");

    // Connecting to database 
    $db = new DB_CONNECT();
 
    // Fire SQL query to insert data in weather
    $mysqli = $db->connect();
    $result1 = $mysqli->query("INSERT INTO weather(temp,hum) VALUES('$temp','$hum')");
    $result2 = $mysqli->query("INSERT INTO soilMoisture(moist) VALUES('$moist')");
 
    // Check for succesfull execution of query
    if ($result1 && $result2) {
        // successfully inserted 
        $response["success"] = 1;
        $response["message"] = "Weather and moisture successfully inserted.";
 
        // Show JSON response
        echo json_encode($response);
    } else {
        // Failed to insert data in database
        $response["success"] = 0;
        $response["message"] = "Something has been wrong";
 
        // Show JSON response
        echo json_encode($response);
    }
} else {
    // If required parameter is missing
    $response["success"] = 0;
    $response["message"] = "Parameter(s) are missing. Please check the request";
 
    // Show JSON response
    echo json_encode($response);
}
?>