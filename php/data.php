<?php
    $sortArr = [];
    while($row = mysqli_fetch_assoc($query)){
        $sql2 = "SELECT * FROM messages WHERE (incoming_msg_id = {$row['unique_id']}
                OR outgoing_msg_id = {$row['unique_id']}) AND (outgoing_msg_id = {$outgoing_id} 
                OR incoming_msg_id = {$outgoing_id}) ORDER BY msg_id DESC LIMIT 1";
        // print_r($sql2);
        // echo "<br>";
        $query2 = mysqli_query($conn, $sql2);
        $row2 = mysqli_fetch_assoc($query2);
        (mysqli_num_rows($query2) > 0) ? $result = $row2['msg'] : $result ="No message available";
        (strlen($result) > 28) ? $msg =  substr($result, 0, 28) . '...' : $msg = $result;
        if(isset($row2['outgoing_msg_id'])){
            ($outgoing_id == $row2['outgoing_msg_id']) ? $you = "You: " : $you = "";
        }else{
            $you = "";
        }
        ($row['status'] == "Offline now") ? $offline = "offline" : $offline = "";
        ($outgoing_id == $row['unique_id']) ? $hid_me = "hide" : $hid_me = "";

        
        // $badge='<span class="badge rounded-pill bg-primary mx-1"  style="font-size: .55em; top:20px">'.$output_noti.'</span>';
        
        $msg_id = $row2['msg_id'];
        if(in_array($row['unique_id'], $lock_arr)){
            // $output .= '<a id="'.$row['unique_id'].'" href="index_camera.php?user_id='. $row['unique_id'] .'" style="text-decoration: none;">
            //             <div class="content">
            //             <img src="php/images/'. $row['img'] .'" alt="">
            //             <div class="details">
            //                 <span>'. $row['fname']. " " . $row['lname'] .'</span>
            //                 <p>'. $you . $msg .'</p>
            //             </div>
            //             </div>
            //             <div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
            //         </a>';
            $temp = '<a id="'.$row['unique_id'].'" href="index_camera.php?user_id='. $row['unique_id'] .'" style="text-decoration: none;">
                        <div class="content">
                        <img src="php/images/'. $row['img'] .'" alt="">
                        <div class="details">
                            <span>'. $row['fname']. " " . $row['lname'] .'</span>
                            <p>'. $you . $msg .'</p>
                        </div>
                        </div>
                        <div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
                    </a>';
            // $sortArr [] = array("msg_id"=>$msg_id, "output"=>$temp);
            array_push($sortArr, array("msg_id"=>$msg_id, "output"=>$temp));
        }else{
            // $output .= '<a id="'.$row['unique_id'].'" href="chat.php?user_id='. $row['unique_id'] .'" style="text-decoration: none;">
            //             <div class="content">
            //             <img src="php/images/'. $row['img'] .'" alt="">
            //             <div class="details">
            //                 <span>'. $row['fname']. " " . $row['lname'] .'</span>
            //                 <p>'. $you . $msg .'</p>
            //             </div>
            //             </div>
            //             <div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
            //         </a>';
            $temp = '<a id="'.$row['unique_id'].'" href="chat.php?user_id='. $row['unique_id'] .'" style="text-decoration: none;">
                        <div class="content">
                        <img src="php/images/'. $row['img'] .'" alt="">
                        <div class="details">
                            <span>'. $row['fname']. " " . $row['lname'] .'</span>
                            <p>'. $you . $msg .'</p>
                        </div>
                        </div>
                        <div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
                    </a>';
            // $sortArr [] = array("msg_id"=>$msg_id, "output"=>$temp);
            array_push($sortArr, array("msg_id"=>$msg_id, "output"=>$temp));
        }

    }
?>