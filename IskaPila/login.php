<?php
    require_once "config.php";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $EMAIL = trim($_POST["email"]??'');
        $USERNAME = trim($_POST["username"]??'');
        $USER_ID = trim($_POST["user_ID"]??'0');
        $PASSWORD = trim($_POST["password"]??'');

        $compare = $mysqli->prepare("SELECT * FROM staff_handler WHERE User_ID = ? AND Username = ? AND Email = ? AND Password = ?");
        $compare->bind_param('isss', $USER_ID, $USERNAME, $EMAIL, $PASSWORD);
        $compare->execute();
        $res = $compare->get_result();
        $data = $res->fetch_assoc();
        $compare->close();

        if(!$data){
            echo"<script>alert(\"Incorrect username, email, ID or password.\") </script>";
        } else {
            session_start();

            $_SESSION["id"] = $data["User_ID"];
            $_SESSION["username"] = $data["Username"];
            $_SESSION["email"] = $data["Email"];
            
            header("Location: staff_access/dashboard.php");
        }
    }

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Log in</title>
        <link rel="stylesheet" href="main.css">
        <link rel="icon" type="image/x-icon" href="web_favi.png">
        <style>
            #ID{
                width: 80px;
            }

            #HML{
                width: 200px;
            }

            #PASS{
                width: 110px;
            }

            #LOGIN{
                width: 90px;

            }
        </style>
    </head>
    <body>
        <ul class="hdrr">
            <li><img src="menu.png" width="60px" height="50px"></li>
            <li><a href="index.html">Menu</a></li>
            <li><a href="viewqueue.php">View Queue</a></li>
            <li><a href="inquire.php">Apply Queue</a></li>
            <li><a href="help.html">Help</a></li>
            <li><a href="login.php">Log In</a></li>
        </ul>
        <br> <br>

        <form method="post" action="login.php"> <table align="center" class="login">
            <tr>
                <th>Dashboard<br>Access</th>
            </tr>
            <tr>
                <td> <input type="text" id="HML" name="username" placeholder="Username" required>
                </td>
            </tr>
            <tr>
                <td>
                <input type="email" id="HML" name="email" placeholder="Email"required>
                </td>
            </tr>
            <tr>
                <td> <input type="number" id="ID" name="user_ID" placeholder="ID" required>    
                <input type="password" id="PASS" name="password"  placeholder="Password"required>
                </td>
            </tr>
            <tr>
                <td><button type="submit" id="LOGIN">LOG IN</button></td>
            </tr>

        </table></form>
    </body>
</html>