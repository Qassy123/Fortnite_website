<?php
session_start(); // Start the session

require_once(__DIR__ . '/../vendor/autoload.php'); // Ensure this is correct

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates'); // Correct path to templates
$twig = new \Twig\Environment($loader);

// Handle cart logic (as in your original code)
// You can pass variables like cart_empty, cart_items, total_price to the template

// Check if the 'clear_cart' button was clicked and clear the cart
if (isset($_POST['clear_cart'])) {
    // Clear the cart session variable
    unset($_SESSION['cart']);
    
    // Redirect after clearing the cart to prevent form resubmission
    header("Location: cart.php");
    exit();
}

// Cart is empty if there are no items in the session cart
$cart_empty = !isset($_SESSION['cart']) || count($_SESSION['cart']) == 0;
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total_price = array_reduce($cart_items, function($sum, $item) {
    return $sum + $item['price']; // Assuming $item['price'] exists
}, 0);

// Render the Twig template
echo $twig->render('cart.html.twig', [
    'cart_empty' => $cart_empty,
    'cart_items' => $cart_items,
    'total_price' => $total_price,
]);