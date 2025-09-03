<?php
session_start();
require_once __DIR__ . '/db.php';

// Security check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Fetch all existing templates to display them
$templates_result = $conn->query("SELECT template_id, template_name, preview_image_path FROM cv_templates");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage CV Templates - Admin Panel</title>
</head>
<body>

<nav>
    <a href="admin_dashboard.php">Dashboard</a> |
    <a href="manage_accounts.php">Manage Accounts</a> |
    <a href="manage_templates.php">Manage Templates</a>
</nav>

<h1>Manage CV Templates</h1>

<h2>Upload New Template</h2>
<form action="upload_template.php" method="post" enctype="multipart/form-data">
    <label for="template_name">Template Name:</label>
    <br>
    <input type="text" id="template_name" name="template_name" required>
    <br><br>
    
    <label for="template_file">Template File (.docx, .pdf, etc.):</label>
    <br>
    <input type="file" id="template_file" name="template_file" required>
    <br><br>
    
    <label for="preview_image">Preview Image (e.g., .png, .jpg):</label>
    <br>
    <input type="file" id="preview_image" name="preview_image" required>
    <br><br>
    
    <button type="submit" name="upload_template">Upload Template</button>
</form>

<hr>

<h2>Existing Templates</h2>
<?php if ($templates_result->num_rows > 0): ?>
    <div style="display: flex; flex-wrap: wrap; gap: 20px;">
        <?php while ($row = $templates_result->fetch_assoc()): ?>
            <div style="border: 1px solid #ccc; padding: 10px; text-align: center;">
                <h3><?php echo htmlspecialchars($row['template_name']); ?></h3>
                <img src="uploads/previews/<?php echo htmlspecialchars(basename($row['preview_image_path'])); ?>" alt="Template Preview" style="width: 200px; height: auto;">
                <p>
                    <a href="delete_template.php?id=<?php echo $row['template_id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
                </p>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <p>No templates uploaded yet.</p>
<?php endif; ?>

</body>
</html>