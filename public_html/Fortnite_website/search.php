<?php
session_start(); // Start the session to store user login status

require_once(__DIR__ . '/../vendor/autoload.php'); // Ensure the correct path to autoload

// Include the database connection file to get access to $conn
include 'db.php'; // Ensure this is the correct path to db.php

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates'); // Correct path to templates
$twig = new \Twig\Environment($loader);

// Get the search query from the URL (using GET)
$searchQuery = $_GET['query'] ?? '';

// Escape the query to prevent SQL injection
$searchQuery = $conn->real_escape_string($searchQuery);

// SQL query to search across multiple tables using UNION
$sql = "
    SELECT Gun_id AS id, Name, type AS Type, damage AS info, rarity, 'Guns' AS table_name FROM Guns WHERE Name LIKE '%$searchQuery%'
    UNION
    SELECT item_id AS id, Name, type AS Type, effect AS info, rarity, 'Items' AS table_name FROM Items WHERE Name LIKE '%$searchQuery%'
    UNION
    SELECT Skin_id AS id, Name, type AS Type, price AS info, rarity, 'Skins' AS table_name FROM Skins WHERE Name LIKE '%$searchQuery%'
    UNION
    SELECT vehicle_id AS id, Name, type AS Type, speed AS info, durability, 'vehicles' AS table_name FROM vehicles WHERE Name LIKE '%$searchQuery%'
";

// Execute the query
$result = $conn->query($sql);

// Prepare results for Twig
$searchResults = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $searchResults[] = $row;
    }
}

// Render the Twig template
echo $twig->render('search.html.twig', [
    'searchQuery' => $searchQuery,
    'searchResults' => $searchResults
]);
?>
