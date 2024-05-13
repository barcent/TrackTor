<?php
session_start();

// Ensure that the user is logged in
if (!isset($_SESSION["user_id"])) {
    header('location: login.php');
    exit;
}

// Retrieve data from the AJAX request
$originalProductName = $_POST['originalProduct'];
$editedWeight = $_POST['editedWeight'];
$editedStatus = $_POST['editedStatus'];
$editedExpiration = $_POST['editedExpiration'];

$host = 'localhost';
$dbname = 'tracktordb';
$dbusername = 'root';
$dbpassword = '';

try {
    // Establish a database connection
    $pdo = new PDO("mysql:host=$host; dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch the productID from the farmer_products table based on the product name
    $selectQuery = "SELECT fp.productID 
                    FROM farmer_products fp
                    JOIN Products p ON fp.productID = p.productID
                    WHERE p.name = :originalProductName";
    $selectStmt = $pdo->prepare($selectQuery);
    $selectStmt->bindParam(':originalProductName', $originalProductName);
    $selectStmt->execute();
    
    // Check if a valid productID is retrieved
    $result = $selectStmt->fetch(PDO::FETCH_ASSOC);
    if (!$result) {
        // If no valid productID is found, return an error
        echo 'error';
        exit;
    }

    $productID = $result['productID'];

    // Update the inventory_products table
    $updateQuery = "UPDATE inventory_products 
                    SET available_weight = :editedWeight, 
                        status = :editedStatus, 
                        expiration = :editedExpiration 
                    WHERE productID = :productID";

    $updateStmt = $pdo->prepare($updateQuery);

    // Bind parameters
    $updateStmt->bindParam(':editedWeight', $editedWeight);
    $updateStmt->bindParam(':editedStatus', $editedStatus);
    $updateStmt->bindParam(':editedExpiration', $editedExpiration);
    $updateStmt->bindParam(':productID', $productID);

    // Execute the update statement
    if ($updateStmt->execute()) {
        // Return success status to the client
        echo 'success';
    } else {
        // If the update fails, return an error and log the SQL error
        echo 'error';

        // Log the SQL error
        error_log('SQL Error: ' . implode(' ', $updateStmt->errorInfo()));
    }
} catch (PDOException $e) {
    // Handle the exception by returning a JSON-encoded error message
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    // Close the database connection
    $pdo = null;
}
?>
