<?php
    require_once "../../config.php";
    session_start();

    if(!isset($_SESSION["id"]) && !isset($_SESSION["username"])){
        header("Location: ../../login.php");
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $new_user = trim($_POST["new_user"]??'');
        $new_email = trim($_POST["new_email"]??'');
        $new_pass = trim($_POST["new_pass"]??'');
        $confirm_pass = trim($_POST["confirm_pass"]??'');
        $new_id = rand(1011, 9999);

        if($new_pass != $confirm_pass){
            $_SESSION["alert"] = "Incorrect confirm password";
            header("Location: settings.php?msg-manipulated");
        } else{
            $create_account = $mysqli->prepare("INSERT INTO staff_handler(User_ID, Username, Email, Password) VALUES(?,?,?,?)");
            $create_account->bind_param('isss', $new_id, $new_user, $new_email, $new_pass);
            if($create_account->execute()){
                $_SESSION["alert"] = "The account is added in the database";
            } else {
                die("Addition failed: " . $create_account->error);
            }
        }

        

    }

    header("Location: settings.php");
?>