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

    // Retrieve the value from the POST request
    $value = $_POST['value'];

    // Retrieve the user ID from the session
    $userID = $_SESSION["user_id"];

    // Check if the value already exists for the specific user
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM Types 
                                JOIN farmer_types ON Types.typeID = farmer_types.typeID
                                WHERE farmer_types.farmerID = :userID AND Types.name = :value");

    $checkStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $checkStmt->bindParam(':value', $value);
    $checkStmt->execute();
    $rowCount = $checkStmt->fetchColumn();

    // Return the result as a JSON response
    echo json_encode(['exists' => $rowCount > 0]);

} catch (PDOException $e) {
    echo json_encode(['exists' => false, 'error' => $e->getMessage()]);
} finally {
    $pdo = null;
}
?>
