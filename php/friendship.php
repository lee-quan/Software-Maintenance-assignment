<?php
session_start();

include_once('config.php');

$sql = "INSERT INTO friendship (to_, from_) VALUES (".$_POST["id"].",".$_SESSION['unique_id'].")";
$query = mysqli_query($conn,$sql);
// echo $sql;
if($query){
    echo "added!";
}

?>