<?php
    require_once "config.php";
    
    $result = $mysqli->query("SELECT * FROM queue_orders ORDER by POS ASC");

    $FRONT = $BACK = array("*", 0);
    $type_IDS = array(1, 1, 1, 1, 1, 1); // get ID by type
    $count = array(0, 0, 0, 0, 0, 0); // count items by type
    $position = 0;

    function find_index($type, $id){
        global $mysqli;
        $res = $mysqli->query("SELECT * FROM queue_orders ORDER by POS ASC");
        $sub = 1;
        while($row = $res->fetch_assoc()){
            if($type == htmlspecialchars($row['Type']) && $id == htmlspecialchars($row['ID'])){
              return $sub;  
            } $sub++;
        } return -1;
    }

    function type_finder($Concern, &$type, &$id, &$pos){
        global $position, $type_IDS;
        $pos = $position + 1;

        switch($Concern){
            case "Enrollment":{
                $type =  "A"; 
                $id = $type_IDS[0] + 1;
                break;
            } case "Non-issuance ID":{ 
                $type =  "B"; 
                $id = $type_IDS[1] + 1;
                break;
            } case "CTC of COR": {
                $type =  "C"; 
                $id = $type_IDS[2] + 1;
                break;
            } case "Scholarship": {
                $type =  "D"; 
                $id = $type_IDS[3] + 1;
                break;
            } case "TOR / Diploma":{
                $type =  "E"; 
                $id = $type_IDS[4] + 1;
                break;
            } case "Others": {
                $type =  "F"; 
                $id = $type_IDS[5] + 1;
                break;
            }
        } 
    }

    
    $sub = 0;
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            if($sub == 0){
                $FRONT = array(htmlspecialchars($row['Type']), (string)htmlspecialchars($row['ID']));
            }
            if ($result->num_rows - 1 == $sub) {
                $BACK = array(htmlspecialchars($row['Type']), (string)htmlspecialchars($row['ID']));
                $position = htmlspecialchars($row['POS']);
            }

            switch($row['Type']){
                case "A": {
                    $type_IDS[0] = max($type_IDS[0], $row["ID"]);
                    $count[0]++;
                    break;
                } case "B": {
                    $type_IDS[1] = max($type_IDS[1], $row["ID"]);
                    $count[1]++;
                    break;
                } case "C": {
                    $type_IDS[2] = max($type_IDS[2], $row["ID"]);
                    $count[2]++;
                    break;
                } case "D": {
                    $type_IDS[3] = max($type_IDS[3], $row["ID"]);
                    $count[3]++;
                    break;
                } case "E": {
                    $type_IDS[4] = max($type_IDS[4], $row["ID"]);
                    $count[4]++;
                    break;
                } case "F": {
                    $type_IDS[5] = max($type_IDS[5], $row["ID"]);
                    $count[5]++;
                    break;
                }
            } $sub++;
        } 
    }
?>