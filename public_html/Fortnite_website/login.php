<?php
include 'db.php'; // Database connection

session_start(); // Start the session to store user login status

require_once(__DIR__ . '/../vendor/autoload.php'); // Ensure this is correct

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates'); // Correct path to templates
$twig = new \Twig\Environment($loader);

// Handle login logic
$error_message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Call your loginUser function to validate user credentials
    if (loginUser($username, $password)) {
        $_SESSION['username'] = $username; // Store username in session
        $_SESSION['logged_in'] = true; // Mark user as logged in
        
        // Redirect to homepage after successful login
        header("Location: index.php");
        exit(); // Ensure no further code is executed
    } else {
        $error_message = "Invalid username or password.";
    }
}

function loginUser($username, $password) {
    global $conn;
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($hashedPassword);
    
    if ($stmt->fetch()) {
        return password_verify($password, $hashedPassword); // Check password
    }
    return false;
}

// Render the Twig template
echo $twig->render('login.html.twig', [
    'error_message' => $error_message
]);
