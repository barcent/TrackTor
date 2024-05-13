<?php

declare(strict_types=1);

function get_username(object $pdo, string $username) {
    $query = "SELECT username FROM Farmers WHERE username = :username;";
    $stmt = $pdo->prepare($query);
    $stmt -> bindParam(':username', $username);
    $stmt -> execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function get_userID(object $pdo, string $username) {
    $query = "SELECT farmerID FROM Farmers WHERE username = :username;";
    $stmt = $pdo->prepare($query);
    $stmt -> bindParam(':username', $username);
    $stmt -> execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function set_user(object $pdo, string $firstname, string $lastname, string $username, string $password) {
    $query = "INSERT INTO Farmers (first_name, last_name, username, password) VALUES (:firstname, :lastname, :username, :password);";
    $stmt = $pdo->prepare($query);

    $options = [
        'cost' => 12
    ];
    $hashedPwd = password_hash($password, PASSWORD_BCRYPT, $options);


    $stmt -> bindParam(':firstname', $firstname);
    $stmt -> bindParam(':lastname', $lastname);
    $stmt -> bindParam(':username', $username);
    $stmt -> bindParam(':password', $hashedPwd);
    $stmt -> execute();
}

function set_inventory(object $pdo, int $farmerID) {
    $query = "INSERT INTO Inventories (farmerID) VALUES (:farmerID);";
    $stmt = $pdo->prepare($query);

    $stmt -> bindParam(':farmerID', $farmerID);
    $stmt -> execute();
}

function update_user(object $pdo, string $firstname, string $lastname, string $username, string $password, int $farmerID) {
    $query = "UPDATE Farmers SET first_name=:firstname, last_name=:lastname, username=:username, password=:password WHERE farmerID=:userID;";
    $stmt = $pdo->prepare($query);

    $options = [
        'cost' => 12
    ];
    $hashedPwd = password_hash($password, PASSWORD_BCRYPT, $options);

    $stmt -> bindParam(':firstname', $firstname);
    $stmt -> bindParam(':lastname', $lastname);
    $stmt -> bindParam(':username', $username);
    $stmt -> bindParam(':password', $hashedPwd);
    $stmt -> bindParam(':userID', $farmerID);
    $stmt -> execute();
}

function get_user(object $pdo, string $farmerID) {
    $query = "SELECT * FROM Farmers WHERE farmerID = :farmerID;";
    $stmt = $pdo->prepare($query);
    $stmt -> bindParam(':farmerID', $farmerID);
    $stmt -> execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

// Function for deleting all products associated with the user
function deleteProductsByUser(object $pdo, int $userID) {
    $deleteQuery = "DELETE FROM Products WHERE productID IN (SELECT productID FROM farmer_products WHERE farmerID = :userID)";

    try {
        $pdo->beginTransaction();

        // Perform the deletion
        $deleteStmt = $pdo->prepare($deleteQuery);
        $deleteStmt->bindParam(':userID', $userID);
        $deleteStmt->execute();

        $pdo->commit();

        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}

// Function for deleting all types associated with the user
function deleteTypesByUser(object $pdo, int $userID) {
    $deleteQuery = "DELETE FROM Types WHERE typeID IN (SELECT typeID FROM farmer_types WHERE farmerID = :userID)";

    try {
        $pdo->beginTransaction();

        // Perform the deletion
        $deleteStmt = $pdo->prepare($deleteQuery);
        $deleteStmt->bindParam(':userID', $userID);
        $deleteStmt->execute();

        $pdo->commit();

        return true;
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}

function delete_user(object $pdo, string $farmerID) {
    $query = "DELETE FROM Farmers WHERE farmerID = :farmerID";
    $stmt = $pdo->prepare($query);
    $stmt -> bindParam(':farmerID', $farmerID);
    $stmt -> execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function update_session(object $pdo, string $farmerID){
    $result = get_user($pdo, $farmerID);

    $_SESSION["user_firstname"] = $result["first_name"];
    $_SESSION["user_lastname"] = $result["last_name"];
    $_SESSION["user_username"] = htmlspecialchars($result["username"]);
}