<?php

session_start();
include_once "config.php";

$id = $_SESSION['user_id'];
$userID = $_POST['chatUserID'];

$sql = "SELECT users.lock from users where user_id = '$id';";
$result = $conn->query($sql);
$row = $result->fetch_assoc();


$previousLock = $row['lock'];
$newLock = $previousLock."|".$userID;

$sql2 = "UPDATE users set users.lock = '$newLock' where user_id = '$id';";
$conn->query($sql2);
// print_r($sql2);
