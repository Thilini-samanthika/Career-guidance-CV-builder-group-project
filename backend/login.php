<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
session_start();
require_once __DIR__ . '/db.php';

if (isset($_POST['login'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    die("All fields are required!");
}

$token = bin2hex(random_bytes(16));
    // Database query
    $stmt = $conn->prepare("SELECT id, full_name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $full_name, $hashed_password, $role);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Login success → Set session variables
            $_SESSION['user_id'] = $id;
            $_SESSION['full_name'] = $full_name;
            $_SESSION['role'] = $role;
            // Redirect based on role
            if ($role == 'user') {
                header("Location: user_dashboard.php");
                exit();
            } elseif ($role == 'company') {
                header("Location: company_dashboard.php");
                exit();
            } elseif ($role == 'admin') {
                header("Location: admin_dashboard.php");
                exit();
            }
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "User not found!";
    }
    if (empty($email) || empty($password)) {
    die("All fields are required!");
}

}
?>
