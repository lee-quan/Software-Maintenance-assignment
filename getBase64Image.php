<?php 
session_start();
include_once('php/config.php');
$user_id = $_GET["id"];
$query = "SELECT * FROM face_unlock WHERE user_id = $user_id";
$img = [];
$img_type = [];
$counter=0;
$sql = mysqli_query($conn,$query);

while($row = mysqli_fetch_assoc($sql)){
    echo 'data:image/png;base64,'.$row['img'].'***';
}

?>