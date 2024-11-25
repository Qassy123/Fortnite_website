<?php
session_start(); // Start the session

require_once(__DIR__ . '/../vendor/autoload.php'); // Ensure this is correct

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates'); // Correct path to templates
$twig = new \Twig\Environment($loader);

// Include database connection
include 'db.php'; 

// Fetch all vehicles from the database
$result = $conn->query("SELECT * FROM vehicles");

// Prepare data to pass to Twig template
$vehicles = [];
while ($row = $result->fetch_assoc()) {
    $vehicles[] = $row;
}

// Render the Twig template
echo $twig->render('view_vehicles.html.twig', [
    'vehicles' => $vehicles
]);
