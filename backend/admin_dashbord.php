<?php
// admin_dashboard.php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/db.php'; // Use your existing db.php connection file

// Fetch total users
$total_users = 0;
$result = $conn->query("SELECT COUNT(*) AS total_users FROM users");
if ($result) {
    $total_users = $result->fetch_assoc()['total_users'] ?? 0;
}

// Fetch total CVs generated
$total_cvs = 0;
$result = $conn->query("SELECT COUNT(*) AS total_cvs FROM cvs");
if ($result) {
    $total_cvs = $result->fetch_assoc()['total_cvs'] ?? 0;
}

// Optional: Fetch visitors
$visitors = 0;
$result = $conn->query("SELECT COUNT(*) AS visitors FROM visitors"); // ensure table exists
if ($result) {
    $visitors = $result->fetch_assoc()['visitors'] ?? 0;
}

// Optional: Fetch messages
$messages = 0;
$result = $conn->query("SELECT COUNT(*) AS messages FROM messages"); // ensure table exists
if ($result) {
    $messages = $result->fetch_assoc()['messages'] ?? 0;
}

// Fetch recent CV downloads
$recent_downloads = [];
$result = $conn->query("
    SELECT u.full_name, c.template AS template_name, c.created_at AS downloaded_at
    FROM cvs c
    JOIN users u ON c.user_id = u.user_id
    ORDER BY c.created_at DESC
    LIMIT 5
");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $recent_downloads[] = $row;
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <nav class="mb-4">
        <a href="admin_dashboard.php" class="btn btn-outline-primary btn-sm">Dashboard</a>
        <a href="manage_users.php" class="btn btn-outline-primary btn-sm">Manage Users</a>
        <a href="manage_companies.php" class="btn btn-outline-primary btn-sm">Manage Companies</a>
        <a href="manage_templates.php" class="btn btn-outline-primary btn-sm">Manage Templates</a>
        <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </nav>

    <div class="container">
        <h1>Welcome, Admin!</h1>

        <h3 class="mt-4">Dashboard Stats</h3>
        <ul>
            <li>Total Users: <?php echo $total_users; ?></li>
            <li>Total CVs Generated: <?php echo $total_cvs; ?></li>
            <li>Total Visitors: <?php echo $visitors; ?></li>
            <li>Total Messages: <?php echo $messages; ?></li>
        </ul>

        <h3 class="mt-4">Recent CV Downloads</h3>
        <?php if (!empty($recent_downloads)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>User Name</th>
                        <th>Template</th>
                        <th>Downloaded At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_downloads as $cv): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cv['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($cv['template_name']); ?></td>
                            <td><?php echo htmlspecialchars($cv['downloaded_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No CV downloads yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>
