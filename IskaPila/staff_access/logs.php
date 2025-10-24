<?php
    include "../config.php";
    session_start();
    
    if(!isset($_SESSION["id"]) && !isset($_SESSION["username"])){
        header("Location: ../login.php");
    }

    if(isset($_SESSION["alert"])){
        $alertmsg = $_SESSION["alert"];
        echo "<script> alert('{$alertmsg}'); </script>";
        unset($_SESSION["alert"]);
    }

    $res = $mysqli->query("SELECT * FROM queuelogs ORDER by POS ASC");


?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="staff.css">
        <title>Queue List</title>
        <link rel="icon" type="image/x-icon" href="../web_favi.png">
        <style>
            #logcount{
                text-align: center;
            }
        </style>
    </head>
    <body>
        <ul class="hdrr">
            <li><img src="../menu.png" width="60px" height="50px"></li>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="list.php">Queue List</a></li>
            <li><a href="logs.php">Queue Logs</a></li>
            <li><a href="settings/settings.php">Settings</a></li>
            <li><a href="help.php">Help</a></li>
        </ul>
        <br>
        <br>
        <p id="logcount">Queue log count: <?= htmlspecialchars($res->num_rows??0); ?> </p>
        <table align="center" class="li1" border="1">
            <tr>
                <th colspan="12" class="hdrr">QUEUE LOGS LIST</th>
            </tr>
            
            <?php
            // prints all queue logs if available
            if($res->num_rows > 0){
                echo " <tr>
                    <th>POS</th>
                    <th>Name</th>
                    <th>Concern</th>
                    <th width=\"200px\">Email</th>
                    <th width=\"200px\">Notes</th>
                    <th>Submission Date</th>
                    <th>Code</th>
                    <th>REF NO</th>
                    <th>Called by</th>
                    <th>REC_NO</th>
                    <th>date_time</th>
                </tr>
                ";

                while($row = $res->fetch_assoc()){
                    $pos = htmlspecialchars($row["POS"]??'');
                    $Name = htmlspecialchars($row["Name"]??'');
                    $Concern = htmlspecialchars($row["Concern"]??'');
                    $Notes = htmlspecialchars($row["Notes"]??'');
                    $Email = htmlspecialchars($row["Email"]??'');
                    $submission_date = htmlspecialchars($row["Submission_date"]??'');
                    $type = htmlspecialchars($row["Type"]??'');
                    $id = htmlspecialchars($row["ID"]??''); 
                    $Ref_no = htmlspecialchars($row["REF"]??'');
                    $called_by = htmlspecialchars($row["called_by"]??'');
                    $rec_no = htmlspecialchars($row["REC_NO"]??'');
                    $date_time = htmlspecialchars($row["datetime"]??'');
                    
                    echo " <tr>
                        <td>{$pos}</td>
                        <td>{$Name}</td>
                        <td>{$Concern}</td>
                        <td>{$Email}</td>
                        <td>{$Notes}</td>
                        <td>{$submission_date}</td>
                        <td>{$type}-{$id}</td>
                        <td>{$Ref_no}</td>
                        <td>{$called_by}</td>
                        <td>{$rec_no}</td>
                        <td>{$date_time}</td>
                    </tr>
                    ";
                }
            } else {
                echo " <tr>
                    <td colspan=\"12\">No queue order records found</td>
                </tr>
                ";
            } echo ""
            ?>
        </table>
        <br>
        
    </body>
</html>