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
    $deleteResult = delete_inventory_product_by_name($pdo, $userID, $productName);

    if ($deleteResult) {
        echo "Farm item is deleted successfully";
    } else {
        echo "Error deleting farm item";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the database connection
$pdo = null;

// Function for deleting inventory product and updating 'inserted' column
function delete_inventory_product_by_name(object $pdo, int $userID, string $productName) {
    $deleteQuery = "DELETE ip FROM inventory_products AS ip
                    INNER JOIN Products AS p ON ip.productID = p.productID
                    INNER JOIN farmer_products AS fp ON p.productID = fp.productID
                    WHERE p.name = :productName AND fp.farmerID = :userID";

    $updateQuery = "UPDATE Products SET inserted = false WHERE name = :productName";

    try {
        $pdo->beginTransaction();

        // Perform the deletion
        $deleteStmt = $pdo->prepare($deleteQuery);
        $deleteStmt->bindParam(':productName', $productName);
        $deleteStmt->bindParam(':userID', $userID);
        $deleteStmt->execute();

        // Update the 'inserted' column
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':productName', $productName);
        $updateStmt->execute();

        $pdo->commit();

        // Returning true indicates the success of the deletion
        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}
?>
