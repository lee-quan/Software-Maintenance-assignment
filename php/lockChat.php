<?php

session_start();
include_once "config.php";

$id = $_SESSION['user_id'];
$userID = $_POST['chatUserID'];

$sql = "SELECT users.lock from users where user_id = '$id';";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$sql1 = "SELECT count(user_id) as count1 FROM face_unlock WHERE user_id =$id";
$result1 = $conn->query($sql1);
$row2 = $result1->fetch_assoc();
$numOfPic = $row2['count1'];

if($_POST['lock']=='lockChat'){
    if ($numOfPic == 0) {
        echo "No picture submitted. Go to setting now.";
    } else {
        $previousLock = $row['lock'];
        $newLock = $previousLock . "|" . $userID;
        $sql2 = "UPDATE users set users.lock = '$newLock' where user_id = '$id';";
        $query = mysqli_query($conn, $sql2);
    
        if($query){
            echo 'success';
        }
    }
}

if($_POST['lock']=='unlockChat'){
    $previousLock = $row['lock'];
    $newLock = str_replace('|'.$userID,'',$previousLock);
    echo $userID.'\nprevious'.$previousLock.'\nafter'.$newLock.'';
    $sql2 = "UPDATE users set users.lock = '$newLock' where user_id = '$id';";
    $query = mysqli_query($conn, $sql2);

    if($query){
        echo 'success';
    }
}
