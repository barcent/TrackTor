<?php

declare(strict_types=1);

function output_username() {
    if (isset($_SESSION["user_id"])) {
        echo $_SESSION["user_username"];
    } 
}

function check_login_errors() {
    if(isset($_SESSION['errors_login'])) {
        $errors = $_SESSION['errors_login'];
        
        foreach($errors as $error) {
            echo '<p>' . $error . '</p>';
        }

        unset($_SESSION['errors_login']);
    } else if (isset($_GET["login"]) && isset($_GET["login"]) === "success") {
        echo '<br>';
        echo '<p class="text-muted">Login success!</p>';
    }
}
