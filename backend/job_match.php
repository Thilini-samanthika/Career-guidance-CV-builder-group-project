<?php
session_start();
require_once "db_connect.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user CV skills
$stmt = $pdo->prepare("SELECT skills FROM cvs WHERE user_id=?");
$stmt->execute([$user_id]);
$cv = $stmt->fetch();

if (!$cv) {
    die("<p class='alert alert-warning'>Please create your CV first!</p>");
}

$userSkills = array_map('trim', explode(',', $cv['skills']));

// Build dynamic SQL
$clauses = [];
$params = [];
foreach ($userSkills as $skill) {
    $clauses[] = "requirements LIKE ?";
    $params[] = "%" . $skill . "%";
}

$sql = "SELECT id, title, company, location, requirements 
        FROM jobs 
        WHERE " . implode(" OR ", $clauses);

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate match score
foreach ($jobs as &$job) {
    $jobSkills = array_map('trim', explode(',', $job['requirements']));
    $score = 0;
    foreach ($userSkills as $skill) {
        if (in_array($skill, $jobSkills)) {
            $score++;
        }
    }
    $job['match_score'] = $score;
}

// Sort by best match
usort($jobs, fn($a, $b) => $b['match_score'] <=> $a['match_score']);
?>
