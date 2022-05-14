<?php
session_start();
include_once "php/config.php";

if (!isset($_GET['token'])) {
    // header("location: login.php");
} else {
    $token = $_GET['token'];
}
$query = "SELECT * FROM users WHERE token = '$token' and verified=0";
echo $query;
$sql = mysqli_query($conn, $query);
if (mysqli_num_rows($sql) > 0) {
    $query1 = "UPDATE users SET token = NULL, verified=1 WHERE token='$token' AND verified=0";
    $sql1 = mysqli_query($conn, $query1);
    if($sql1){
        $successful_verification = 1;
    }else{
        $successful_verification = 0;
    }
} else {
    $successful_verification = 0;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify</title>
</head>

<body>
    <script>
        var successfulVerification = <?php echo $successful_verification?>;
        if(successfulVerification === 1){
            alert('Your account is now verified!')
        }else{
            alert('This verification token is invalid.')
        }
    </script>
</body>

</html>