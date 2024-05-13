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
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host; dbname=$dbname", $dbusername, $dbpassword);

    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Retrieve data from the database using prepared statements
    $query = "SELECT Types.typeID, Types.name
              FROM Types
              JOIN farmer_types ON Types.typeID = farmer_types.typeID
              WHERE farmer_types.farmerID = :userID";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Output data as a table
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<tr>';
            echo '<td>' . $row['name'] . '</td>';
            echo '<td><button onclick="deleteRow(this)">Delete</button></td>';
            echo '<td><button onclick="editRow(this)">Edit</button></td>';
            echo '</tr>';
        }
    } else {
        echo "<tr><td>Empty</td></tr>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Close the database connection
$pdo = null;
?>
