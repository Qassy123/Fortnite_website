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

// Check if the user is logged in
$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true;
$username = $is_logged_in ? $_SESSION['username'] : null;  // Get username if logged in, otherwise null

// Render the Twig template
echo $twig->render('view_skins.html.twig', [
    'skins' => $skins,
    'is_logged_in' => $is_logged_in, // Pass login status to template
    'username' => $username, // Optionally pass username if needed
]);
