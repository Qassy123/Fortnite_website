<?php
require_once(__DIR__ . '/../vendor/autoload.php'); // Include the Twig autoloader

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates'); // Path to the templates directory
$twig = new \Twig\Environment($loader);

// You can define variables here if you need to pass dynamic data to the template
// For example, you can pass information like the title or any other content
$title = "Home"; // Example title, you can replace it with whatever data you need
$search_query = isset($_GET['query']) ? $_GET['query'] : ''; // If there's a search query from GET

// Render the index template and pass data
echo $twig->render('index.html.twig', [
    'title' => $title,
    'search_query' => $search_query,
]);

