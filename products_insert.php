<?php
session_start();

// Retrieve user ID from the session
if (!isset($_SESSION["user_id"])) {
    header('Location: login.php');
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tracktordb";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get values from the form
    $productName = $_POST['productName'];
    $productType = $_POST['productType'];

    // Retrieve the user ID from the session
    $userID = $_SESSION["user_id"];

    // Start a transaction
    $conn->beginTransaction();

    try {
        // Insert data into the database
        $stmt = $conn->prepare("INSERT INTO Products (name, typeID) VALUES (:productName, :productType)");
        $stmt->bindParam(':productName', $productName);
        $stmt->bindParam(':productType', $productType);
        $stmt->execute();

        // Retrieve the last inserted ID
        $productID = $conn->lastInsertId();

        // Insert data into the farmer_types table
        $stmt2 = $conn->prepare("INSERT INTO farmer_products (farmerID, productID) VALUES (:userID, :productID)");
        $stmt2->bindParam(':userID', $userID, PDO::PARAM_INT);
        $stmt2->bindParam(':productID', $productID, PDO::PARAM_INT);
        $stmt2->execute();

        // Commit the transaction
        $conn->commit();

        echo "Product added successfully!";

    } catch (PDOException $e) {
        // Rollback the transaction in case of an error
        $conn->rollBack();
        echo "Error adding product. Please try again.";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}

// Close the database connection
$conn = null;
?>
