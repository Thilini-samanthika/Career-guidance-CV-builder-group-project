<?php
require_once __DIR__ . 'db.php';
 if(isset($_GET['TOKEN'])){
    $token =$_GET['token'];
    $stmt = $conn->prepare("SELECT id FROM users WHERE verification_token = ? AND is_verified =0");
    $stmt->bind_param("s",$token);
    $stmt->execute();
    $Stmt->store_result();

    if($stmt->num_rows >0){
        $stmt = $conn->prepare("UPDATE users SET is_verified = 1,verification_token = NULL WHERE verification_token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        echo "Email verified successfully! You can now <a href='../templates/user_login.html'>login</a>.";
    } else {
        echo "Invalid or expired token!";
    }
}
?>" 