<?php
    require_once "../../config.php";
    session_start();

    if(!isset($_SESSION["id"]) && !isset($_SESSION["username"])){
        header("Location: ../../login.php");
    }

    unset($_SESSION["id"], $_SESSION["username"], $_SESSION["email"]);
    header("Location: ../../login.php?msg-loggedout");
?>