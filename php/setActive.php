<?php

session_start();
include_once "config.php";
$active = mysqli_real_escape_string($conn, $_POST['active']);
$sessionID = $_SESSION['user_id'];
$sql = mysqli_query($conn, "UPDATE users SET status = '$active' where user_id = $sessionID") or die();
