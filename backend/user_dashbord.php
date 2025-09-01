<?php
// user_dashboard.php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !='user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'] ?? 'User';

// Database connection
$host = "localhost";
$db_user = "root";
$db_pass = ""; // your MySQL password
$db_name = "career_db";

$conn = new mysqli($host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch job suggestions for this user
$job_suggestions = [];
$stmt = $conn->prepare("SELECT title, company FROM jobs ORDER BY created_at DESC LIMIT 5");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $job_suggestions[] = $row;
}
$stmt->close();
$conn->close();
?>
