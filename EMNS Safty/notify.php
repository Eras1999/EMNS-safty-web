<?php
$servername = "localhost";
$username = "root"; // Default XAMPP MySQL username
$password = ""; // Default XAMPP MySQL password
$dbname = "emergency_response"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect data from the form
    $name = htmlspecialchars($_POST['name']);
    $location = htmlspecialchars($_POST['location']);
    $accidentType = htmlspecialchars($_POST['accidentType']);
    $details = htmlspecialchars($_POST['details']);

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO reports (name, location, accidentType, details) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $location, $accidentType, $details);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Emergency notification sent successfully.";
        // Redirect to the reports page after successful submission
        header("Location: ers.html");
        exit();
    } else {
        echo "Failed to send notification: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
