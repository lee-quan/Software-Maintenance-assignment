<?php
session_start();
if (isset($_SESSION['unique_id'])) {
    include_once "config.php";
    $incoming_msg_id = $_SESSION['unique_id'];
    // $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $output_noti = 0;
    $sql = "SELECT * FROM messages WHERE messages.incoming_msg_id = $incoming_msg_id and messages.read = 0  ORDER BY messages.msg_id";
    $query = mysqli_query($conn, $sql);
    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            if ($row['read'] == 0) {
                $output_noti++;
            }
        }
    }
    // print_r($sql);
    echo $output_noti;
} else {
    header("location: ../login.php");
}
