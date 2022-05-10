<?php 
include_once('config.php');
session_start();
$id = $_SESSION['user_id'];
if(isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['email'])){
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];

    $query = mysqli_query($conn,"UPDATE users SET fname='$fname', lname='$lname', email = '$email' WHERE user_id = $id");
    if($query){
        echo "Updated!";
    }
}
