<?php
session_start();
if (isset($_SESSION['unique_id'])) {
    include_once "config.php";
    $incoming_msg_id = $_SESSION['unique_id'];
    // $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $build = "";
    $sql = "SELECT * FROM messages inner join users on users.unique_id = messages.outgoing_msg_id WHERE messages.incoming_msg_id = $incoming_msg_id and messages.read = 0  ORDER BY messages.msg_id";
    $query = mysqli_query($conn, $sql);
    
    
    
    
    
    $sql3 = "SELECT * FROM users WHERE unique_id = {$incoming_msg_id} ORDER BY user_id DESC";
    $query3 = mysqli_query($conn, $sql3);
    $qeury_lock = $query3;
    $row3 = $qeury_lock->fetch_assoc();
    $lock_query = $row3['lock'];
    $lock_remove_space = str_replace(' ', '', $lock_query);
    $lock_arr = explode("|",$lock_remove_space) ; //all the user that this user has lock

    
    
    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            $uniqid = $row['unique_id'];
            $msg = $row['msg'];
            $fname = $row['fname'];
            $lname = $row['lname'];     
            if(in_array($row['unique_id'], $lock_arr)){
                $build  .= '<li><a class="dropdown-item" href="index_camera.php?user_id='. $row['unique_id'] .'">'.$msg.' - <b>'.$fname.' '.$lname.'</b> </a></li>';
            }else{
                $build  .= '<li><a class="dropdown-item" href="chat.php?user_id='. $row['unique_id'] .'">'.$msg.' - <b>'.$fname.' '.$lname.'</b> </a></li>';
            }
        }
    }else{
        $build = '<li><a class="dropdown-item" href="#">No new notification</a></li>';
    }
  
    echo $build;
} else {
    header("location: ../login.php");
}
