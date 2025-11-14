<?php
    require_once "../config.php";
    session_start();

    if(!isset($_SESSION["id"]) && !isset($_SESSION["username"])){
        header("Location: ../login.php");
    }


?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="staff.css">
        <title>Dashboard</title>
        <link rel="icon" type="image/x-icon" href="../web_favi.png">
        <style>
            table.help{
                border-style: solid;
                border-width: 2px;
                border-radius: 5px;
                width: 1200px;
                text-align: left;
                background: rgb(248, 218, 218);
                box-shadow: 0px 0px 5px black;
            }

            div.margin{
                margin: 10px;
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

        <table align="center" class="help">
            <tr>
                <th><h1 style="text-align: center;">IskaPila Dashboard Access Help Information</h1></th>
            </tr>
            <tr>
                <td> <div class="margin">
                    <h2>Dashboard</h2>
                    <ul>
                        <li><b>The dashboard contains the primary queue information and dashboard for calling and dequeueing current front queue</b></li>
                        <li>The desks is used to call the current front queue, and drop the previously called queue code after the interaction with the client</li>
                        <li>Take note of the desk you manage, and mind your own desk since the desks are accessible to any logged staff accounts. They can drop or call the 
                            queue codes in any time, and the actions made in aftermentioned were logged by the <b>Queue logs</b> and <b>Change logs</b> in settings</li>
                        <li>Below the desks are the information of current front queue code and back queue code, the front queue code is changed after calling in any desks</li>
                        <li>Beside the front queue and back queue is the type count. This shows the number instance of the concerns</li>
                        <li>Here are the concern type based on the queue code:
                            <ul>
                                <li>A - Enrollment</li>
                                <li>B - Non-issuance ID</li>
                                <li>C - CTC of COR</li>
                                <li>D - Scholarship</li>
                                <li>E - TOR / Diploma</li>
                                <li>F - Others</li>
                            </ul>
                        </li>
                    </ul>
                    <h2>Queue List</h2>
                    <ul>
                        <li><b>The queue list serves as a list of queues that shows the current queue information from client inquiries by order</b></li>
                        <li>The list is ordered by the position (POS), it serves as a index for finding the front and back queue</li>
                        <li>You can able to enter inquiries from the client to be enqueued in the order</li>
                        <li>Here is the guide on entering their inquiriesto apply:
                            <ol>
                                <li>Name and email address (must be written legally)</li>
                                <li>Concern type:
                                    <ul>
                                        <li>Enrollment - enrollment related inquiries / admission for freshmen </li>
                                        <li>Non-issuance ID - getting other ID alternative for non-issued students</li>
                                        <li>CTC of COR - certifying certificate of registration as certified  true copy</li>
                                        <li>Scholarship - for inquiring scholarship needs and financial assistance</li>
                                        <li>TOR / Diploma - graduation/post-graduate documents</li>
                                        <li>Others - other admission related inquiries</li>
                                    </ul>
                                </li>
                                <li>Notes for more clarification</li>
                                <li>Submit it when all the forms is already filled out</li>
                            </ol> After enqueued, the recently submitted application is showed at the bottom of the queue list, and current back queue in the <b>Dashboard</b>
                        </li>
                        <li>Take note that the application can not be enqueued due to the size limit (see settings)</li>
                        <li><b>You can search the Name and Email in the list</b></li>
                        <li>If the search is found, the system shows you the placement of the queue information, queue code and reference number that they can use to search their placement on the client side</li>
                        <li>Take note that if the search is not found, you can find the queue information manually in the queue list (for larger queue size)</li>
                        <li><b>You can dequeue/clear all enqueued information in the list</b></li>
                        <li>Take note that the change is irreversable, and the action is logged at the <b>Change Logs</b></li>
                    </ul>
                    <h2>Queue Logs</h2>
                    <ul>
                        <li><b>The queue logs contains the previous queue information that is dequeued, and its caller with the date and time </b></li>
                        <li>This is where you based on who called the inquiries from the client, used as evidence if something else happens</li>
                    </ul>
                    <h2>Change Logs</h2>
                    <ul>
                        <li><b>The change logs contains the previous queue defaults that is executed by the user. </b></li>
                        <li>Also used as evidence on who is user called and dropped the queue number from the desks, changed queue size, and dequeue records</li>
                        <li>The types are varied by the following:
                            <ul>
                                <li>A - DEFAULT (Generated by the system)</li>
                                <li>B - Called/Dequeued queue code or Dropped call on queue code</li>
                                <li>C - Changed queue size</li>
                                <li>D - Dequeued all current queue order information</li>
                            </ul>
                        </li>
                        <li>You can view the change logs in the settings</li>
                    </ul>
                    <h2>Settings</h2>
                    <ul>
                        <li><b>The settings used to change queue size, change credentials, and change logs</b></li>
                        <li>Changing queue size is recorded in the change logs</li>
                        <li>You can edit acccount information and passwords, and have the ability to delete staff account (except administrative accounts)</li>
                        <li>Deleting queue logs and change logs is only done by admistrative account. And they have the ability to create staff accounts</li>
                        <li>You can ask the administrative account holder to create a new staff account.</li>
                    </ul>
                </div></td>
            </tr>
        </table>
        <br>
        <table align="center" class="help">
            <tr>
                <th style="text-align: center;"><h1>About</h1></th>
            </tr>
            <tr>
                <td> <div class="margin">
                    <h2>IskaPila was made for the submission fo the Data Structures and Algorithms</h2>
                    Contents applied: <ul>Queue Data Structure</ul>
                    <h3>Creators:</h3>
                    <ul>
                        <li>Roanne Joy R. Segui - Developer, Documentation and Originality </li>
                        <li>Matthew Kristoff B. Gonzales - Developer</li>
                    </ul>
                    
                    <br>
                    Version info: 1.0.2 <br>
                    All rights reserved 2025
                </div></td>
            </tr>
        </table>

        <p align="center"></p>

    </body>
</html>