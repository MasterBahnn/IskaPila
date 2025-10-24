<?php
    require_once "config.php";
    include "get_id.php";
    include "queue_config.php";


    if($sub >= $queue_data->MAX_QUEUE - 1){ 
        session_start();
        $_SESSION["alert"] = "Queue is already full. Please try again later";
        header("Location: viewqueue.php?msg-queuefull");
    }

    //  sends data to the database
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        date_default_timezone_set("Asia/Manila");
        
        $Name = trim($_POST['name']??'');
        $Concern = trim($_POST["concern"]??'');
        $Notes = trim($_POST["notes"]??'');
        $Email = trim($_POST["email"]??'');
        $submission_date = date("d/m/y h:i:sa");
        type_finder($Concern, $type, $id, $pos);
        $Ref_no = rand(10000, 99999);
        
        // preparing sql on inserting values to database
        $submit = $mysqli->prepare("INSERT INTO queue_orders(pos, Name, Concern, Notes, Email, Submission_date, Type, ID, REF) VALUES (?,?,?,?,?,?,?,?,?)");
        $submit->bind_param('issssssii', $pos, $Name, $Concern, $Notes, $Email, $submission_date, $type, $id, $Ref_no);
        if($submit->execute()){ 
            session_start();
            $_SESSION["alert"] = "The application is sent to the system.\\nPlease save this number to show.\\nQueue Number: {$type}-{$id} \\nREF NO: {$Ref_no}";
            header("Location: viewqueue.php?ref={$Ref_no}");
        } else{
            echo "<script> alert('Insert Failed: {$stmt->error}');";
        }
        
    }
    

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title> Service Form </title>
        <link rel="stylesheet" href="main.css">
        <link rel="icon" type="image/x-icon" href="web_favi.png">
        <style>
            textarea{
                resize:none;
                width: 200px;
                height: 70px;
            }
        </style>
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

        <form action="inquire.php" method="post">
            <table align="center" class="login">
                <tr>
                    <td colspan="2" align="center">
                        <h2> Application Form </h2>
                    </td>
                </tr>
                <tr>
                    <td><span id="imp">*</span>Name</td>
                    <td><input type="text" name="name" required></td>
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
                    <td><input type="email" name="email" placeholder="johndoe@outlook.com" required></td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        Notes <br>
                        <textarea name="notes"> </textarea> 
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                    <button type="submit">Submit Application</button>
                    <button type="clear" onclick="return confirm('Are you want to clear existing values?');">Clear Values</button>
                    </td>
                </tr>
            </table>
            
            
             <br>
            
        </form>
        

    </body>
</html>