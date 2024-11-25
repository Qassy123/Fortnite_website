<?php
session_start(); // Start the session

// Include the database connection
include 'db.php';

// Include Twig autoloader and set up the environment
require_once(__DIR__ . '/../vendor/autoload.php');
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig = new \Twig\Environment($loader);

// Get all the skins from the database
$result = $conn->query("SELECT * FROM Skins"); // Ensure the table name matches

// Handle the "Add to Cart" button click
if (isset($_GET['add_to_cart'])) {
    $skin_id = $_GET['add_to_cart'];

    // Fetch the item details from the database
    $item_query = $conn->prepare("SELECT * FROM Skins WHERE Skin_id = ?");
    $item_query->bind_param("i", $skin_id);
    $item_query->execute();
    $item_result = $item_query->get_result();
    $item = $item_result->fetch_assoc();

    // Add the item to the cart (session)
    if ($item) {
        $_SESSION['cart'][] = [
            'name' => $item['Name'],
            'price' => $item['price'],
            'image_url' => $item['image_url']
        ];
    }

    // Redirect back to the shop
    header('Location: shop.php');
    exit();
}

// Render the template and pass the necessary data
echo $twig->render('shop.html.twig', [
    'skins' => $result->fetch_all(MYSQLI_ASSOC) // Pass skins to the template
]);


