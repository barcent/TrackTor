<?php
session_start();

// Retrieve user ID from the session
if (!isset($_SESSION["user_id"])) {
    header('Location: login.php');
    exit;
}

$userID = $_SESSION["user_id"];

$host = 'localhost';
$dbname = 'tracktordb';
$dbusername = 'root';
$dbpassword = '';

try {
    $pdo = new PDO("mysql:host=$host; dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve the product value from the POST request
    $productName = $_POST['product'];

    // Perform the deletion in the database only if the product is associated with the current user
    $stmt = $pdo->prepare("DELETE p FROM Products p
                          INNER JOIN farmer_products AS fp ON p.productID = fp.productID
                          WHERE p.name = :productName AND fp.farmerID = :userID");
    $stmt->bindParam(':productName', $productName);
    $stmt->bindParam(':userID', $userID);
    $stmt->execute();

    // Output a success message (you can customize this based on your needs)
    echo "Product deleted successfully";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the database connection
$pdo = null;
?>
