<?php
session_start(); // Start the session to store user login status

require_once(__DIR__ . '/../vendor/autoload.php'); // Ensure the correct path to autoload

// Include the database connection file to get access to $conn
include 'db.php'; // Ensure this is the correct path to db.php

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates'); // Correct path to templates
$twig = new \Twig\Environment($loader);

// Get search query and filters from the URL (using GET)
$searchQuery = $_GET['query'] ?? '';
$type = $_GET['type'] ?? '';
$min_damage = $_GET['min_damage'] ?? null;
$max_damage = $_GET['max_damage'] ?? null;
$rarity = $_GET['rarity'] ?? '';

// Escape the query and inputs to prevent SQL injection
$searchQuery = $conn->real_escape_string($searchQuery);
$type = $conn->real_escape_string($type);
$rarity = $conn->real_escape_string($rarity);
$min_damage = is_numeric($min_damage) ? intval($min_damage) : null;
$max_damage = is_numeric($max_damage) ? intval($max_damage) : null;

// Base SQL queries for each table
$guns_query = "SELECT Gun_id AS id, Name, type AS Type, damage AS info, rarity, 'Guns' AS table_name FROM Guns WHERE Name LIKE '%$searchQuery%'";
$items_query = "SELECT item_id AS id, Name, type AS Type, effect AS info, rarity, 'Items' AS table_name FROM Items WHERE Name LIKE '%$searchQuery%'";
$skins_query = "SELECT Skin_id AS id, Name, type AS Type, price AS info, rarity, 'Skins' AS table_name FROM Skins WHERE Name LIKE '%$searchQuery%'";
$vehicles_query = "SELECT vehicle_id AS id, Name, type AS Type, speed AS info, NULL AS rarity, 'vehicles' AS table_name FROM vehicles WHERE Name LIKE '%$searchQuery%'";

// Add filters dynamically for each query
if (!empty($type)) {
    $guns_query .= " AND type = '$type'";
    $items_query .= " AND type = '$type'";
    $skins_query .= " AND type = '$type'";
    $vehicles_query .= " AND type = '$type'";
}
if (!empty($rarity)) {
    $guns_query .= " AND rarity = '$rarity'";
    $items_query .= " AND rarity = '$rarity'";
    $skins_query .= " AND rarity = '$rarity'";
    // Do not apply `rarity` to `Vehicles` as it doesn't exist
}
if ($min_damage !== null) {
    $guns_query .= " AND damage >= $min_damage";
    $vehicles_query .= " AND speed >= $min_damage"; // Assuming `speed` can be a "damage-like" filter for vehicles
}
if ($max_damage !== null) {
    $guns_query .= " AND damage <= $max_damage";
    $vehicles_query .= " AND speed <= $max_damage"; // Assuming `speed` can be a "damage-like" filter for vehicles
}

// Combine queries with UNION
$sql = "$guns_query UNION $items_query UNION $skins_query UNION $vehicles_query";

// Execute the query
$result = $conn->query($sql);

// Prepare results for Twig
$searchResults = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $searchResults[] = $row;
    }
}

// Check if the user is logged in
$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true;
$username = $is_logged_in ? $_SESSION['username'] : null;  // Get username if logged in, otherwise null

// Render the Twig template
echo $twig->render('search.html.twig', [
    'searchQuery' => $searchQuery,
    'searchResults' => $searchResults,
    'is_logged_in' => $is_logged_in,  // Pass login status to template
    'username' => $username,         // Optionally pass username if needed
    'type' => $type,                 // Pass type filter back to template
    'min_damage' => $min_damage,     // Pass min damage filter back to template
    'max_damage' => $max_damage,     // Pass max damage filter back to template
    'rarity' => $rarity              // Pass rarity filter back to template
]);
?>

<!-- Add the following script at the end of the PHP file for autocomplete -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('search-input');
        const suggestions = document.getElementById('suggestions');

        // Function to fetch suggestions
        const fetchSuggestions = (query) => {
            if (!query) {
                suggestions.innerHTML = '';
                return;
            }

            fetch(`autocomplete.php?query=${encodeURIComponent(query)}`)
                .then(response => response.text())
                .then(data => {
                    suggestions.innerHTML = data;
                })
                .catch(error => console.error('Error fetching suggestions:', error));
        };

        // Event listener for search input
        searchInput.addEventListener('input', () => {
            const query = searchInput.value.trim();
            fetchSuggestions(query);
        });

        // Optional: Clear suggestions when the input loses focus
        searchInput.addEventListener('blur', () => {
            setTimeout(() => { suggestions.innerHTML = ''; }, 100); // Delay to allow clicking on suggestions
        });

        // Optional: Select suggestion and fill input
        document.addEventListener('click', (event) => {
            if (event.target.classList.contains('suggestion-item')) {
                searchInput.value = event.target.textContent;
                suggestions.innerHTML = '';
            }
        });
    });
</script>
