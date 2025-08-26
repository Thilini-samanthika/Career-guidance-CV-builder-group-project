<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

if (isset($_POST['register'])) {
    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($full_name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $error = "Email already exists!";
        } else {
            // Hash password and create verification token
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $token = bin2hex(random_bytes(16));

            $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, verification_token) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $full_name, $email, $hashed_password, $token);
            
            if ($stmt->execute()) {
                // Send verification email
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = "smtp.gmail.com";
                    $mail->SMTPAuth = true;
                    $mail->Username = "youremail@gmail.com";   // change this
                    $mail->Password = "your-app-password";     // Gmail App Password
                    $mail->SMTPSecure = "tls";
                    $mail->Port = 587;

                    $mail->setFrom("youremail@gmail.com", "Career Builder");
                    $mail->addAddress($email, $full_name);

                    $verify_link = "http://localhost/career_guidance_cv_builder/backend/verify.php?token=$token";

                    $mail->isHTML(true);
                    $mail->Subject = "Verify your Email";
                    $mail->Body = "Hi $full_name,<br><br> Please verify your email by clicking here: 
                                   <a href='$verify_link'>Verify Email</a>";

                    $mail->send();
                    $success = "Registration successful! Please check your email to verify.";
                } catch (Exception $e) {
                    $error = "Could not send verification email. Mailer Error: " . $mail->ErrorInfo;
                }
            } else {
                $error = "Registration failed: " . $conn->error;
            }
        }
    }
}
?>
