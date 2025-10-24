<?php
    require_once "../../config.php";
    include "../../queue_config.php";
    session_start();

    if(!isset($_SESSION["id"]) && !isset($_SESSION["username"])){
        header("Location: ../../login.php");
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $manip_size = trim($_POST["manip_size"]??'50');
        if($queue_data->MAX_QUEUE == $manip_size){
            $_SESSION["alert"] = "The new credentials is still same as old credentials";
            header("Location: settings.php?warn");
        } else {
            date_default_timezone_set("Asia/Manila");
            // preparing SQL query to update changes and save shanges to changelogs table
            $ref = rand(10000, 99999); $current_date = date("d/m/y h:i:sa");
            $statement = $mysqli->prepare("INSERT INTO queue_defaults(IDX, madeby, max_queue, Type, DESK1, DESK2, DESK3, REF, DATETIME) VALUES (?,?,?,?,?,?,?,?,?)");
            $index = $queue_data->IDX + 1; $TYPE = "C";

            // combining array values to store data to database
            $d1 = implode(" ", $queue_data->DESK1); 
            $d2 = implode(" ", $queue_data->DESK2); 
            $d3 = implode(" ", $queue_data->DESK3);
            
            $statement->bind_param('isissssis', $index, $_SESSION["username"], $manip_size, $TYPE, $d1, $d2, $d3, $ref, $current_date);
            if($statement->execute()){
                header("Location: settings.php");
            } else {
                die("Update failed: " . $statement->error);
            }
    }
        }
        
        

?>