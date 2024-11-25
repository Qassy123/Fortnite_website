<?php
require_once(__DIR__ . '/../vendor/autoload.php'); // Include the Twig autoloader

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates'); // Path to the templates directory
$twig = new \Twig\Environment($loader);

// Start the session to check if the user is logged in
session_start();

// Logout logic: Check if a logout action has been requested
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    // Destroy the session and clear cookies if necessary
    session_unset(); // Remove session variables
    session_destroy(); // Destroy the session

    // Optionally, remove cookies if you set them for remember me
    setcookie('username', '', time() - 3600, '/'); // Expire the username cookie
    setcookie('logged_in', '', time() - 3600, '/'); // Expire the logged_in cookie

    // Redirect to the homepage or login page after logging out
    header("Location: index.php");
    exit(); // Make sure the rest of the code doesn't run
}

// Define the title and search query as before
$title = "Home"; // Example title, you can replace it with whatever data you need
$search_query = isset($_GET['query']) ? $_GET['query'] : ''; // If there's a search query from GET

// Check if the user is logged in or not
$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true;
$username = $is_logged_in ? $_SESSION['username'] : null;  // Get username if logged in, otherwise null

// Render the index template and pass data
echo $twig->render('index.html.twig', [
    'title' => $title,
    'search_query' => $search_query,
    'logged_in' => $is_logged_in, // Add this to pass logged_in status
    'username' => $username, // Pass the username to display if logged in
]);
