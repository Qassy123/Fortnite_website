<?php
session_start(); // Start the session to store user registration status

require_once(__DIR__ . '/../vendor/autoload.php'); // Ensure the correct path to autoload

// Include the database connection file
include 'db.php'; // Ensure this path is correct

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates'); // Correct path to templates
$twig = new \Twig\Environment($loader);

// Function to register the user
function registerUser($username, $password) {
    global $conn;
    
    // Check if the username already exists
    $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    // If the username already exists, return false
    if ($count > 0) {
        return false;
    }

    // Otherwise, hash the password and insert the user
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashedPassword);

    return $stmt->execute(); // Return true if the user is inserted successfully
}

// Variable to hold the error message, if any
$error_message = null;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Try to register the user
    if (registerUser($username, $password)) {
        $_SESSION['username'] = $username; // Store username in session
        $_SESSION['logged_in'] = true; // Mark user as logged in
        
        // Redirect to homepage after successful registration
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Username already exists. Please choose a different one.";
    }
}

// Render the Twig template
echo $twig->render('register.html.twig', [
    'error_message' => $error_message
]);
 