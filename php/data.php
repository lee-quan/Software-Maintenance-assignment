<?php
    $sortArr = [];
    while($row = mysqli_fetch_assoc($query)){
        $sql2 = "SELECT * FROM messages WHERE (incoming_msg_id = {$row['unique_id']}
                OR outgoing_msg_id = {$row['unique_id']}) AND (outgoing_msg_id = {$outgoing_id} 
                OR incoming_msg_id = {$outgoing_id}) ORDER BY msg_id DESC LIMIT 1";
  
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
        
        $msg_id = $row2['msg_id'];
        $read = $row2['read'];
        $yousent = $row2['outgoing_msg_id'];
        $priority = 0;

        if($read == 0 && $yousent!=$outgoing_id && $result !="No message available"){
            $namehighlight =  '<span class="text-primary">'.$row['fname']. " " . $row['lname'].'</span>';
            $priority = 1;
        }else{
            $namehighlight =  '<span >'.$row['fname']. " " . $row['lname'].'</span>';
        }

        if(in_array($row['unique_id'], $lock_arr)){
            $temp = '<a id="'.$row['unique_id'].'" href="index_camera.php?user_id='. $row['unique_id'] .'" style="text-decoration: none;">
                        <div class="content">
                        <img src="php/images/'. $row['img'] .'" alt="">
                        <div class="details">
                            '.$namehighlight.'
                            <p>'. $you . $msg .'</p>
                        </div>
                        </div>
                        <div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
                    </a>';
            array_push($sortArr, array("msg_id"=>$msg_id, "output"=>$temp, "priority" => $priority));
        }else{

            $temp = '<a id="'.$row['unique_id'].'" href="chat.php?user_id='. $row['unique_id'] .'" style="text-decoration: none;">
                        <div class="content">
                        <img src="php/images/'. $row['img'] .'" alt="">
                        <div class="details">
                        '.$namehighlight.'
                            <p>'. $you . $msg .'</p>
                        </div>
                        </div>
                        <div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
                    </a>';
            array_push($sortArr, array("msg_id"=>$msg_id, "output"=>$temp, "priority" => $priority));
        }

    }
?>