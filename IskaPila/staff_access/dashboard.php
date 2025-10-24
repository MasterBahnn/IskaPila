<?php
    require_once "../config.php";
    include "../get_id.php";
    include "../queue_config.php";
    session_start();

    // checks if the user is loggeed
    if(!isset($_SESSION["id"]) && !isset($_SESSION["username"])){
        header("Location: ../login.php");
    }

    $USER_ID = htmlspecialchars($_SESSION["id"]);
    $USERNAME = htmlspecialchars($_SESSION["username"]);

    // alert message handler
    if(isset($_SESSION["alert"])){
        $alertmsg = $_SESSION["alert"];
        echo "<script> alert('{$alertmsg}'); </script>";
        unset($_SESSION["alert"]);
    }

    // fucntion to load data from the database
    function load_data($DATA){
        global $mysqli;
        $stmt = $mysqli->prepare("SELECT * FROM queue_orders WHERE Type = ? AND ID = ?");
        $stmt->bind_param('si', $DATA[0], $DATA[1]);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }


    // call the front queue (also dequeued from the queue)
    // and record the previous data to the queue_logs
    function call_front($DATA, $tab){ 
        global $sub, $mysqli, $queue_data;

        if($sub == 0){
            $_SESSION["alert"] = "Calling while the queue is empty.";
            return;
        }  
   
        $queue = load_data($DATA);
        date_default_timezone_set("Asia/Manila");
        $current_date = date("d/m/y h:i:sa");
        $rec_no = rand(10000, 99999);
        
        $size = $mysqli->query("SELECT * FROM queuelogs ORDER by POS DESC");
        $position = $size->num_rows + 1;

        // SQL query to insert data to queue logs
        $query1 = $mysqli->prepare("INSERT INTO queuelogs(POS, Name, Concern, Notes, Email, Submission_date, Type, ID, REF, called_by, REC_NO, datetime) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        $query1->bind_param('issssssiisis', $position, $queue["Name"], $queue["Concern"], $queue["Notes"], $queue["Email"], $queue["Submission_date"], $queue["Type"], $queue["ID"], $queue["REF"], $_SESSION["username"], $rec_no, $current_date);
        
        // SQL query to insert and updata current data for future use in storage database
        $ref = rand(10000, 99999);
        $query2 = $mysqli->prepare("INSERT INTO queue_defaults(IDX, madeby, max_queue, Type, DESK1, DESK2, DESK3, REF, DATETIME) VALUES (?,?,?,?,?,?,?,?,?)");
        $index = $queue_data->IDX + 1; $TYPE = "B"; 

        // combining array values to store data to database
        $d1 = implode(" ", $queue_data->DESK1); 
        $d2 = implode(" ", $queue_data->DESK2); 
        $d3 = implode(" ", $queue_data->DESK3); 
        $ps = implode(" ", $DATA);

        if($tab == 1 && $queue_data->DESK1 == explode(" ", $queue_data->DEF_VAL)) {
            $query2->bind_param('isissssis', $index, $_SESSION["username"], $queue_data->MAX_QUEUE, $TYPE, $ps, $d2, $d3, $ref, $current_date);
        } else if( $tab == 2 &&$queue_data->DESK2 == explode(" ", $queue_data->DEF_VAL)) {;
            $query2->bind_param('isissssis', $index, $_SESSION["username"], $queue_data->MAX_QUEUE, $TYPE, $d1, $ps, $d3, $ref, $current_date);
        } else if( $tab == 3 &&$queue_data->DESK3 == explode(" ", $queue_data->DEF_VAL)) {
            $query2->bind_param('isissssis', $index, $_SESSION["username"], $queue_data->MAX_QUEUE, $TYPE, $d1, $d2, $ps, $ref, $current_date);  
        } else {
            $_SESSION["alert"] = "Calling while the desk is not empty"; 
            return;
        }

        // SQL query to delete queue edata to the order (dequeue)
        $query3 = $mysqli->prepare("DELETE FROM queue_orders WHERE REF = ?");
        $query3->bind_param('i', $queue["REF"]);


        if(!$query1->execute() || !$query2->execute() || !$query3->execute()){
            $_SESSION["alert"] = "alert('Failed to execute, {$query1->error}";
            return;
        }
    }

    // call stop / drop the called queue data
    function call_stop(&$DATA, $tab){
        global $mysqli, $queue_data;
        if($DATA[1] == 0){
            $_SESSION["alert"] = "Dropping while empty. Call front to drop the data.";
            return;
        } 

        date_default_timezone_set("Asia/Manila");
        $current_date = date("d/m/y h:i:sa");

        // SQL query to insert and update current data for future use in storage database
        $ref = rand(10000, 99999);
        $query2 = $mysqli->prepare("INSERT INTO queue_defaults (IDX, madeby, max_queue, Type, DESK1, DESK2, DESK3, REF, DATETIME) VALUES (?,?,?,?,?,?,?,?,?)");
        $index = $queue_data->IDX + 1; $TYPE = "B";

        // combining array values to store data to database
        $d1 = implode(" ", $queue_data->DESK1); 
        $d2 = implode(" ", $queue_data->DESK2); 
        $d3 = implode(" ", $queue_data->DESK3);

        switch($tab){
            case 1: $query2->bind_param('isissssis', $index, $_SESSION["username"], $queue_data->MAX_QUEUE, $TYPE, $queue_data->DEF_VAL, $d2, $d3, $ref, $current_date); break;
            case 2: $query2->bind_param('isissssis', $index, $_SESSION["username"], $queue_data->MAX_QUEUE, $TYPE, $d1, $queue_data->DEF_VAL, $d3, $ref, $current_date); break;
            case 3: $query2->bind_param('isissssis', $index, $_SESSION["username"], $queue_data->MAX_QUEUE, $TYPE, $d1, $d2, $queue_data->DEF_VAL, $ref, $current_date); break;
        } 

        if(!$query2->execute()){
            $_SESSION["alert"] = "Failed to execute, {$query2->error}";
            return;
        }
        
    }

    function display_data($DATA){
        global $mysqli;
        $stmt = $mysqli->prepare("SELECT * FROM queuelogs WHERE Type = ? AND ID = ?");
        $stmt->bind_param('si', $DATA[0], $DATA[1]);
        $stmt->execute();
        $res = $stmt->get_result();
        $data =  $res->fetch_assoc();
        if($data){   
            $Name = htmlspecialchars($data["Name"]);
            $Concern = htmlspecialchars($data["Concern"]);
            $Notes = htmlspecialchars($data["Notes"]);
            $submission_date = htmlspecialchars($data["Submission_date"]);
            $ref = htmlspecialchars($data["REF"]);
                
            echo " <table id=\"t2\" align=\"center\">
                    <tr>
                        <td> Name: {$Name} </td>  
                    </tr>
                    <tr>
                        <td> Concern: {$Concern}  </td>
                    </tr>
                    <tr>
                        <td id=\"t2\"> Notes: <br>
                        {$Notes}
                        </td>
                    </tr>
                     <tr>
                        <td colspan=\"2\"> Submission date: {$submission_date} <br>
                        Reference no: {$ref}
                        </td>
                    </tr>
                </table> ";
        } else {
            echo " <table id=\"t2\" align=\"center\">
                    <tr>
                        <td> Call front to display queue data </td>  
                    </tr>
                </table> ";
        }
    }

    // posting response to here for queue management
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $resp = array(
            array(trim($_POST["r1"]??''), trim($_POST["sn1"]??'')),
            array(trim( $_POST["r2"]??''), trim($_POST["sn2"]??'')),
            array(trim($_POST["r3"]??''), trim($_POST["sn3"]??''))
        );


        if(isset($_POST["callfront"])){
            switch($_POST["callfront"]){
                case "cf1":{ 
                    call_front($FRONT, 1);
                    break;
                } case "cf2":{
                    call_front($FRONT, 2);
                    break;
                } case "cf3":{
                    call_front($FRONT, 3);
                    break;
                }
            } 
        }
        
        else if(isset($_POST["callstop"])){
            switch($_POST["callstop"]){
                case "cs1":{   
                    call_stop($queue_data->DESK1, 1);
                    break;
                } case "cs2":{
                    call_stop($queue_data->DESK2, 2);
                    break;
                } case "cs3":{
                    call_stop($queue_data->DESK3, 3);
                    break;
                }
            }
        }
        
        header("Location: dashboard.php?msg-manipulated");
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="refresh" content="20">
        <link rel="stylesheet" href="staff.css">
        <title>Dashboard</title>
        <link rel="icon" type="image/x-icon" href="../web_favi.png">
        <style>
            textarea{
                resize: none;
                width: 200px;
                height: 60px;
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
        <h2 id="welcome"> Welcome, <?= $USERNAME?>!</h2>
        
        <br>

        <form action="dashboard.php" method="post">
        <table align="center">
            <tr>
                <td>
                    <table class="bview">
                        <tr>
                            <td> DESK 1 </td>
                        </tr>
                        <tr>
                            <td id="nc"><?php echo $queue_data->DESK1[0] . "-" . $queue_data->DESK1[1] ?></td>
                        </tr>
                        <tr>
                            <td><?php display_data($queue_data->DESK1)?></td>
                        </tr>
                        <tr>
                            <td> <button type="submit" name="callfront" value="cf1">Call Front</button>
                            <button type="submit" name="callstop" value="cs1">Drop</button>
                            </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="bview">
                        <tr>
                            <td> DESK 2 </td>
                        </tr>
                        <tr>
                            <td id="nc"><?php echo $queue_data->DESK2[0] . "-" . $queue_data->DESK2[1] ?></td>
                        </tr>
                        <tr>
                            <td><?php display_data($queue_data->DESK2)?></td>
                        </tr>
                        <tr> 
                            <td> <button type="submit" name="callfront" value="cf2">Call Front</button>
                            <button type="submit"  name="callstop" value="cs2">Drop</button>
                            </td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="bview">
                        <tr>
                            <td> DESK 3 </td>
                        </tr>
                        <tr>
                            <td id="nc"><?php echo $queue_data->DESK3[0] . "-" . $queue_data->DESK3[1] ?></td>
                        </tr>
                        <tr>
                            <td><?php display_data($queue_data->DESK3)?></td>
                        </tr>
                        <tr>
                            <td> <button type="submit"  name="callfront" value="cf3">Call Front</button>
                            <button type="submit" name="callstop" value="cs3">Drop</button>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        </form>
 
        <table align="center">
            <tr>
                <td> <table class="bview1" border="1">
                    <tr>
                        <td>FRONT QUEUE</td>
                        <td>BACK QUEUE</td>
                    </tr>
                    <tr>
                        <td id="nc"><?php echo $FRONT[0] . "-" . $FRONT[1]; ?></td>
                        <td id="nc"><?php echo $BACK[0] . "-" . $BACK[1]; ?></td>
                        </tr>
                </table> </td>
                <td> <table class="bview1" border="1">
                    <tr>
                        <td colspan="6">QUEUE TYPE COUNT</td>
                    </tr>
                    <tr>
                        <td>A</td>
                        <td>B</td>
                        <td>C</td>
                        <td>D</td>
                        <td>E</td>
                        <td>F</td>
                    </tr>
                    <tr>
                        <td id="nc"><?= $count[0]?></td>
                        <td id="nc"><?= $count[1]?></td>
                        <td id="nc"><?= $count[2]?></td>
                        <td id="nc"><?= $count[3]?></td>
                        <td id="nc"><?= $count[4]?></td>
                        <td id="nc"><?= $count[5]?></td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            QUEUE COUNT: <?= $sub ?> / <?= $queue_data->MAX_QUEUE ?>
                        </td>
                    </tr>
                </table> </td>
            </tr>
        </table>
        
    </body>
</html>
