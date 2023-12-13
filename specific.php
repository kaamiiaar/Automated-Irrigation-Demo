<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Creating Array for JSON response
$response = array();
 
// Include data base connect class
$filepath = realpath (dirname(__FILE__));
require_once($filepath."/db_connect.php");

 // Connecting to database
$db = new DB_CONNECT();
 
// Check if we got the field from the user
if (isset($_GET["id"])) {
    $id = $_GET['id'];
 
    // Fire SQL query to get weather data by id
    $mysqli = $db->connect();
    $result = $mysqli->query("SELECT * FROM weather WHERE id = $id");
	
	//If returned result is not emptyß
    if (!empty($result)) {

        // Check for succesfull execution of query and no results found
        if ($result->num_rows > 0) {
			
			// Storing the returned array in response
            $result = $result->fetch_assoc();
			
			// temperoary user array
            $weather = array();
            $weather["id"] = $result["id"];
            $weather["temp"] = $result["temp"];
			$weather["hum"] = $result["hum"];
          
            $response["success"] = 1;

            $response["weather"] = array();
			
			// Push all the items 
            array_push($response["weather"], $weather);
 
            // Show JSON response
            echo json_encode($response);
        } else {
            // If no data is found
            $response["success"] = 0;
            $response["message"] = "No data on weather found";
 
            // Show JSON response
            echo json_encode($response);
        }
    } else {
        // If no data is found
        $response["success"] = 0;
        $response["message"] = "No data on weather found";
 
        // Show JSON response
        echo json_encode($response);
    }
} else {
    // If required parameter is missing
    $response["success"] = 0;
    $response["message"] = "Parameter(s) are missing. Please check the request";
 
    // echoing JSON response
    echo json_encode($response);
}
?>