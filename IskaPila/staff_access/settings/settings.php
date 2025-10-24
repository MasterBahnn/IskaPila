<?php
    require_once "../../config.php";
    require_once "../../queue_config.php";
    session_start();

    if(!isset($_SESSION["id"]) && !isset($_SESSION["username"])){
        header("Location: ../../login.php");
    }

    if(isset($_SESSION["alert"])){
        $alertmsg = $_SESSION["alert"];
        echo "<script> alert('{$alertmsg}'); </script>";
        unset($_SESSION["alert"]);
    }


?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../staff.css">
        <title>Settings</title>
        <link rel="icon" type="image/x-icon" href="../../web_favi.png">
        <style>
            #staff{
                width: 250px;
            }
            #acct{
                width: 90px;
            }

        </style>
    </head>
    <body>
        <ul class="hdrr">
            <li><img src="../../menu.png" width="60px" height="50px"></li>
            <li><a href="../dashboard.php">Dashboard</a></li>
            <li><a href="../list.php">Queue List</a></li>
            <li><a href="../logs.php">Queue Logs</a></li>
            <li><a href="settings.php">Settings</a></li>
            <li><a href="../help.php">Help</a></li>
        </ul>
        <p style="margin-left: 30px; font-size: 14px; text-align: left; margin-bottom: 50px"> 
            ID: <?= htmlspecialchars($_SESSION["id"])?><br>
            Username: <?= htmlspecialchars($_SESSION["username"])?><br>
            <?php if($_SESSION["id"] >= 1001 && $_SESSION["id"]  <= 1010) echo "ADMINISTRATIVE ACCOUNT";?>
        </p>

        <table align="center" class="settings" border="1" >
            <tr>
                <td><table align="center">
                    <tr>
                        <th>EDIT QUEUE SIZE</th>
                    </tr>
                    <tr>
                        <td>  <form action="change_size.php" method="POST">
                            Max size: <?= $queue_data->MAX_QUEUE ?> <br>
                            New size: <input type="number" name="manip_size" min="10" max="200" required style="width: 50px"> <br> <br>
                            <button type="submit" onkeydown="return event.key !== 'Enter';">Change Size</button>
                        </form>
                        </td>
                    </tr>
                </table> </td>
                <td><table align="center">
                    <tr>
                        <th>EDIT PROFILE</th>
                    </tr>
                    <tr>
                        <td>  <form action="edit_user.php" method="POST">
                            Username<br>
                            <input type="text" id="staff" name="manip_name" value="<?= htmlspecialchars($_SESSION["username"])?>"><br>
                            Email<br>
                            <input type="text" id="staff" name="manip_email" value="<?= htmlspecialchars($_SESSION["email"])?>"> <br>
                            <button type="submit" onkeydown="return event.key !== 'Enter';">Edit changes</button>
                        </form>
                        </td>
                    </tr>
                </table> </td>
                <td><table align="center">
                    <tr>
                        <th>CHANGE PASSWORD</th>
                    </tr>
                    <tr>
                        <td>  <form action="change_password.php" method="POST">
                            <input type="password" name="old_pass" placeholder="Old password" id="staff"  required><br>
                            <input type="password" name="new_pass" placeholder="New password" id="staff" required> <br>
                            <input type="password" name="confirm_pass" placeholder="Confirm password" id="staff" required> <br>
                            <button type="submit" onkeydown="return event.key !== 'Enter';" onclick="return confirm('Upon proceeding, it cannot undo changes. Do you wish to proceed?');">Edit changes</button>
                        </form>
                        </td>
                    </tr>
                </table> </td>
            </tr>
            <tr>
                <td><table align="center">
                    <tr>
                        <th>Account Options</th>
                    </tr>
                    <tr>
                        <td>
                            <a href="logout.php">
                                <button class="acct" onkeydown="return event.key !== 'Enter';" style="width: 110px;">Log out</button>
                            </a>
                            <?php if($_SESSION["id"] < 1001 || $_SESSION["id"]  > 1010): ?>
                            <form action="delete_user.php" method="POST">
                                <button type="submit" id="imp" onkeydown="return event.key !== 'Enter';" style="width: 110px;"
                                onclick="return confirm('Upon proceeding, it cannot undo changes. Do you wish to proceed?');">DELETE ACCOUNT</button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table> </td>
                <td colspan="2"><table align="center">
                    <tr>
                        <th>Create staff account</th>
                    </tr>
                    <?php if($_SESSION["id"] >= 1001 && $_SESSION["id"] <= 1010): ?>
                    <tr>
                        <td>  
                        <form action="add_user.php" method="POST">
                            <input type="text" name="new_user" placeholder="Username" id="staff" required><br>
                            <input type="email" name="new_email" placeholder="Email" id="staff" required><br>
                            <input type="password" name="new_pass" placeholder="New password"id="staff" required> <br>
                            <input type="password" name="confirm_pass" placeholder="Confirm password" id="staff" required> <br>
                            <button type="submit" onkeydown="return event.key !== 'Enter';" onclick="return confirm('Do you wish to add new account?');">Add Account</button>
                        </form>
                        
                        </td>
                    </tr>
                    <?php else: ?>
                    <tr>
                        <td>  
                            You must be an administrative account<br>to access account creation.
                        </td>
                    </tr>
                    <?php endif; ?>
                </table> </td>
            </tr>
            <tr>
                <td><table align="center">
                    <tr>
                        <th>CURRENT LOG COUNT</th>
                    </tr>
                    <tr>
                    <?php 
                        $getdata = $mysqli->query("SELECT * FROM queue_defaults ORDER BY IDX DESC");
                        $queuelog_count = $mysqli->query("SELECT * FROM queuelogs ORDER BY POS DESC");
                    ?>
                        <td>  
                            QUEUE LOG: <?= htmlspecialchars($queuelog_count->num_rows); ?> <br>
                            CHANGE LOG: <?= htmlspecialchars($getdata->num_rows) ?>
                        </td>
                    </tr>
                </table> </td>
                <td colspan="2"><table align="center">
                    <tr>
                        <th>Log deletion</th>
                    </tr>
                    <?php if($_SESSION["id"] >= 1001 && $_SESSION["id"] <= 1010): ?>
                    <tr>
                        <td>  
                        <form action="delete_log.php" method="POST">
                            <button type="submit" onkeydown="return event.key !== 'Enter';" name="delete_changelog" value="yes" id="imp"
                            onclick="return confirm('Do you wish to delete all logs, This will revert to default logs?');">Delete Change Logs</button>
                            <button type="submit" onkeydown="return event.key !== 'Enter';" name="delete_queuelog" value="yes" id="imp"
                            onclick="return confirm('Do you wish to delete all logs?');">Delete Queue Logs</button>
                        </form>
                        
                        </td>
                    </tr>
                    <?php else: ?>
                    <tr>
                        <td>  
                            You must be an administrative account<br>to access log deletion.
                        </td>
                    </tr>
                    <?php endif; ?>
                </table> </td>
            </tr>
            <tr>
                <td colspan="3"><table align="center" border="1" cellspacing="0" style="width: 800px;">
                    <tr>
                        <th colspan="9">CHANGE LOGS</th>
                    </tr>
                    <?php
                        
                        if($getdata->num_rows > 0){
                            echo "<tr>
                                <th>IDX</th>
                                <th>MADE BY</th>
                                <th>TYPE</th>
                                <th>MAX QUEUE</th>
                                <th>DESK1</th>
                                <th>DESK2</th>
                                <th>DESK3</th>
                                <th>REF</th>
                                <th>DATE</th>
                            </tr>";
                            while($row = $getdata->fetch_assoc()){
                                $idx = htmlspecialchars($row["IDX"]);
                                $madeby = htmlspecialchars($row["madeby"]);
                                $type = htmlspecialchars($row["Type"]);
                                $max_queue = htmlspecialchars($row["max_queue"]);
                                $d1 = htmlspecialchars($row["DESK1"]);
                                $d2 = htmlspecialchars($row["DESK2"]);
                                $d3 = htmlspecialchars($row["DESK3"]);
                                $ref = htmlspecialchars($row["REF"]);
                                $dt = htmlspecialchars($row["DATETIME"]);

                                echo "<tr>
                                <th>{$idx}</th>
                                <th>{$madeby}</th>
                                <th>{$type}</th>
                                <th>{$max_queue}</th>
                                <th>{$d1}</th>
                                <th>{$d2}</th>
                                <th>{$d3}</th>
                                <th>{$ref}</th>
                                <th>{$dt}</th>
                            </tr>";
                            }
                        }
                    ?>
                </table> </td>
            </tr>
        </table>
        <br>
        <br>
    </body>
</html>