<?php
session_start();

// Retrieve user ID from the session
if (!isset($_SESSION["user_id"])) {
    header('Location: login.php');
    exit;
}

// Retrieve JSON data from the POST request
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

// Access the form data as an associative array
$productId = $data['productId'];
$productType = $data['productType'];
$weight = $data['weight'];
$status = $data['status'];
$expiration = $data['expiration'];

$farmerID = $_SESSION["user_id"];

// Database connection parameters
$host = 'localhost';
$dbname = 'tracktordb';
$dbusername = 'root';
$dbpassword = '';

try {
    // Establish a database connection
    $pdo = new PDO("mysql:host=$host; dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute the SQL query to insert data into the inventory_products table
    $stmt = $pdo->prepare("INSERT INTO inventory_products (inventoryID, productID, available_weight, status, expiration) 
                           VALUES ((SELECT inventoryID FROM Inventories WHERE farmerID = :farmerID), :productID, :weight, :status, :expiration)");

    // Bind parameters
    $stmt->bindParam(':farmerID', $farmerID, PDO::PARAM_INT);
    $stmt->bindParam(':productID', $productId, PDO::PARAM_INT);
    $stmt->bindParam(':weight', $weight, PDO::PARAM_INT);
    $stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $stmt->bindParam(':expiration', $expiration, PDO::PARAM_STR);
    $stmt->execute();


    // Prepare and execute the SQL query to update the Product's inserted attribute to true
    $updateStmt = $pdo->prepare("UPDATE Products SET inserted = true WHERE productID = :productID");
    $updateStmt->bindParam(':productID', $productId, PDO::PARAM_INT);
    $updateStmt->execute();

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} finally {
    $pdo = null;
}
?>
