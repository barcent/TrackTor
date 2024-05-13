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
            $errors["empty_input"] = "Fill in all fields!";
        }

        if (is_username_taken($pdo,$username)){
            $errors["username_taken"] = "Username already taken!";
        }

        require_once 'config_session.inc.php';

        if($errors) {
            $_SESSION["errors_signup"] = $errors;
            header("Location: ../signup.php?signup=error");
            die();
        }
        
        create_user($pdo, $firstname, $lastname, $username, $password);
        
        $result = get_userID($pdo, $username);
        set_inventory($pdo, $result["farmerID"]); 

        header("Location: ../login.php");

        $pdo =null;
        $stmt = null;
        
        die();

    } catch (PDOException $e) {
        die("Query Failed: " . $e->getMessage());
    }
} else {
    header("location: ../signup.php");
    die();
}