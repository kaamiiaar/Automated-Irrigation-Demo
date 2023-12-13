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
 
 // Fire SQL query to get all data from weather
$mysqli = $db->connect();
$result = $mysqli->query("SELECT *FROM weather") or die("".mysqli_error($mysqli));
 
// Check for succesfull execution of query and no results found
if ($result->num_rows > 0) {
    
	// Storing the returned array in response
    $response["weather"] = array();
 
	// While loop to store all the returned response in variable
    while ($row = $result->fetch_assoc()) {     # fetch_assoc() - for each row it will return an associative array
        // temperoary user array
        $weather = array(); 
        $weather["id"] = $row["id"];
        $weather["temp"] = $row["temp"];
		$weather["hum"] = $row["hum"];

		// Push all the items 
        array_push($response["weather"], $weather);
    }
    // On success
    $response["success"] = 1;
 
    // Show JSON response
    echo json_encode($response);
}	
else 
{
    // If no data is found
	$response["success"] = 0;
    $response["message"] = "No data on weather found";
 
    // Show JSON response
    echo json_encode($response);
}
?>