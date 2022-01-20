<?php

session_start();
include_once "config.php";

$id = $_SESSION['user_id'];
$userID = $_POST['chatUserID'];

$sql = "SELECT users.lock from users where user_id = '$id';";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$previousLock = $row['lock'];
$lock_remove_space = str_replace(' ', '', $previousLock);
$lock_arr = explode("|", $lock_remove_space); //all the user that this user has lock

foreach ($lock_arr as $index => $lock){
    if($lock == $userID){
        unset($lock_arr[$index]); 
    }
}

$reIndex = array_values($lock_arr);

$newLock = implode('|', $reIndex);

$newLockSQL = "UPDATE users set users.lock = '$newLock' where user_id = '$id';";

print_r($previousLock);
print_r("<br>");
print_r($newLock);
print_r("<br>");
print_r($newLockSQL);

$conn->query($newLockSQL);

