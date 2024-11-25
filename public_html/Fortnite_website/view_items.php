<?php
session_start(); // Start the session

require_once(__DIR__ . '/../vendor/autoload.php'); // Ensure this is correct for autoload

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates'); // Correct path to templates
$twig = new \Twig\Environment($loader);

// Include database connection
include 'db.php'; 

// Fetch all items from the database
$result = $conn->query("SELECT * FROM Items");

// Prepare data to pass to Twig template
$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

// Check if the user is logged in or not
$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true;
$username = $is_logged_in ? $_SESSION['username'] : null;  // Get username if logged in, otherwise null

// Render the Twig template
echo $twig->render('view_items.html.twig', [
    'items' => $items,
    'is_logged_in' => $is_logged_in,  // Pass the login status to the template
    'username' => $username, // Pass the username if logged in
]);
