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
    $pdo = new PDO("mysql:host=$host; dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve the current and new values from the FormData object
    $currentProdNameValue = $_POST['currentProdName'];
    $newProdNameValue = $_POST['newProdName'];
    $newProdTypeValue = $_POST['newProdType'];

    // Perform the update in the database only if the product is associated with the current user
    $updateQuery = "UPDATE Products p
                    INNER JOIN farmer_products AS fp ON p.productID = fp.productID
                    SET p.name = :newProdName, p.typeID = :newProdType
                    WHERE p.name = :currentProdName AND fp.farmerID = :userID";

    $stmt = $pdo->prepare($updateQuery);
    $stmt->bindParam(':newProdName', $newProdNameValue);
    $stmt->bindParam(':newProdType', $newProdTypeValue);
    $stmt->bindParam(':currentProdName', $currentProdNameValue);
    $stmt->bindParam(':userID', $_SESSION["user_id"]);
    $stmt->execute();

    echo "Updated successfully";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the database connection
$pdo = null;
?>
