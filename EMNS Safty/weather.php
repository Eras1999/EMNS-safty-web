<?php
if (isset($_GET['from']) && isset($_GET['to']) && isset($_GET['apiKey'])) {
    $from = $_GET['from'];
    $to = $_GET['to'];
    $apiKey = $_GET['apiKey'];

    // Example: Get weather for the starting and ending cities
    $cities = [$from, $to];
    $weatherData = [];

    foreach ($cities as $city) {
        $weatherUrl = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}&units=metric"; // For Celsius
        $weatherResponse = file_get_contents($weatherUrl);
        $weatherData[] = json_decode($weatherResponse, true);
    }

    echo json_encode($weatherData);
} else {
    echo json_encode(['error' => 'Invalid parameters.']);
}
?>
