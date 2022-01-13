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
    // echo $output;

    

    $sortColArr = array_column($sortArr, 'msg_id');
    array_multisort($sortColArr, SORT_DESC, $sortArr);

    usort($sortArr, function ($item1, $item2) {
        return $item2['msg_id'] <=> $item1['msg_id'];
    });

    // print_r($sortArr[0]);

    foreach ($sortArr as $val => $result){
        print_r($result['output']) ;
        // $output .= $result[$val];
    }

    // echo $output;
?>