<?php
include 'db.php';

$searchQuery = $_GET['query'] ?? '';

if (!empty($searchQuery)) {
    $searchQuery = $conn->real_escape_string($searchQuery);

    // Query to fetch matching results across all relevant tables
    $sql = "SELECT Name FROM Guns WHERE Name LIKE '%$searchQuery%' 
            UNION 
            SELECT Name FROM Items WHERE Name LIKE '%$searchQuery%'
            UNION 
            SELECT Name FROM Skins WHERE Name LIKE '%$searchQuery%'
            UNION 
            SELECT Name FROM vehicles WHERE Name LIKE '%$searchQuery%'
            LIMIT 5";  // Limit the number of results for performance

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Output each result as a clickable suggestion
        while ($row = $result->fetch_assoc()) {
            echo "<div class='suggestion-item' onclick=\"selectSuggestion('" . htmlspecialchars($row['Name']) . "')\">" . htmlspecialchars($row['Name']) . "</div>";
        }
    } else {
        echo "<p>No suggestions found.</p>";
    }
} else {
    echo "<p>Start typing to see suggestions.</p>"; // Handle cases where the search query is empty
}
?>
