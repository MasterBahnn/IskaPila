<?php
    require_once "../../config.php";
    session_start();

    if(!isset($_SESSION["id"]) && !isset($_SESSION["username"])){
        header("Location: ../../login.php");
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $delete_id = $_SESSION["id"];

        if($delete_id >= 1001 && $delete_id <= 1010){
            $_SESSION["alert"] = "Cannot delete main account";
            header("Location: settings.php?warn");
        } else {
            $delete = $mysqli->prepare("DELETE FROM staff_handler WHERE User_ID = ?");
            $delete->bind_param('i', $delete_id);
            if($delete->execute()){
                unset($_SESSION['id'], $_SESSION["username"], $_SESSION["email"]);
                header("Location: ../../login.php");
            } else {
                die("Deletion failed: " . $delete->error);
            }
        }
    }

?>