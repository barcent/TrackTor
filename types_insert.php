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

    // Start a transaction
    $pdo->beginTransaction();

    try {
        // Check if the type already exists so typeID will be used 
        $CheckStmt = $pdo->prepare("SELECT * FROM Types WHERE LOWER(Types.name) = LOWER(:type)");
        $CheckStmt->bindParam(':type', $value);
        $CheckStmt->execute();

        if ($CheckStmt->rowCount() == 0) {
            // Insert data into the Types table
            $insertStmt = $pdo->prepare("INSERT INTO Types (name) VALUES (:type)");
            $insertStmt->bindParam(':type', $value);
            $insertStmt->execute();

            // Retrieve the last inserted ID
            $typeID = $pdo->lastInsertId();
        } else {
            // Fetch the row from the result
            $row = $CheckStmt->fetch(PDO::FETCH_ASSOC);
            $typeID = $row['typeID'];
        }

        // Insert data into the farmer_types table
        $farmerTypesStmt = $pdo->prepare("INSERT INTO farmer_types (farmerID, typeID) VALUES (:userID, :typeID)");
        $farmerTypesStmt->bindParam(':userID', $userID, PDO::PARAM_INT);
        $farmerTypesStmt->bindParam(':typeID', $typeID, PDO::PARAM_INT);
        $farmerTypesStmt->execute();

        // Commit the transaction
        $pdo->commit();

        // Return a success response
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        // Rollback the transaction in case of an error
        $pdo->rollBack();
        error_log("Error: " . $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'An error occurred. Please try again later.']);
    }
} catch (PDOException $e) {
    // Handle the exception as needed
    error_log("Error: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'An error occurred. Please try again later.']);
} finally {
    // Close the database connection
    $pdo = null;
}
?>
