<?php
$filepath = realpath (dirname(__FILE__));
require_once($filepath."/db_connect.php");

$db = new DB_CONNECT();
$mysqli = $db->connect();

if ($mysqli->connect_error) {
    die("Database Connection failed: " . $mysqli->connect_error);
}

$sql = "SELECT id, temp, hum FROM weather ORDER BY id";
$result = $mysqli->query($sql);

$temperature = array();
$humidity = array();
$weather_id = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $weather_id[] = $row["id"];
        $temperature[] = $row["temp"];
        $humidity[] = $row["hum"];
    }
} else {
    echo "Temperature and Humidity data not available";
    $weather_id = "N/A";
    $temperature = "N/A";
    $humidity = "N/A";
}

$sql = "SELECT id, moist, min_thresh, max_thresh FROM soilMoisture ORDER BY id";
$result = $mysqli->query($sql);

$soil_moisture = array();
$soil_id = array();
$min_thresh = array();
$max_thresh = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $soil_id[] = $row["id"];
        $soil_moisture[] = $row["moist"];
        $min_thresh[] = $row["min_thresh"];
        $max_thresh[] = $row["max_thresh"];
    }
} else {
    echo "No data available";
    $soil_id = "N/A";
    $soil_moisture = "N/A";
    $min_thresh = "N/A";
    $max_thresh = "N/A";
}

?>

<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">

    <title>Automated Irrigation System</title>
    <!-- Add CSS and JavaScript here -->
    <style>
    /* General Styles */
    .weather, .soil-moisture {
        text-align: center;
        margin-top: 50px;
    }

    .weather h1, .soil-moisture h1,
    .weather p, .soil-moisture p {
        font-size: 2em; /* Adjusted for both h1 and p */
    }

    /* Table Styles */
    table {
        border-collapse: collapse;
        width: 70%;
        margin: auto; /* Combined margin-left and margin-right */
        text-align: center;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        font-size: medium;
    }

    th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: middle;
        background-color: #4CAF50;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #ddd;
    }

    /* Form Styles */
    form {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f9f9f9;
        width: 50%;
    }

    form input, form button {
        margin: 10px 0;
        padding: 10px;
        width: 80%;
    }

    /* Map Styles */
    #map {
        height: 200px;
        width: 50%;
        margin: auto; /* Combined margin-left and margin-right */
    }

    /* Grid Layout for Forms */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-gap: 50px;
    }

    .form-grid div {
        display: flex;
        align-items: center;
    }

    /* Utility Classes */
    .clearfix {
        clear: both;
    }

    .list-reset {
        list-style-type: none;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
    }

    /* Social Media Links */
    .social__list {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
    }

    .social__item {
        margin-right: 12px;
    }

    .social__item:last-child {
        margin-right: 0;
    }

    .social__link, .sourceCode {
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 20px;
        color: var(--heading-font-color);
    }
    .container {
    display: flex; /* This enables flexbox */
    justify-content: space-between; /* Items will be placed left-to-right */
    align-items: start; /* Items will be placed at the top of the container */
    }

    .box {
        flex: 1; /* Each box will take up equal space */
        padding: 20px; /* Add some padding for content */
        margin: 15px; /* Add some space between boxes */
        /* change the distance between the boxes */        
    }

    .box1 {
        margin-right: 0px;
    }

    .box2 {
        margin-left: 0px;
    }

    </style>

<script>
    window.onload = function() {
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('manual')) {
            window.scrollTo(0, 500); // Scrolls to 500px from the top of the page
    }   }
</script>

</head>
<body>

<h1 class="weather">Automated Irrigation System</h1>
<ul class="social__list list-reset">
    <h3>Created by Kamyar Karimi.</h3>

    <!-- <h3 style="margin-left: 10px;" class="sourceCode:">LinkedIn: </h3>
    <li style="margin-left: 10px;" class="social__item">
        <a class="social__link" href="https://www.linkedin.com/in/karimikamyar/" target="_blank" rel="noopener" aria-label="Social link"><i class="bi bi-linkedin"></i></a>
    </li> -->

    <a style="margin-left: 10px; text-decoration: underline;" class="social__link" href="https://github.com/kaamiiaar/Automated-Irrigation-Demo" target="_blank" rel="noopener" aria-label="Social link">
        <h3 class="sourceCode">Explanation & Source Code:</h3>
        <i style="margin-left: 10px;" class="bi bi-github"></i>
    </a>
                                    
</ul>


<h4 style="margin-top: 5px; margin-bottom: 60px; color: darkgray; text-align:center">A demo made for the IoT Engineering position at Aglantis and Cisco-La Trobe Centre for AI and IoT</h4>


