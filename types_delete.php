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

    // Start a transaction
    $pdo->beginTransaction();

    try {
        // Retrieve the type value from the POST request
        $typeValue = $_POST['type'];
    
        // Check for duplicate entries in the farmer_types table
        $duplicateStmt = $pdo->prepare("SELECT * 
                                        FROM farmer_types AS ft
                                        JOIN Types AS t ON ft.typeID = t.typeID
                                        WHERE t.name = :name;");
    
        $duplicateStmt->bindParam(':name', $typeValue);
        $duplicateStmt->execute();
    
        if ($duplicateStmt->rowCount() >= 2) {
            // Duplicate entries found, delete all rows in farmer_types with the specified typeID and farmerID
            $deleteFarmerTypesStmt = $pdo->prepare("DELETE ft
                                                    FROM farmer_types AS ft
                                                    JOIN Types AS t ON ft.typeID = t.typeID
                                                    WHERE ft.farmerID = :userID AND t.name = :name;");
            $deleteFarmerTypesStmt->bindParam(':userID', $userID);
            $deleteFarmerTypesStmt->bindParam(':name', $typeValue);
            $deleteFarmerTypesStmt->execute();
    
            // Commit the transaction
            $pdo->commit();
    
            // Return a response for debugging purposes
            echo json_encode(['success' => true, 'message' => 'Duplicate entries found. Deleted corresponding farmer_types rows.']);
        } else {
            // No duplicate entries found, proceed with deletion
            $stmt = $pdo->prepare("DELETE t FROM Types t
                                  INNER JOIN farmer_types AS ft ON t.typeID = ft.typeID
                                  WHERE t.name = :name AND ft.farmerID = :userID");
            $stmt->bindParam(':name', $typeValue);
            $stmt->bindParam(':userID', $userID);
            $stmt->execute();
    
            // Commit the transaction
            $pdo->commit();
    
            // Return a success message
            echo json_encode(['success' => true, 'message' => 'Row deleted successfully']);
        }
    } catch (PDOException $e) {
        // Rollback the transaction in case of an error
        $pdo->rollBack();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }    
} catch (PDOException $e) {
    // Handle the exception as needed
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} finally {
    // Close the database connection
    $pdo = null;
}
?>
