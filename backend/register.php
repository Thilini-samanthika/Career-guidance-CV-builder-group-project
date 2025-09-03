<?php
// Set a session to track user login status
session_start();

// Ensure the database connection file is included
// The path is relative from the 'backend' folder
require_once __DIR__ . '/../db.php';

// Check if the registration button was clicked
if (isset($_POST['register_btn'])) {
    // Sanitize and validate user inputs to prevent SQL injection and other attacks
    $username = $conn->real_escape_string($_POST['username']);
    $password = $conn->real_escape_string($_POST['password']);
    $confirm_password = $conn->real_escape_string($_POST['confirm_password']);
    $email = $conn->real_escape_string($_POST['email']);

    // Simple validation checks
    if (empty($username) || empty($password) || empty($confirm_password) || empty($email)) {
        die("Please fill in all fields.");
    }
    
    // Check if passwords match
    if ($password !== $confirm_password) {
        die("Passwords do not match. Please try again.");
    }

    // Hash the password for security before storing it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to insert a new user into the database
    // The table name is 'users' as a common practice
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

    // Use a prepared statement to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $hashed_password, $email);

    if ($stmt->execute()) {
        // Registration successful, redirect the user to a login page or dashboard
        $_SESSION['success_message'] = "Registration successful! You can now log in.";
        header("Location: ../index.php"); // Adjust to your login page path
        exit();
    } else {
        // If registration fails, display an error
        die("Error: " . $stmt->error);
    }
    
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!-- HTML for the registration form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="style.css"> <!-- Assuming you have a CSS file for styling -->
</head>
<body>

<div class="registration-container">
    <h2>Register Here</h2>
    <form action="backend/register.php" method="POST">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" name="register_btn">Register</button>
    </form>
</div>

</body>
</html>
