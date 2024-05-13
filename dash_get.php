<?php
session_start();

// Retrieve user ID from the session
if (!isset($_SESSION["user_id"])) {
    header('Location: login.php');
    exit;
}

$userID = $_SESSION["user_id"];

// Database connection parameters
$host = 'localhost';
$dbname = 'tracktordb';
$dbusername = 'root';
$dbpassword = '';

try {
    // Establish a database connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute the SQL query to retrieve user's product inventory
    $stmt = $pdo->prepare("SELECT IP.available_weight, IP.status, IP.expiration, P.name AS productName, T.name AS typeName
                            FROM inventory_products IP
                            JOIN Products P ON IP.productID = P.productID
                            JOIN Types T ON P.typeID = T.typeID
                            WHERE IP.inventoryID IN (SELECT inventoryID FROM Inventories WHERE farmerID = :userID)");

    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt->execute();


if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo '<tr data-productid="' . $row['productID'] . '" data-typeid="' . $row['typeID'] . '">';
        echo '<td>' . htmlspecialchars($row['productName']) . '</td>';
        echo '<td>' . $row['available_weight'] . '</td>';
        echo '<td>' . $row['status'] . '</td>';
        echo '<td>' . $row['expiration'] . '</td>';
        echo '<td>' . $row['typeName'] . '</td>';
        echo '<td><button onclick="deleteRow(this)">Delete</button></td>';
        echo '<td><button onclick="editRow(this)">Edit</button></td>';
        echo '</tr>';
    }
} else {
    echo "<tr><td colspan='3'>Empty</td></tr>";
}

} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    $pdo = null;
}
?>
