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

    // Retrieve the user ID from the session
    $userID = $_SESSION["user_id"];

    // SQL query to get products and their types for the current user
    $sql = "
        SELECT Products.productID, Products.name AS productName, Types.name AS typeName
        FROM Products
        JOIN farmer_products ON Products.productID = farmer_products.productID
        JOIN Types ON Products.typeID = Types.typeID
        WHERE farmer_products.farmerID = :userID
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt->execute();

    // Check if there are rows
    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . $row['productName'] . '</td>';
            echo '<td>' . $row['typeName'] . '</td>';
            echo '<td><button onclick="deleteRow(this)">Delete</button></td>';
            echo '<td><button onclick="editRow(this)">Edit</button></td>';
            echo '</tr>';
        }
    } else {
        echo "<tr><td colspan='3'>Empty</td></tr>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the database connection
$conn = null;
?>
