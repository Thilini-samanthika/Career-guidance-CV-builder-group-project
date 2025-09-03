<?php
session_start();
require_once __DIR__ . '/db.php';

// Security check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if (isset($_POST['upload_template'])) {
    $template_name = trim($_POST['template_name']);

    if (empty($template_name)) {
        die("Template name is required.");
    }

    // Set up directories for uploads.
    $template_dir = __DIR__ . "/uploads/templates/";
    $preview_dir = __DIR__ . "/uploads/previews/";

    // Check for upload errors
    if ($_FILES['template_file']['error'] !== UPLOAD_ERR_OK || $_FILES['preview_image']['error'] !== UPLOAD_ERR_OK) {
        die("File upload error.");
    }

    // Generate unique filenames to prevent overwrites
    $template_ext = pathinfo($_FILES['template_file']['name'], PATHINFO_EXTENSION);
    $preview_ext = pathinfo($_FILES['preview_image']['name'], PATHINFO_EXTENSION);
    
    $template_filename = uniqid('template_', true) . '.' . $template_ext;
    $preview_filename = uniqid('preview_', true) . '.' . $preview_ext;

    $template_path = $template_dir . $template_filename;
    $preview_path = $preview_dir . $preview_filename;

    // Move the uploaded files
    if (move_uploaded_file($_FILES['template_file']['tmp_name'], $template_path) && move_uploaded_file($_FILES['preview_image']['tmp_name'], $preview_path)) {
        // Save file paths and template name to the database
        $stmt = $conn->prepare("INSERT INTO cv_templates (template_name, file_path, preview_image_path) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $template_name, $template_path, $preview_path);

        if ($stmt->execute()) {
            echo "Template uploaded successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Failed to move uploaded files.";
    }
} else {
    echo "Invalid request.";
}

// Redirect back to the templates page after processing
header('Location: manage_templates.php');
exit();
?>