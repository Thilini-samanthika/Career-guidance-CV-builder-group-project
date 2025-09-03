<?php 
session_start();
require_once __DIR__ . '/db.php';

// Security check to ensure only admins can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Check if both company ID and action are present in the URL
if (isset($_GET['id']) && isset($_GET['action'])) {
    $company_id = intval($_GET['id']);
    $action = $_GET['action'];

    // Validate the action to prevent unexpected values
    if ($action === 'approve' || $action === 'reject') {
        $status = ($action === 'approve') ? 'approved' : 'rejected';

        // Use a prepared statement to safely update the company status
        if ($stmt = $conn->prepare("UPDATE companies SET status = ? WHERE id = ?")) {
            $stmt->bind_param("si", $status, $company_id);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Redirect back to the manage accounts page to see the updated status
header('Location: manage_accounts.php');
exit();
?>
