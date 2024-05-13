<?php

session_start();

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

    // Retrieve the current and new type values from the POST request
    $currentTypeValue = $_POST['currentType'];
    $newTypeValue = $_POST['newType'];

    // Perform the update in the database only if the type is associated with the current user
    $updateQuery = "UPDATE Types t
                    INNER JOIN farmer_types AS ft ON t.typeID = ft.typeID
                    SET t.name = :newType
                    WHERE t.name = :currentType AND ft.farmerID = :userID";

    $stmt = $pdo->prepare($updateQuery);
    $stmt->bindParam(':newType', $newTypeValue);
    $stmt->bindParam(':currentType', $currentTypeValue);
    $stmt->bindParam(':userID', $_SESSION["user_id"]);
    $stmt->execute();

    echo "Updated successfully";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the database connection
$pdo = null;
?>
