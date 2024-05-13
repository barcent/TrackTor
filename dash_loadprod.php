<?php
session_start();

// Retrieve user ID from the session
if (!isset($_SESSION["user_id"])) {
    header('Location: login.php');
    exit;
}

$host = 'localhost';
$dbname = 'tracktordb';
$dbusername = 'root';
$dbpassword = '';

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host; dbname=$dbname", $dbusername, $dbpassword);

    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve the user ID from the session
    $userID = $_SESSION["user_id"];

    // Fetch products associated with the current user
    $stmt = $pdo->prepare("SELECT Products.productID, Products.name AS productName
                            FROM Products
                            JOIN farmer_products ON Products.productID = farmer_products.productID
                            WHERE farmer_products.farmerID = :userID AND Products.inserted = 0");

    
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the result as JSON
    echo json_encode($products);

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    $pdo = null;
}
?>
