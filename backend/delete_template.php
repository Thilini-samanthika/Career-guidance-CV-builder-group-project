<?php
session_start();
require_once __DIR__ . '/db.php';

// Security check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $template_id = intval($_GET['id']);

    // First, get the file paths from the database
    $stmt = $conn->prepare("SELECT file_path, preview_image_path FROM cv_templates WHERE template_id = ?");
    $stmt->bind_param("i", $template_id);
    $stmt->execute();
    $stmt->bind_result($file_path, $preview_image_path);
    $stmt->fetch();
    $stmt->close();

    // Check if the file paths exist
    if ($file_path && $preview_image_path) {
        // Delete the files from the server
        if (file_exists($file_path) && file_exists($preview_image_path)) {
            unlink($file_path);
            unlink($preview_image_path);
        }

        // Then, delete the entry from the database
        $stmt = $conn->prepare("DELETE FROM cv_templates WHERE template_id = ?");
        $stmt->bind_param("i", $template_id);
        $stmt->execute();
        $stmt->close();

        echo "Template deleted successfully.";
    } else {
        echo "Template not found.";
    }
}

// Redirect back to the manage templates page
header('Location: manage_templates.php');
exit();
?>