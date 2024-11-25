<?php
session_start(); // Start the session

require_once(__DIR__ . '/../vendor/autoload.php'); // Ensure this is correct

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates'); // Correct path to templates
$twig = new \Twig\Environment($loader);

// Include database connection
include 'db.php'; 

// Fetch all guns from the database
$result = $conn->query("SELECT * FROM Guns");

// Prepare data to pass to Twig template
$guns = [];
while ($row = $result->fetch_assoc()) {
    $guns[] = $row;
}

// Render the Twig template
echo $twig->render('view_guns.html.twig', [
    'guns' => $guns
]);

