<?php
    require_once "../../config.php";
    session_start();

    if(!isset($_SESSION["id"]) && !isset($_SESSION["username"])){
        header("Location: ../../login.php");
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $manip_user = trim($_POST['manip_name']??'');
        $manip_email = trim($_POST['manip_email']??'');

        if($manip_user == $_SESSION["username"] && $manip_email == $_SESSION["email"]){
            $_SESSION["alert"] = "The new credentials is still same as old credentials";
            header("Location: settings.php?warn");
        } else{
            $find = $mysqli->prepare("UPDATE staff_handler SET Username = ?, Email = ? WHERE User_ID = ?");
            $find->bind_param('ssi', $manip_user, $manip_email, $_SESSION["id"]);
            if($find->execute()){
                $_SESSION["username"] = $manip_user;
                $_SESSION["email"] = $manip_email;
                header("Location: settings.php?edited");
            } else {
                die("Update Failed: " . $find->error);
            }
        }

        
    }
    
?>