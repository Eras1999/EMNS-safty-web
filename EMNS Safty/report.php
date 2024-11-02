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

// Fetch reports from the database
$sql = "SELECT * FROM reports ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ers.css">
    <title>Submitted Emergency Reports</title>
</head>
<body>
    


    <div class="container">
        <h1>Submitted Emergency Reports</h1>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Type of Accident</th>
                    <th>Details</th>
                    <th>Date Submitted</th>
                </tr>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['location']; ?></td>
                        <td><?php echo $row['accidentType']; ?></td>
                        <td><?php echo $row['details']; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No reports submitted yet.</p>
        <?php endif; ?>
        <div class="learn-more text-center">
            <button onclick="window.location.href='index.html'" class="btn btn-secondary" id="learn-more-btn">Go to Home Page</button>
        </div>
    </div>
   <br>
   <br>
   
</body>
</html>

<?php
$conn->close();
?>
