<?php
    session_start();
    include_once "config.php";
    $outgoing_id = $_SESSION['unique_id'];
    $sql = "SELECT * FROM users WHERE NOT unique_id = {$outgoing_id} ORDER BY user_id DESC";
    $sql3 = "SELECT * FROM users WHERE unique_id = {$outgoing_id} ORDER BY user_id DESC";
    $query = mysqli_query($conn, $sql);

    $query3 = mysqli_query($conn, $sql3);
    $qeury_lock = $query3;
    $row = $qeury_lock->fetch_assoc();
    $lock_query = $row['lock'];
    $lock_remove_space = str_replace(' ', '', $lock_query);
    // print_r($row);
    $lock_arr = explode("|",$lock_remove_space) ; //all the user that this user has lock

    $output = "";
    if(mysqli_num_rows($query) == 0){
        $output .= "No users are available to chat";
    }elseif(mysqli_num_rows($query) > 0){
        include_once "data.php";
    }
    echo $output;
?>