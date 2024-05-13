<?php 
session_start();

// Retrieve user ID from the session
if (!isset($_SESSION["user_id"])) {
    header('Location: login.php');
    exit;
}

require_once 'includes/config_session.inc.php';
require_once 'includes/login_view.inc.php';
?>

<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="500x500" href="assets/img/Farm_tractor_black_silhouette_logo__2_-removebg-preview.png">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.reflowhq.com/v2/toolkit.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;display=swap">
    <link rel="stylesheet" href="assets/fonts/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/Billing-Table-with-Add-Row--Fixed-Header-Feature.css">
    <link rel="stylesheet" href="assets/css/Dynamic-Table.css">
    <script src="dashboard.js" defer></script>

</head>

<body>
<nav class="navbar navbar-expand-md bg-body navbar-shrink py-3 navbar-light" id="mainNav" style="padding-top: 16;height: 80px;">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <span>
                    <picture>
                        <img src="assets/img/Farm_tractor_black_silhouette_logo__2_-removebg-preview.png" width="122" height="110">
                    </picture>Tracktor
                </span>
            </a>
            
            <div class="collapse navbar-collapse flex-grow-0 order-md-first" id="navcol-1">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"></li>                    
                    <li class="nav-item"></li>
                </ul>
                <div class="d-md-none my-2"><button class="btn btn-light me-2" type="button">Button</button><button class="btn btn-primary" type="button">Button</button></div>
            </div>

            <div class="d-none d-md-block">
                <div class="dropdown">
                    <a class="dropdown-toggle" aria-expanded="false" data-bs-toggle="dropdown"><?php output_username();?>&nbsp;</a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="profile.php">Profile</a>
                        <a class="dropdown-item" href="includes/logout.inc.php">Log Out</a>
                    </div>
                </div>
            </div>
        </div>
</nav>
    

    <div>
        <a class="btn btn-primary shadow" role="button" href="products.php">Farm</a>
        <a class="btn btn-primary shadow" role="button" href="types.php">Types</a>
    </div>
    
    <form id="inventoryForm">
        <label for="productSelect">Product:</label>
        <select id="productSelect" onchange="loadProductType()">
            <!-- Options will be dynamically added here using JavaScript -->
        </select>

        <label for="productType">Product Type:</label>
        <input type="text" id="productType" readonly>

        <label for="weight">Weight:</label>
        <input type="number" id="weight">

        <label for="status">Status:</label>
        <select id="status">
            <option value="" disabled selected>Select Status</option>
            <option value="Harvested">Harvested</option>
            <option value="For Harvest">For Harvest</option>
            <option value="Planted">Planted</option>
            <option value="For Planting">For Planting</option>
            <option value="For Sale">For Sale</option>
            <option value="Sold">Sold</option>
        </select>

        <label for="expiration">Expiration:</label>
        <input type="date" id="expiration">

        <button type="button" onclick="addInventory()">Add to Inventory</button>
    </form>

    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Weight</th>
                    <th>Status</th>
                    <th>Expiration</th>
                    <th>Type</th>
                </tr>
            </thead>
            
        <tbody id="tableBody">
            <!-- Rows will be dynamically added here -->
        </tbody>
        </table>
    </div>


<div id="editInventoryForm" style="display: none;">
    <label for="editWeight">Weight:</label>
    <input type="number" id="editWeight">

    <label for="editStatus">Status:</label>
    <select id="editStatus">
        <option value="" disabled>Select Status</option>
        <option value="Harvested">Harvested</option>
        <option value="For Harvest">For Harvest</option>
        <option value="Planted">Planted</option>
        <option value="For Planting">For Planting</option>
        <option value="For Sale">For Sale</option>
        <option value="Sold">Sold</option>
    </select>

    <label for="editExpiration">Expiration:</label>
    <input type="date" id="editExpiration">

    <button type="button" onclick="saveInventoryChanges()">Save Changes</button>
    <button type="button" onclick="closeInventoryForm()">Cancel</button>
</div>


    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/bs-init.js"></script>
    <script src="assets/js/bold-and-bright.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</body>

</html>