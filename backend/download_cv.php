<?php
$file = $_GET['file'];
$path = "../" . $file;

if (file_exists($path)) {
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="'.basename($path).'"');
    readfile($path);
    exit;
} else {
    echo "File not found.";
}
?>
