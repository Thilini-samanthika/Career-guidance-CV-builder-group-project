<?php
session_start();
include 'db.php'; 

// Session check - only company users
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'company') {
    header("Location: login.php");
    exit();
}
echo "welcome " . $_SESSION['full_name'];
$company_id = $_SESSION['user_id'];
$company_name = $_SESSION['full_name'];

// Fetch stats
$totalJobs = $conn->query("SELECT COUNT(*) as total_jobs FROM jobs WHERE company_id=$company_id")->fetch_assoc()['total_jobs'];
$totalApplicants = $conn->query("SELECT COUNT(*) as total_applicants FROM applicants WHERE job_id IN (SELECT id FROM jobs WHERE company_id=$company_id)")->fetch_assoc()['total_applicants'];
$cvDownloads = $conn->query("SELECT COUNT(*) as cv_downloads FROM cv_downloads WHERE job_id IN (SELECT id FROM jobs WHERE company_id=$company_id)")->fetch_assoc()['cv_downloads'];
$openPositions = $conn->query("SELECT COUNT(*) as open_positions FROM jobs WHERE company_id=$company_id AND status='Open'")->fetch_assoc()['open_positions'];

// Recent jobs
$recentJobs = $conn->query("SELECT * FROM jobs WHERE company_id=$company_id ORDER BY posted_on DESC LIMIT 5");

// Chart Data - Applicants per month
$months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
$applicantsData = [];
foreach ($months as $index => $month) {
    $monthNum = $index + 1;
    $count = $conn->query("SELECT COUNT(*) as count FROM applicants WHERE MONTH(applied_on)=$monthNum AND job_id IN (SELECT id FROM jobs WHERE company_id=$company_id)")->fetch_assoc()['count'];
    $applicantsData[] = $count;
}
$applicantsDataJSON = json_encode($applicantsData);
?>