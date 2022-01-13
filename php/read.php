<?php
session_start();
if (isset($_SESSION['unique_id'])) {
    include_once "config.php";
    $incoming_id = $_SESSION['unique_id'];
    $outgoing_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $output = "";
    // $sql = "SELECT * FROM messages LEFT JOIN users ON users.unique_id = messages.outgoing_msg_id
    //         WHERE (outgoing_msg_id = {$outgoing_id} AND incoming_msg_id = {$incoming_id})
    //         OR (outgoing_msg_id = {$incoming_id} AND incoming_msg_id = {$outgoing_id}) ORDER BY msg_id";

    $sql = "UPDATE messages set messages.read = 1 where msg_id in (SELECT msg_id FROM messages 
            inner join users on users.unique_id = messages.outgoing_msg_id
            WHERE messages.incoming_msg_id = {$incoming_id} and messages.outgoing_msg_id= {$outgoing_id}  and messages.read = 0 ORDER BY messages.msg_id);";
    $query = mysqli_query($conn, $sql);

    // $resultArr = $query -> fetch_all(MYSQLI_ASSOC);
    print_r($sql);
}
