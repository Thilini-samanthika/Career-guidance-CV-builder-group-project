<?php
session_start();
include("../config/db.php");
$cid = $_SESSION["company_id"];

$jobs = $conn->query("SELECT * FROM jobs WHERE company_id='$cid'");
?>
<h2>My Jobs</h2>
<a href="job_create.php">➕ New Job</a><br><br>

<?php while($job = $jobs->fetch_assoc()): ?>
    <b><?= $job['title'] ?></b><br>
    <?= $job['description'] ?><br>
    <a href="job_edit.php?id=<?= $job['id'] ?>">✏️ Edit</a> |
    <a href="job_delete.php?id=<?= $job['id'] ?>">❌ Delete</a><br><br>
<?php endwhile; ?>
