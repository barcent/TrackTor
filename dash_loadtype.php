<?php
session_start();

// Ensure that the user is logged in
if (!isset($_SESSION["user_id"])) {
    header('location: login.php');
    exit;
}

$host = 'localhost';
$dbname = 'tracktordb';
$dbusername = 'root';
$dbpassword = '';

// Ensure that the selectedProductID is received in the POST request
if (isset($_POST['selectedProductID'])) {
    $selectedProductID = $_POST['selectedProductID'];
    $userID = $_SESSION["user_id"];

    try {
        // Establish a database connection
        $pdo = new PDO("mysql:host=$host; dbname=$dbname", $dbusername, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Validate and sanitize input
        $selectedProductID = filter_var($selectedProductID, FILTER_VALIDATE_INT);
        $userID = filter_var($userID, FILTER_VALIDATE_INT);

        if ($selectedProductID === false || $userID === false) {
            throw new Exception("selectedProductID or userID Invalid .");
        }

        // Prepare and execute the SQL query to fetch product type
        $stmt = $pdo->prepare("SELECT Types.name AS typeName
                               FROM Products
                               JOIN Types ON Products.typeID = Types.typeID
                               JOIN farmer_products ON Products.productID = farmer_products.productID
                               WHERE Products.productID = :selectedProductID
                               AND farmer_products.farmerID = :userID");

        $stmt->bindParam(':selectedProductID', $selectedProductID, PDO::PARAM_INT);
        $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        $stmt->execute();

        // Fetch the result
        $productType = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($productType) {
            // Return the product type as JSON
            header('Content-Type: application/json');
            echo json_encode($productType);
        } else {
            // If no result found, return an empty response or an error message
            header('Content-Type: application/json');
            echo json_encode(['error' => 'No product type found for the selected product.']);
        }
    } catch (PDOException $e) {
        // Handle database connection or query error
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
    } catch (Exception $e) {
        // Handle validation error
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
    } finally {
        // Close the database connection
        $pdo = null;
    }
} else {
    // If selectedProductID is not set in the POST request, return an error
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Selected product ID not provided.']);
}
?>
