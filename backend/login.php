<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
session_start();
require_once __DIR__ . '/db.php';

// Check if the login form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Check if both email and password fields are filled
    if (empty($email) || empty($password)) {
        // Use a user-friendly error message that doesn't terminate the script
        echo "All fields are required!";
    } else {
        // Prepare the statement to fetch user data
        $stmt = $conn->prepare("SELECT user_id, full_name, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        // Check if a user with the given email exists
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $full_name, $hashed_password, $role);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Login success -> Set session variables
                $_SESSION['user_id'] = $user_id;
                $_SESSION['full_name'] = $full_name;
                $_SESSION['role'] = $role;

                // Redirect based on the user's role
                if ($role == 'user') {
                    header("Location: user_dashboard.php");
                } elseif ($role == 'company') {
                    header("Location: company_dashboard.php");
                } elseif ($role == 'admin') {
                    header("Location: admin_dashboard.php");
                }
                exit();
            } else {
                echo "Invalid email or password!";
            }
        } else {
            echo "Invalid email or password!";
        }
        
        $stmt->close();
    }
}
?>