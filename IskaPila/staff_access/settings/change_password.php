<?php
    require_once "../../config.php";
    session_start();

    if(!isset($_SESSION["id"]) && !isset($_SESSION["username"])){
        header("Location: ../../login.php");
    }

    $query = $mysqli->prepare("SELECT * FROM staff_handler WHERE User_ID = ?");
    $query->bind_param('i', $_SESSION['id']);
    $query->execute();
    $res = $query->get_result();
    $data = $res->fetch_assoc();
    $query->close();

    if(!$data){
        die("Selection failed: " . $query->error);
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $old_pass = trim($_POST["old_pass"]??'');
        $new_pass = trim($_POST["new_pass"]??'');
        $confirm = trim($_POST["confirm_pass"]??'');

        if($old_pass != $data["Password"] || $new_pass != $confirm){
            $_SESSION["alert"] = "Incorrect old and/or confirm passwords. Please try again";
            header("Location: settings.php");
        } else if ($old_pass == $new_pass && $new_pass == $confirm){
            $_SESSION["alert"] = "Same passwords from old and new password. Please try again";
            header("Location: settings.php");
        } else {
            $find = $mysqli->prepare("UPDATE staff_handler SET Password = ? WHERE User_ID = ?");
            $find->bind_param('si', $confirm, $_SESSION["id"]);
            if($find->execute()){
                header("Location: settings.php");
            } else {
                die("Update Failed: " . $find->error);
            }
        }

        
    }

?>