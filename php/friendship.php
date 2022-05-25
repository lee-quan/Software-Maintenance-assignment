<?php
session_start();

include_once('config.php');

$getUserData = mysqli_query($conn, 'SELECT * FROM users WHERE unique_id=' . $_SESSION['unique_id']);
if (mysqli_num_rows($getUserData) > 0) {
    while ($row = mysqli_fetch_assoc($getUserData)) {
        $fname = $row['fname'];
    }
}

if (isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $sql = "INSERT INTO friendship (to_, from_) VALUES (" . $_POST["id"] . "," . $_SESSION['unique_id'] . ")";
        $query = mysqli_query($conn, $sql);
        // echo $sql;
        if ($query) {
            $sql = "INSERT INTO notification (to_, from_, notification_type, message) VALUES (" . $_POST["id"] . "," . $_SESSION['unique_id'] . ",0,'{$fname} sent you a friend request.')";
            echo $sql;
            $query1 = mysqli_query($conn, $sql);
            if ($query1) {
                // echo 12312321312;
            }else{
                // echo 0000;
            }
        }
    }
}
