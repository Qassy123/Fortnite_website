<?php
include 'db.php';

$searchQuery = $_GET['query'] ?? '';

if (!empty($searchQuery)) {
    $searchQuery = $conn->real_escape_string($searchQuery);

    // Example query (adjust as needed for your database)
    $sql = "SELECT Name FROM Guns WHERE Name LIKE '%$searchQuery%' 
            UNION 
            SELECT Name FROM Items WHERE Name LIKE '%$searchQuery%'
            UNION 
            SELECT Name FROM Skins WHERE Name LIKE '%$searchQuery%'
            UNION 
            SELECT Name FROM vehicles WHERE Name LIKE '%$searchQuery%'";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p>" . htmlspecialchars($row['Name']) . "</p>";
        }
    } else {
        echo "<p>No suggestions found.</p>";
    }
}
