<?php
include 'db.php'; // Make sure this is the correct path

function registerUser($username, $password) {
    global $conn; // Access the global database connection
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    if ($stmt) {
        $stmt->bind_param("ss", $username, $hashedPassword);
        return $stmt->execute(); // Execute the statement
    } else {
        return false; // Failed to prepare the statement
    }
}

function loginUser($username, $password) {
    global $conn; // Access the global database connection

    // Prepare the SQL statement
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($hashedPassword); // Bind result

        // Check if we fetched a result
        if ($stmt->fetch()) {
            return password_verify($password, $hashedPassword); // Verify password
        } else {
            return false; // No user found
        }
    }
    return false; // Failed to prepare the statement
}
