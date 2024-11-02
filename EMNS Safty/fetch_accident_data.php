<?php
// Include the database connection file
include 'db_connect.php';

// Query to fetch the latest accident data
$sql = "SELECT * FROM emergency_response ORDER BY timestamp DESC LIMIT 5";
$result = $conn->query($sql);

// Check if there are results
if ($result->num_rows > 0) {
    echo "<div class='accident-data'>";
    echo "<h2>Recent Accident Reports</h2>";
    echo "<ul>";
    
    // Output each row of data
    while($row = $result->fetch_assoc()) {
        echo "<li>";
        echo "Name: " . $row["name"] . " | ";
        echo "Location: " . $row["location"] . " | ";
        echo "Incident: " . $row["incident_type"] . " | ";
        echo "Status: " . $row["status"] . " | ";
        echo "Time: " . $row["timestamp"];
        echo "</li>";
    }
    
    echo "</ul>";
    echo "</div>";
} else {
    echo "No accident data found.";
}

// Close the database connection
$conn->close();
?>
