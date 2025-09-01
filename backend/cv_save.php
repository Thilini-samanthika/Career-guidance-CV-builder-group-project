<?php
session_start();
require_once "db_connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$fullname   = trim($_POST['fullname'] ?? '');
$email      = trim($_POST['email'] ?? '');
$phone      = trim($_POST['phone'] ?? '');
$linkedin   = trim($_POST['linkedin'] ?? '');
$about      = trim($_POST['about'] ?? '');
$education  = trim($_POST['education'] ?? '');
$work_exp   = trim($_POST['work_experinces'] ?? '');  // fix field name
$projects   = trim($_POST['projects'] ?? '');
$skills     = trim($_POST['skills'] ?? '');
$template   = trim($_POST['template'] ?? 'first');

// Validation
if ($fullname === "" || $email === "" || $about === "" || $education === "" || $work_exp === "" || $projects === "") {
    echo "Please fill all required fields!";
    exit;
}

// Save to DB
$stmt = $pdo->prepare("INSERT INTO cvs 
    (user_id, fullname, email, phone, linkedin, about, education, work_experiences, projects, skills, template) 
    VALUES (?,?,?,?,?,?,?,?,?,?,?)");

$stmt->execute([$user_id, $fullname, $email, $phone, $linkedin, $about, $education, $work_exp, $projects, $skills, $template]);

echo "CV saved successfully! <a href='dashboard.php'>Back to Dashboard</a>";
