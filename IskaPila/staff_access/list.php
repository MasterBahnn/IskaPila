<?php
    include "../config.php";
    include "../get_id.php";
    include "../queue_config.php";
    session_start();

    if(!isset($_SESSION["id"]) && !isset($_SESSION["username"])){
        header("Location: ../login.php");
    }

    // assigning cleaned values to prevent XSS
    $USER_ID = htmlspecialchars($_SESSION["id"]);
    $USERNAME = htmlspecialchars($_SESSION["username"]);

    // to notified if it has errors or operations
    if(isset($_SESSION["alert"])){
        $alertmsg = $_SESSION["alert"];
        echo "<script> alert('{$alertmsg}'); </script>";
        unset($_SESSION["alert"]);
    }

    // fucntion to dequeue all values
    function dequeue_all(){
        global $sub, $mysqli, $_SESSION, $queue_data; // global variables to be used
        if($sub == 0){ // if the list is already empty
            $_SESSION["alert"] = "The list is already empty";
        } else {

            // SQL query to record current data for storage database
            date_default_timezone_set("Asia/Manila");
            $ref = rand(10000, 99999); $current_date = date("d/m/y h:i:sa");
            $record = $mysqli->prepare("INSERT INTO queue_defaults(IDX, madeby, max_queue, Type, DESK1, DESK2, DESK3, REF, DATETIME) VALUES (?,?,?,?,?,?,?,?,?)");
            $index = $queue_data->IDX + 1; $TYPE = "D"; 

            // combining array values to store data to database
            $d1 = implode(" ", $queue_data->DESK1); 
            $d2 = implode(" ", $queue_data->DESK2); 
            $d3 = implode(" ", $queue_data->DESK3); 

            $record->bind_param('isissssis', $index, $_SESSION["username"], $queue_data->MAX_QUEUE, $TYPE, $d1, $d2, $d3, $ref, $current_date);
            

            $delete = $mysqli->prepare("TRUNCATE TABLE queue_orders");
            if($delete->execute() && $record->execute()){
                $_SESSION["alert"] = "All items are dequeued";
            } else {
                $_SESSION["alert"] = "Failed to delete" . $delete->error;
            }
        }

       
    }

    // used as handler for searching values
    $s_type = $s_id = $s_ref = $compare = NULL;

    //  sends data to the database
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if(isset($_POST["enqueue"])){
            if($sub >= $queue_data->MAX_QUEUE){ 
                $_SESSION["alert"] = "Queue is already full. Please try again later";
                header("Location: list.php?msg-error");
            } else{
                date_default_timezone_set("Asia/Manila");

                $Name = trim($_POST['name']??'');
                $Concern = trim($_POST["concern"]??'');
                $Notes = trim($_POST["notes"]??'none');
                $Email = trim($_POST["email"]??'none');
                $submission_date = date("d/m/y h:i:sa");
                type_finder($Concern, $type, $id, $pos);
                $Ref_no = rand(10000, 99999);
                $current_date = date("d/m/y h:i:sa");
                
                    
                // preparing sql on inserting values to database
                $submit = $mysqli->prepare("INSERT INTO queue_orders(pos, Name, Concern, Notes, Email, Submission_date, Type, ID, REF) VALUES (?,?,?,?,?,?,?,?,?)");
                $submit->bind_param('issssssii', $pos, $Name, $Concern, $Notes, $Email, $submission_date, $type, $id, $Ref_no);
                if(!$submit->execute()){
                    $_SESSION["alert"] = "Insert Failed: {$stmt->error}";
                }
            }
            

            header("Location: list.php");
        } else if(isset($_POST["truncate"])){
            dequeue_all();
        } else if (isset($_POST["search"])){
            $compare = trim($_POST["value"]??'');
        }
    }

    
