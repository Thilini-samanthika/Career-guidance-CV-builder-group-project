<?php
// Database credentials - You must fill these in with your own data.
$servername = "localhost";
$username = "your_username"; // Replace with your MySQL username
$password = "your_password"; // Replace with your MySQL password
$dbname = "your_database_name"; // Replace with your database name

// Create a new database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    // If connection fails, stop the script and show an error
    die("Connection failed: " . $conn->connect_error);
}
?>
```
eof

### Step 3: Link Other PHP Files to `db.php`

Every PHP script that needs to interact with the database (e.g., to read, write, update, or delete data) must include your `db.php` file. This is done using a single line of code.

1.  **Open `register.php`**.
2.  Ensure this line is at the very top of the file, right after `session_start()`:
    ```php
    require_once __DIR__ . '/../db.php';
    
