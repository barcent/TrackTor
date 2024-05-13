<?php

declare(strict_types=1);


function output_firstname() {
    echo $_SESSION["user_firstname"];
}

function output_lastname() {
    echo $_SESSION["user_lastname"];
}

function output_username() {
    echo $_SESSION["user_username"];
}