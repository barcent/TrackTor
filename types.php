<?php 
session_start();

if (!isset($_SESSION["user_id"])) {
    header('location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html data-bs-theme="light" lang="en">
<head>     
    <script src="types.js" defer></script>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody id="tableBody">
            <!-- Rows will be dynamically added here -->
        </tbody>
    </table>

    <input type="text" id="typeInput" onkeydown="return /[a-zA-Z]/i.test(event.key)" placeholder="Enter Type">
    <button onclick="addRow()">Add Row</button>
</body>

</html>
