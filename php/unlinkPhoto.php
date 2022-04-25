<?php
session_start();
include_once "config.php";
if(isset($_GET['id'])){
    $sql = mysqli_query($conn, "DELETE FROM `chatapp`.`face_unlock` WHERE (`img_id` = '{$_GET['id']}');");
    if($sql){
        echo 'Success!';
    }else{
        echo 'Something Went Wrong!';
    }
}

?>