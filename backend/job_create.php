<?php
session_start();
include("../config/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $desc = $_POST["description"];
    $company_id = $_SESSION["company_id"];

    $sql = "INSERT INTO jobs (company_id,title,description) VALUES ('$company_id','$title','$desc')";
    if ($conn->query($sql)) {
        echo "Job created! <a href='jobs_list.php'>View Jobs</a>";
    }
}
?>

<form method="post">
    <input type="text" name="title" placeholder="Job Title" required><br>
    <textarea name="description" placeholder="Description"></textarea><br>
    <button type="submit">Create Job</button>
</form>
