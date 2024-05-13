<?php

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST["uname"];
    $password = $_POST["pwd"];

    try {
        require_once 'dbh.inc.php';
        require_once 'login_model.inc.php';
        require_once 'login_contr.inc.php';

        // ERROR HANDLERS

        $errors = [];

        if (is_input_empty($username, $password)){
            $errors["empty_input"] = "Fill in all fields!";
        }

        $result = get_user($pdo, $username);

        if (is_username_wrong($result)){
            $errors["login_incorrect"] = "User does not exist!";
        }

        if (!is_username_wrong($result) && is_password_wrong($password, $result["password"])){
            $errors["login_incorrect"] = "Incorrect Login Info!";
        }

        require_once 'config_session.inc.php';

        if($errors) {
            $_SESSION["errors_login"] = $errors;
            header("Location: ../login.php");
            die();
        }

        $newSessionId = session_create_id();
        $sessionId = $newSessionId . "_" . $result["farmerID"];
        session_id($sessionId);

        $_SESSION["user_id"] = $result["farmerID"];
        $_SESSION["user_username"] = htmlspecialchars($result["username"]);
        $_SESSION["user_firstname"] = $result["first_name"];
        $_SESSION["user_lastname"] = $result["last_name"];

        $_SESSION["last_regeneration"] = time();

        header("Location: ../index.php");
        $pdo=null;
        $statement = null;

        die();
    } catch (PDOException $e) {
        die("Query Failed: " . $e->getMessage());
    }
} else {
    header("location: ../login.php");
    die();
}