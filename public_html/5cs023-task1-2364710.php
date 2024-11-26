<?php
// Database connection details
$servername = 'localhost'; // Your server name (may need to change if not localhost)
$username = '2364710';      // Your MySQL username
$password = 'Qassy0566.';   // Your MySQL password
$dbname = 'db2364710';      // Your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_errno) {
    echo 'Failed to connect to MySQL: ' . $conn->connect_error;
    exit(); // Corrected: removed extra 'e'
}

// SQL query to get movies
$sql = "SELECT movie_name, genre, price, release_date FROM movies";
$result = $conn->query($sql);

// Display data in an HTML table
if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>Movie Name</th>
                <th>Genre</th>
                <th>Price</th>
                <th>Date of Release</th>
            </tr>";
    
    // Loop through each row and display it
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row["movie_name"]) . "</td>
                <td>" . htmlspecialchars($row["genre"]) . "</td>
                <td>£" . number_format($row["price"], 2) . "</td>
                <td>" . date("d/m/Y", strtotime($row["release_date"])) . "</td>
              </tr>";
    }
    
    echo "</table>";
} else {
    echo "No movies found";
}

// Close connection
$conn->close();
?>

