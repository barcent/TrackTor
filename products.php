<?php
session_start();

// Ensure that the user is logged in
if (!isset($_SESSION["user_id"])) {
    header('location: login.php');
    exit;
}

require_once 'includes/config_session.inc.php';            
require_once 'includes/profile_view.inc.php';


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tracktordb";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    session_start();
    $userID = $_SESSION["user_id"]; 

    // Retrieve Options from Database for the specific user
    $stmt = $conn->prepare("
        SELECT Types.typeID, Types.name
        FROM Types
        JOIN farmer_types ON Types.typeID = farmer_types.typeID
        WHERE farmer_types.farmerID = :userID
    ");
    $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
    $stmt->execute();
    $options = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Close the database connection
    $conn = null;
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Options from MySQL</title>
    <script src="products.js" defer></script>
</head>

<body>

<form id="productForm">
  <label for="productName">Product Name:</label>
  <input type="text" id="productName" onkeydown="return /[a-zA-Z]/i.test(event.key)" required>

  <label for="productType">Product Type:</label>
  <select id="mySelect">
        <option value="" disabled selected>Select Types</option>
        <?php foreach ($options as $option): ?>
            <option value="<?= $option['typeID'] ?>"><?= $option['name'] ?></option>
        <?php endforeach; ?>
    </select>

  <button type="button" onclick="addProduct()">Add Product</button>
</form>

<table>
    <thead>
        <tr>
            <th>Product</th>
            <th>Type</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody id="tableBody">
        <!-- Rows will be dynamically added here -->
    </tbody>
</table>

<div id="editForm" style="display: none;">
  <label for="newProductName">Enter a new name for Product:</label>
  <input type="text" id="newProductName" onkeydown="return /[a-zA-Z]/i.test(event.key)">
  
  <label for="newProductType">Select a new type for Product:</label>
  <select id="newProductType">
    <?php foreach ($options as $option): ?>
      <option value="<?= $option['typeID'] ?>"><?= $option['name'] ?></option>
    <?php endforeach; ?>
  </select>

  <button onclick="saveChanges()">Save</button>
  <button onclick="closeForm()">Cancel</button>
</div>


</body>
</html>