?>
<!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <link rel="stylesheet" href="staff.css">
            <title>Queue List</title>
            <link rel="icon" type="image/x-icon" href="../web_favi.png">
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

            <table align="center" class="li1" border="1" >
                <tr>
                    <th colspan="9" class="hdrr">QUEUE ORDER LIST</th>
                </tr>
            
            <?php

            $res = $mysqli->query("SELECT * FROM queue_orders ORDER by POS ASC");
            if($res->num_rows > 0){
                echo " <tr>
                    <th>INDEX</th>
                    <th>PREV POS</th>
                    <th>Name</th>
                    <th>Concern</th>
                    <th width=\"300px\">Email</th>
                    <th width=\"300px\">Notes</th>
                    <th>Submission Date</th>
                    <th>Code</th>
                    <th>REF NO</th>
                </tr>
                ";
                $sub = 0;
                while($row = $res->fetch_assoc()){
                    $sub++;
                    $pos = htmlspecialchars($row["POS"]);
                    $Name = htmlspecialchars($row["Name"]);
                    $Concern = htmlspecialchars($row["Concern"]);
                    $Notes = htmlspecialchars($row["Notes"]);
                    $Email = htmlspecialchars($row["Email"]);
                    $submission_date = htmlspecialchars($row["Submission_date"]);
                    $type = htmlspecialchars($row["Type"]);
                    $id = htmlspecialchars($row["ID"]); 
                    $Ref_no = htmlspecialchars($row["REF"]);

                    if($compare == $Name || $compare == $Email){
                        $s_type = htmlspecialchars($row["Type"]);
                        $s_id = htmlspecialchars($row["ID"]);
                        $s_ref = htmlspecialchars($row["REF"]);
                    }
                    
                    
                    echo " <tr>
                        <td>{$sub}</td>
                        <td>{$pos}</td>
                        <td>{$Name}</td>
                        <td>{$Concern}</td>
                        <td>{$Email}</td>
                        <td>{$Notes}</td>
                        <td>{$submission_date}</td>
                        <td>{$type}-{$id}</td>
                        <td>{$Ref_no}</td>
                    </tr>
                    ";
                }
            } else {
                echo " <tr>
                    <td colspan=\"8\">No queue order records found</td>
                </tr>
                ";
            }
            ?>
            </table>
            <br>
            <table align="center">
                <tr>
                    <td> <form action="list.php" method="post">
                    <table align="center" class="info">
                        <tr>
                            <td colspan="2" align="center">
                                <h2>MANUAL ENQUEUE</h2>
                                    </td>
                        </tr>
                        <tr>
                            <td><span id="imp">*</span>Name</td>
                            <td><input type="text" name="name" max="30" required></td>
                        </tr>
                        <tr>
                            <td><span id="imp">*</span>Concern</td>
                            <td>
                                <select name="concern" required>
                                    <option value="Enrollment">Enrollment</option>
                                    <option value="Non-issuance ID">Non-issuance ID</option>
                                    <option value="CTC of COR">CTC of COR</option>
                                    <option value="Scholarship">Scholarship</option>
                                    <option value="TOR / Diploma">TOR / Diploma</option>
                                    <option value="Others">Others</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><span id="imp">*</span>Email</td>
                            <td><input type="email" name="email" placeholder="johndoe@outlook.com" max="60" required></td>
                        </tr>
                            <tr>
                                <td colspan="2" align="center">
                                    Notes <br>
                                    <textarea name="notes" max="100"> </textarea> 
                                </td>
                            </tr>
                        <tr>
                            <td colspan="2" align="center">
                                <button type="submit" name="enqueue" value="enqueue">Enqueue</button>
                                <button type="clear" onclick="return confirm('Are you want to clear existing values?');">Clear Values</button>
                            </td>
                        </tr>
                    </table></form>
                    </td>
                    <td>
                    <table class="bview">
                        <tr>
                            <td> <form action="list.php" method="post">
                                <button type="submit" name="truncate" value="truncate" onclick="return confirm('Are you want to empty all list values?');">Dequeue all</button></a> <br>
                                </form>
                            </td>
                        </tr>
                    </table>
                    <table class="bview">
                        <tr>
                            <td> Search here <br>
                            <form action="list.php" method="post">
                                <textarea name="value" style="resize: none; width: 170px; height: 40px;"> </textarea> <br>
                                <button type="submit" name="search" value="search">Search</button>
                            </form>
                            </td>
                        </tr>
                        <?php if($s_ref != NULL){
                            $placement = find_index($s_type, $s_id);
                            
                            echo "<tr> <td>
                            ITEM FOUND AT: <br>
                            Placement: " . $placement . "<br>" .
                            "Code: " . $s_type . "-" . $s_id . " <br>
                            REF: " . $s_ref .
                            "</td> </tr>";

                        } else {
                            echo "<tr> <td>
                            Search an item here
                            </td> </tr>";
                        }
                        
                        ?>
                    </table>
                </td>
                </tr>
            </table>


        </body>
    </html>