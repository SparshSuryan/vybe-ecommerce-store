<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbw_project";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}

// Create 'orders' and 'order_items' tables if not exists
$sql_orders = "CREATE TABLE IF NOT EXISTS orders (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10, 2) NOT NULL,
    product_names TEXT
)";
$conn->query($sql_orders);

$sql_order_items = "CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(order_id)
)";
$conn->query($sql_order_items);

// Process POST data
$inputData = json_decode(file_get_contents("php://input"), true);

if (!$inputData || !isset($inputData['cart'])) {
    echo json_encode(['error' => 'Invalid input data']);
    exit();
}

$cart = $inputData['cart'];
$total_amount = 0;
$product_names = [];

// Start a transaction
$conn->begin_transaction();

try {
    // Insert into 'orders' table
    $sql = "INSERT INTO orders (total_amount, product_names) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    foreach ($cart as $item) {
        $product_names[] = $item['name'];
        $total_amount += $item['quantity'] * $item['price'];
    }
    $product_names_str = implode(", ", $product_names);
    $stmt->bind_param("ds", $total_amount, $product_names_str);
    $stmt->execute();
    $order_id = $stmt->insert_id;

    // Insert into 'order_items' table
    foreach ($cart as $item) {
        $sql_item = "INSERT INTO order_items (order_id, product_name, quantity, price) 
                     VALUES (?, ?, ?, ?)";
        $stmt_item = $conn->prepare($sql_item);
        $stmt_item->bind_param("isid", $order_id, $item['name'], $item['quantity'], $item['price']);
        $stmt_item->execute();
    }

    // Commit transaction
    $conn->commit();
    echo json_encode(['order_id' => $order_id]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['error' => 'Transaction failed: ' . $e->getMessage()]);
}

$conn->close();
?>
