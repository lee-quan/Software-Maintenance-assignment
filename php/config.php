<?php
  // $hostname = "chatapptesting.educationhost.cloud";
  // $username = "mbecspyx_elwin";
  // $password = "WIF3005testing";
  // $dbname = "mbecspyx_chatapp";

  $hostname = "localhost";
  $username = "root";
  $password = "";
  $dbname = "chatapp";


  $conn = mysqli_connect($hostname, $username, $password, $dbname);
  if(!$conn){
    echo "Database connection error".mysqli_connect_error();
  }
?>
