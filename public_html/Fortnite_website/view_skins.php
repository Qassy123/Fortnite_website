<?php
session_start(); // Start the session

require_once(__DIR__ . '/../vendor/autoload.php'); // Ensure this is correct

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates'); // Correct path to templates
$twig = new \Twig\Environment($loader);

// Include database connection
include 'db.php'; 

// Fetch all skins from the database
$result = $conn->query("SELECT * FROM Skins");

// Prepare data to pass to Twig template
$skins = [];
while ($row = $result->fetch_assoc()) {
    $skins[] = $row;
}

// Render the Twig template
echo $twig->render('view_skins.html.twig', [
    'skins' => $skins
]);
