<?php
    include "config.php";
    include "get_id.php";
    include "queue_config.php";
    session_start();
    
    if(isset($_SESSION["alert"])){
        $sample = $_SESSION["alert"];
        echo"<script> alert('{$sample}'); </script>";
        unset($_SESSION["alert"]);
    } 

    $ref= $data = null;
    if(isset($_GET['ref'])){
        $ref = htmlspecialchars($_GET['ref']);
        $CURRENT = $mysqli->prepare("SELECT * FROM queue_orders WHERE REF = ?");
        $CURRENT->bind_param('i', $ref);
        $CURRENT->execute();
        $res = $CURRENT->get_result();
        $data = $res->fetch_assoc();
        $CURRENT->close();
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="refresh" content="20">
        <link rel="stylesheet" href="main.css">
        <title> Queue Viewer </title>
        <link rel="icon" type="image/x-icon" href="web_favi.png">
    </head>
    <body>
        <ul class="hdrr">
            <li><img src="menu.png" width="60px" height="50px"></li>
            <li><a href="menu.html">Menu</a></li>
            <li><a href="viewqueue.php">View Queue</a></li>
            <li><a href="inquire.php">Apply Queue</a></li>
            <li><a href="help.html">Help</a></li>
            <li><a href="login.php">Log In</a></li>
        </ul>
        <br>
        <h2 id="title2">Please wait for the admission staff to call your number in the desks below</h1>
        <table align="center">
            <tr>
                <td>
                    <table class="bigview">
                        <tr>
                            <td> DESK 1 </td>
                        </tr>
                        <tr>
                            <td id="nc"><?php echo $queue_data->DESK1[0] . "-" . $queue_data->DESK1[1] ?></td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="bigview">
                        <tr>
                            <td> DESK 2 </td>
                        </tr>
                        <tr>
                            <td id="nc"><?php echo $queue_data->DESK2[0] . "-" . $queue_data->DESK2[1] ?></td>
                        </tr>
                    </table>
                </td>
                <td>
                    <table class="bigview">
                        <tr>
                            <td> DESK 3 </td>
                        </tr>
                        <tr>
                            <td id="nc"><?php echo $queue_data->DESK3[0] . "-" . $queue_data->DESK3[1] ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table> <br>
        
            <?php
            // store data for searching the reference number
            if($data != NULL){   
                
                $type = htmlspecialchars($data["Type"]);
                $id = htmlspecialchars($data["ID"]);
                $index = find_index($type, $id);
                $Name = htmlspecialchars($data["Name"]);
                $Concern = htmlspecialchars($data["Concern"]);
                $Notes = htmlspecialchars($data["Notes"]);
                $submission_date = htmlspecialchars($data["Submission_date"]);
                
                echo "
                    <table class=\"search\" align=\"center\">
                        <tr>
                            <td id=\"nc\"> Queue Code: {$type} - {$id} </td>
                        </tr>
                        <tr>
                            <td id=\"nc\" colspan=\"2\"> Placement: {$index} </td>
                        </tr>
                        <tr>
                            <td colspan=\"2\"> Submission date: {$submission_date} </td>
                        </tr>
                    </table> <br>";

                echo " <table align=\"center\" class=\"info\">
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
                    </table> ";
            } else{
                echo " <table align=\"center\" class=\"info\">
                <tr>
                    <td colspan=\"2\"> Placement not found <br>
                    Enter queue type and number to search 
                    </td>
                </tr> </table>";
            }
            ?>
        <br>

        <form method="get" action="viewqueue.php">
            <table align="center" style="text-align: center;" class="info">
                <tr>     
                    <td>REF NO:</td>
                    <td><input type="number" name="ref" min="10000" max="99999" style="width: 90px" value="<?=$ref?>"> </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <button type="submit">Search</button>
                    </td>
                </tr>  
            </table> <br>
        </form>

    </body>
</html>
