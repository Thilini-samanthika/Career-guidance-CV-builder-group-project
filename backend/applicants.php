<?php
session_start();
include("../config/db.php");
$cid = $_SESSION["company_id"];

$sql = "SELECT a.id, u.full_name, u.email, j.title, c.pdf_path
        FROM applications a
        JOIN users u ON a.user_id=u.id
        JOIN jobs j ON a.job_id=j.id
        JOIN cvs c ON u.id=c.user_id
        JOIN companies comp ON j.company_id=comp.id
        WHERE comp.id='$cid'";
$result = $conn->query($sql);
?>

<h2>Applicants</h2>
<table border="1">
<tr><th>Name</th><th>Email</th><th>Job</th><th>CV</th></tr>
<?php while($row=$result->fetch_assoc()): ?>
<tr>
    <td><?= $row['full_name'] ?></td>
    <td><?= $row['email'] ?></td>
    <td><?= $row['title'] ?></td>
    <td><a href="download_cv.php?file=<?= $row['pdf_path'] ?>">Download</a></td>
</tr>
<?php endwhile; ?>
</table>
