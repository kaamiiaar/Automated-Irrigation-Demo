<?php

// Include data base connect class
$filepath = realpath (dirname(__FILE__));
require_once($filepath."/db_connect.php");

# create a new connection
$db = new DB_CONNECT();
$mysqli = $db->connect();

// Check connection
if ($mysqli->connect_error) {
    die("Database Connection failed: " . $mysqli->connect_error);
}

// Function to update switch state
function updateSwitchState($mysqli, $switchId, $state) {
    $sql = "UPDATE switch SET status = ? WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $state, $switchId);
    $stmt->execute();
    $stmt->close();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming you have four switches
    $switch1 = isset($_POST['switch1']) ? 'on' : 'off';
    $switch2 = isset($_POST['switch2']) ? 'on' : 'off';
    $switch3 = isset($_POST['switch3']) ? 'on' : 'off';
    $switch4 = isset($_POST['switch4']) ? 'on' : 'off';

    // Update the switch states in the database
    updateSwitchState($mysqli, 1, $switch1);
    updateSwitchState($mysqli, 2, $switch2);
    updateSwitchState($mysqli, 3, $switch3);
    updateSwitchState($mysqli, 4, $switch4);

    // TODO: Add code to communicate with ESP8266-12F to control the irrigation system

    // Redirect back to the dashboard after updating
    header("Location: index.php?manual=true");

    exit();
}

$mysqli->close();
?>
