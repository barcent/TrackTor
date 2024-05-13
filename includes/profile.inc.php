<?php

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname = $_POST["fname"];
    $lastname = $_POST["lname"];
    $username = $_POST["uname"];
    $password = $_POST["pwd"];

    try {
        require_once 'dbh.inc.php';
        require_once 'signup_model.inc.php';
        require_once 'signup_contr.inc.php';

        // ERROR HANDLERS
        $errors = [];

        if (is_input_empty($firstname, $lastname, $username, $password)){
            header("Location: ../profile.php?error=incomplete");
            die();
        }

        if (is_username_taken($pdo,$username)){
            header("Location: ../profile.php?error=username_taken");
            die();
        }

        require_once 'config_session.inc.php';

        $farmerID = $_SESSION["user_id"];

        update_user($pdo, $firstname, $lastname, $username, $password, $farmerID);
        update_session($pdo,$farmerID);

        header("Location: ../profile.php?editprofile=success");

        $pdo =null;
        $stmt = null;
        
        die();

    } catch (PDOException $e) {
        die("Query Failed: " . $e->getMessage());
    }
} else {
    header("location: ../profile.php");
    die();
}
