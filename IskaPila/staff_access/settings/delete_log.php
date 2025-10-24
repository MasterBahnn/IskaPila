<?php
    require_once "../../config.php";
    session_start();

    if(!isset($_SESSION["id"]) && !isset($_SESSION["username"])){
        header("Location: ../../login.php");
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        if(isset($_POST["delete_changelog"])){
            $csize = $mysqli->query("SELECT * FROM queue_defaults ORDER by IDX ASC");

            if($csize->num_rows == 0){
                $_SESSION["alert"] = "The queue log is already empty";
                header("Location: settings.php?warn");
            } else {
                $dclogs = $mysqli->prepare("TRUNCATE TABLE queue_defaults");
                if($dclogs->execute()){
                    $_SESSION["alert"] = "All change logs are deleted";
                } else {
                    $_SESSION["alert"] = "Failed to delete" . $delete->error;
                }
            }

        }

        if(isset($_POST["delete_queuelog"])){
            $qsize = $mysqli->query("SELECT * FROM queuelogs ORDER by POS ASC");
            if($qsize->num_rows == 0){
                $_SESSION["alert"] = "The queue log is already empty";
                header("Location: settings.php?warn");
            } else {
                $dqlogs = $mysqli->prepare("TRUNCATE TABLE queuelogs");
                if($dqlogs->execute()){
                    $_SESSION["alert"] = "All queue logs are deleted";
                } else {
                    $_SESSION["alert"] = "Failed to delete" . $delete->error;
                }
            }
            
        }
    }

    header("Location: settings.php");
?>