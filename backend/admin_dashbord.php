<?php
// admin_dashboard.php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Database connection
$host = "localhost";
$db_user = "root";
$db_pass = ""; // your MySQL password
$db_name = "career_db";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch total users
$result = $conn->query("SELECT COUNT(*) AS total_users FROM users");
$total_users = $result->fetch_assoc()['total_users'] ?? 0;

// Fetch total CVs generated
$result = $conn->query("SELECT COUNT(*) AS total_cvs FROM cvs"); // assuming cvs table exists
$total_cvs = $result->fetch_assoc()['total_cvs'] ?? 0;

// Fetch visitors (example: from analytics table)
$result = $conn->query("SELECT COUNT(*) AS visitors FROM visitors"); // optional
$visitors = $result->fetch_assoc()['visitors'] ?? 0;

// Fetch messages (example: contact messages)
$result = $conn->query("SELECT COUNT(*) AS messages FROM messages"); // optional
$messages = $result->fetch_assoc()['messages'] ?? 0;

// Fetch recent CV downloads
$recent_downloads = [];
$result = $conn->query("SELECT u.full_name, c.template_name, c.downloaded_at, c.status 
                        FROM cvs c 
                        JOIN users u ON c.user_id = u.id 
                        ORDER BY c.downloaded_at DESC 
                        LIMIT 5");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $recent_downloads[] = $row;
    }
}
$conn->close();
?>