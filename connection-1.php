<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$serverName = "sql204.infinityfree.com";
$userName = "if0_39058563";
$password = "6JZp3iDNau";
$dbName = "if0_39058563_sijan";

// Connect to the database
$conn = mysqli_connect($serverName, $userName, $password, $dbName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database if it doesn't exist
$createDatabase = "CREATE DATABASE IF NOT EXISTS if0_39058563_sijan";
if (mysqli_query($conn, $createDatabase)) {
    mysqli_select_db($conn, $dbName);
} else {
    die("Failed to create database: " . mysqli_error($conn));
}

// Create table if it doesn't exist
$createTable = "CREATE TABLE IF NOT EXISTS weatherdata(
    city VARCHAR(255) NOT NULL,
    temperature FLOAT NOT NULL,
    humidity FLOAT NOT NULL,
    weatherDescription VARCHAR(255) NOT NULL,
    wind FLOAT NOT NULL,
    pressure FLOAT NOT NULL,
    timezone INT NOT NULL,
    lastUpdated INT NOT NULL,
    PRIMARY KEY (city)
)";
if (!mysqli_query($conn, $createTable)) {
    die("Failed to create table: " . mysqli_error($conn));
}

// Get city name from the request
$cityName = isset($_GET['q']) ? htmlspecialchars($_GET['q']) : 'Enterprise';

// Debugging: Log city name received
error_log("Received city: $cityName");  // Log to PHP error log

// Fetch data from the database
$selectAllData = "SELECT * FROM weatherdata WHERE city = '$cityName'";
$result = mysqli_query($conn, $selectAllData);
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $currentTime = time();
    $lastUpdated = $row['lastUpdated'];
    $timeDifference = $currentTime - $lastUpdated;

    if ($timeDifference > 7200) { // Update if data is older than 2 hours
        fetchAndUpdateWeatherData($cityName, $conn);
    }
} else {
    fetchAndUpdateWeatherData($cityName, $conn);
}

// Fetch weather data and update the database
function fetchAndUpdateWeatherData($cityName, $conn) {
    $apiKey = "cb559ad9fa9cbff03805c3230b74bf9b";
    $apiUrl = "https://api.openweathermap.org/data/2.5/weather?units=metric&q=$cityName&appid=$apiKey";
    $response = file_get_contents($apiUrl);
    $data = json_decode($response, true);

    if ($data) {
        $temperature = $data['main']['temp'];
        $humidity = $data['main']['humidity'];
        $weatherDescription = $data['weather'][0]['description'];
        $wind = $data['wind']['speed'];
        $pressure = $data['main']['pressure'];
        $timezone = $data['timezone'];
        $currentTime = time();

        $updateQuery = "REPLACE INTO weatherdata (city, temperature, humidity, weatherDescription, wind, pressure, timezone, lastUpdated)
                        VALUES ('$cityName', '$temperature', '$humidity', '$weatherDescription', '$wind', '$pressure', '$timezone', '$currentTime')";

        if (!mysqli_query($conn, $updateQuery)) {
            die("Failed to update data: " . mysqli_error($conn));
        }
    }
}

// Fetch the updated data from the database
$result = mysqli_query($conn, $selectAllData);
$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}

header('Content-Type: application/json');
echo json_encode($rows);

mysqli_close($conn);
?>
