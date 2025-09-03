<?php
session_start();
require_once __DIR__ . '/db.php';

// Security check to ensure only admins can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Fetch all users
$users_result = $conn->query("SELECT id, full_name, email, role FROM users");

// Fetch all companies and their status
$companies_result = $conn->query("SELECT id, name, status FROM companies");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Accounts - Admin Panel</title>
</head>
<body>

<nav>
    <a href="admin_dashboard.php">Dashboard</a> |
    <a href="manage_accounts.php">Manage Accounts</a> |
    <a href="manage_templates.php">Manage Templates</a>
</nav>

<h1>Manage User & Company Accounts</h1>

<h2>Manage Companies</h2>
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $companies_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td>
                    <?php if ($row['status'] !== 'approved'): ?>
                        <a href="approve_company.php?id=<?php echo htmlspecialchars($row['id']); ?>&action=approve">Approve</a>
                    <?php endif; ?>
                    <?php if ($row['status'] !== 'rejected'): ?>
                        <a href="approve_company.php?id=<?php echo htmlspecialchars($row['id']); ?>&action=reject">Reject</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<br>

<h2>Manage Users</h2>
<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Role</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $users_result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['role']); ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>