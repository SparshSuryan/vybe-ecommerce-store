<?php
// Database credentials
$host = "localhost";
$username = "root";
$password = "";
$db_name = "dbw_project";

// Connect to MySQL server
$conn = new mysqli($host, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sql_create_db = "CREATE DATABASE IF NOT EXISTS $db_name";
$conn->query($sql_create_db);

// Select the database
$conn->select_db($db_name);

// Create the `users` table if not exists
$sql_create_table = "
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(255) NOT NULL UNIQUE,
    fullname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(15) NOT NULL,
    street_address TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    country VARCHAR(100) NOT NULL,
    postal_code VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($sql_create_table);

// Handle form operations (Insert)
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['operation'])) {
    $operation = $_POST['operation']; // Insert
    
    if ($operation === "Insert") {
        // Sanitize and validate inputs
        $user_id = $_POST['user_id'];
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $street_address = $_POST['street_address'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $country = $_POST['country'];
        $postal_code = $_POST['postal_code'];

        // Prepare the insert statement
        $stmt = $conn->prepare("
            INSERT INTO users (user_id, fullname, email, phone, street_address, city, state, country, postal_code)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "sssssssss",
            $user_id,
            $fullname,
            $email,
            $phone,
            $street_address,
            $city,
            $state,
            $country,
            $postal_code
        );
        
        // Execute the statement
        if ($stmt->execute()) {
            // JavaScript for popup and redirection
            echo "
            <script>
                alert('Account successfully created!');
                window.location.href = 'index.html'; // Redirect to home page
            </script>
            ";
        } else {
            echo "
            <script>
                alert('Error inserting user: " . addslashes($stmt->error) . "');
                window.history.back(); // Redirect back to form
            </script>
            ";
        }
        $stmt->close();
    }
}

// Close the connection
$conn->close();
?>
