<?php

    try {
        require_once 'dbh.inc.php';
        require_once 'signup_model.inc.php';
        require_once 'signup_contr.inc.php';
        require_once 'config_session.inc.php';

        $farmerID = $_SESSION["user_id"];

        deleteProductsByUser($pdo, $farmerID);
        deleteTypesByUser($pdo, $farmerID);
        delete_user($pdo, $farmerID);

        header("Location: ../signup.php");

        $pdo =null;
        $stmt = null;
        
        die();

    } catch (PDOException $e) {
        die("Query Failed: " . $e->getMessage());
    }

