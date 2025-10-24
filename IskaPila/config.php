<?php
    $mysqli = new mysqli("localhost", "root", "", "admission_records");
    if($mysqli->connect_errno){
        die("Failed to connect: " . $mysqli->connect_error);
    }

    $mysqli->set_charset("utf8mb4");
?>