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

// Render the Twig template
echo $twig->render('view_items.html.twig', [
    'items' => $items
]);