<!-- The div element for the map -->
<div id="map"></div>
<script>
    function initMap() {
        var location1 = {lat: -19.427958, lng: 146.711635}; // -19.427958, 146.711635
        var location2 = {lat: -19.428548, lng: 146.714943}; // -19.428548, 146.714943
        var location3 = {lat: -19.428867, lng: 146.717704}; // -19.428867, 146.717704
        var location4 = {lat: -19.432071, lng: 146.715904}; // -19.432071, 146.715904

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            center: location2,
            mapTypeId: 'satellite', // Set map type to satellite
            styles: [
                { // Turn off labels
                    featureType: "all",
                    elementType: "labels",
                    stylers: [{ visibility: "off" }]
                }
            ]
        });

        var marker1 = new google.maps.Marker({position: location1, map: map, label: '1'});
        var marker2 = new google.maps.Marker({position: location2, map: map, label: '2'});
        var marker3 = new google.maps.Marker({position: location3, map: map, label: '3'});
        var marker4 = new google.maps.Marker({position: location4, map: map, label: '4'});
    }
</script>

<!-- Load the Google Maps JavaScript API -->
<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCPiPdRBYmxtnJmpR2v0CrLPl8P-LkOba4&callback=initMap">
</script>
<h5 style="text-align: center; margin-top:2px">Townsville, Queensland</h2>

<div class="container">
<div class="weather box box1">
    <h2>Latest Weather Data</h2>
    <table>
        <tr>
            <th>Sensor ID</th>
            <th>Temperature (°C)</th>
            <th>Humidity (%)</th>
        </tr>
        <?php
        for ($i = 0; $i < count($temperature); $i++) {
            echo "<tr>";
            echo "<td>" . $weather_id[$i] . "</td>";
            echo "<td>" . $temperature[$i] . "</td>";
            echo "<td>" . $humidity[$i] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>

<div class="soil-moisture box box2">
    <h2>Latest Soil Moisture Data</h2>
    <table>
        <tr>
            <th>Sensor ID</th>
            <th>Soil Moisture</th>
            <th>Min Threshold</th>
            <th>Max Threshold</th>
        </tr>
        <?php
        $manual = isset($_GET['manual']) && $_GET['manual'] == 'true';

        for ($i = 0; $i < count($soil_moisture); $i++) {
            echo "<tr>";
            echo "<td>" . $soil_id[$i] . "</td>";
            if ($soil_moisture[$i] < $min_thresh[$i]) {
                echo "<td style='color: red;'>" . $soil_moisture[$i] . "</td>";
                if (!$manual) {
                    $sql = "UPDATE switch SET status='on' WHERE id=$soil_id[$i]";
                    $mysqli->query($sql);
                }

            } else if ($soil_moisture[$i] > $max_thresh[$i]) {
                echo "<td style='color: red;'>" . $soil_moisture[$i] . "</td>";
                if (!$manual) {
                    $sql = "UPDATE switch SET status='off' WHERE id=$soil_id[$i]";
                    $mysqli->query($sql);
                }

            } else {
                echo "<td style='color: green;'>" . $soil_moisture[$i] . "</td>";
                if (!$manual) {
                    $sql = "UPDATE switch SET status='off' WHERE id=$soil_id[$i]";
                    $mysqli->query($sql);
                }

            }
            
            echo "<td>" . $min_thresh[$i] . "</td>";
            echo "<td>". $max_thresh[$i] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>
</div>

<?php
$sql = "SELECT id, status FROM switch ORDER BY id";
$result = $mysqli->query($sql);

$switch_id = array();
$status = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $switch_id[] = $row["id"];
        $status[] = $row["status"];
    }
} else {
    echo "No data available";
    $switch_id = "N/A";
    $status = "N/A";
}
?>

<!-- Display other sensor data here -->
<!-- Form to control the irrigation -->
<div class="container">
<div class="box box1">
    <h2 style="text-align: center;">Irrigation Control</h2>
    <table>
        <tr>
            <th>Switch ID</th>
            <th>Status (on/off)</th>
        </tr>
        <?php
        for ($i = 0; $i < count($status); $i++) {
            echo "<tr>";
            echo "<td>" . $switch_id[$i] . "</td>";
            echo "<td>" . $status[$i] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>

<div class="box box2">
<form action="control_irrigation.php" method="post">
    <h3>Manual Activation</h1>
    <h5 style="margin-top: 5px; text-align:center; color: darkgray;">Select the ones you want to activate/keep activated</h3>
    <div class="form-grid">
        <div>
            <input type="checkbox" id="switch1" name="switch1" value="1">
            <label for="switch1">Switch 1</label>
        </div>
        <div>
            <input type="checkbox" id="switch2" name="switch2" value="1">
            <label for="switch2">Switch 2</label>
        </div>
        <div>
            <input type="checkbox" id="switch3" name="switch3" value="1">
            <label for="switch3">Switch 3</label>
        </div>
        <div>
            <input type="checkbox" id="switch4" name="switch4" value="1">
            <label for="switch4">Switch 4</label>
        </div>
    </div>

<input type="submit" value="Activate Irrigation">
<?php
if ($manual) {
    echo '<a href="index.php?manual=false" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; margin: 4px 2px; cursor: pointer; border-radius: 4px;">Switch to Automatic</a>';
}
?>


</form>
</div>
</div>


</body>
</html>

