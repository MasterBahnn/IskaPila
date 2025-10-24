<?php
    include "config.php";

    class queue_default{
        public $IDX = 1; 
        public $MAX_QUEUE = 50;
        public $DESK1 = array("*", 0);
        public $DESK2 = array("*", 0);
        public $DESK3 = array("*", 0);
        public $DEF_VAL = "* 0";
    }

    $queue_data = new queue_default();

    function get_data(){
        global $queue_data, $mysqli;
        $result = $mysqli->query("SELECT * FROM queue_defaults ORDER by IDX ASC");
        if($result->num_rows > 0){ $sub = 0;
            while($current = $result->fetch_assoc()){
                if($result->num_rows -1 == $sub){
                    $queue_data->IDX = htmlspecialchars($current["IDX"]);
                    $queue_data->MAX_QUEUE = htmlspecialchars($current["max_queue"]);
                    $queue_data->DESK1 = explode(" ", htmlspecialchars($current["DESK1"]));
                    $queue_data->DESK2 = explode(" ", htmlspecialchars($current["DESK2"]));
                    $queue_data->DESK3 = explode(" ", htmlspecialchars($current["DESK3"]));
                } $sub++;
            }
        } else {
            $USERNAME = "DEFAULT"; $TYPE = "A"; 
            $query1 = $mysqli->prepare("INSERT INTO queue_defaults(IDX, madeby, max_queue, Type, DESK1, DESK2, DESK3) VALUES (?,?,?,?,?,?,?)");
            $query1->bind_param('isissss', $queue_data->IDX, $USERNAME, $queue_data->MAX_QUEUE, $TYPE, $queue_data->DEF_VAL, $queue_data->DEF_VAL, $queue_data->DEF_VAL);
            if(!$query1->execute()){
                die("Setup failed" . $query1->error);
            }
        }
    }

    
    get_data();

?>