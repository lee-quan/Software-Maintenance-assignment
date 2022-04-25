<?php
session_start();
include_once "php/config.php";
if (!isset($_SESSION['unique_id'])) {
    header("location: login.php");
}
$user_id = $_SESSION['user_id'];

$sql1 = mysqli_query($conn, "SELECT * FROM face_unlock WHERE user_id = $user_id");
$sql2 = mysqli_query($conn, "SELECT count(user_id) as count1 FROM face_unlock WHERE user_id = $user_id");
if (mysqli_num_rows($sql2) > 0) {
    $row2 = mysqli_fetch_assoc($sql2);
}
$count = $row2['count1'];
include_once "header.php";

?>

<body>
    <div class="wrapper">
        <section class="users">
            <!-- Header containing Back Btn, Profile Pic, Name, Dropdown -->
            <header>
                <div class="content">
                    <?php
                    $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
                    if (mysqli_num_rows($sql) > 0) {
                        $row = mysqli_fetch_assoc($sql);
                    }
                    ?>
                    <a href="users.php" class="align-content-center" style="color: #333;"><i class="fas fa-arrow-left"></i></a>
                    <img src="php/images/<?php echo $row['img']; ?>" alt="">
                    <div class="details">
                        <span><?php echo $row['fname'] . " " . $row['lname'] ?></span>
                        <p><?php echo $row['status']; ?></p>
                    </div>
                </div>
                <div class="dropdown" style="margin-right: 20px">
                    <i class="fas fa-ellipsis-v fa-lg" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false"></i>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <div>
                            <li><a class="dropdown-item" href="#">Face Unlock Settings</a></li>
                            <hr>
                            <li><a class="dropdown-item" href="php/logout.php?logout_id=<?php echo $row['unique_id']; ?>">Logout</a></li>
                        </div>
                    </ul>
                </div>
                <!-- <a href="php/logout.php?logout_id=<?php echo $row['unique_id']; ?>" class="logout">Logout</a> -->
            </header>
            <!-- Upload picture section -->
            <div style="min-height:500px; max-height:500px; " class="d-flex flex-column align-items-center justify-content-center ">
                <!-- Add Photo Section -->
                <div class="d-flex flex-column align-items-center w-100">
                    <h3 class="mb-4">Submitted Photos</h3>

                    <ul class="list-group-flush w-100 ">
                        <?php
                        while($row = mysqli_fetch_assoc($sql1)){
                            echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                            <img src='data:".$row['img_type'].";base64,".$row["img"]."' width=50 height=50>
                            <button class='btn btn-outline-danger unset' id='".$row['img_id']."' onClick='unset(this.id)'><i class='fas fa-trash'></i> </button></li>";
                        }
                        ?>
                    </ul>


                </div>
                <div class="d-flex flex-column">
                    <!-- Add face button -->
                    <a href="face_unlock_settings.php" style="border-radius:20px;" type="button" class="btn btn-lg btn-outline-secondary <?php echo''.($count < 3 ? '' : 'disabled') ?>" >
                        Add Face
                    </a>
                </div>
                <!-- End Add Photo Section -->
            </div>
        </section>
    </div>
</body>

<script>
    function unset(clicked_id){
        // alert(clicked_id);
        $.ajax({
            type: "GET",
            url: "php/unlinkPhoto.php",
            data: {
                id: clicked_id
            },
            success: function(response) {
                // alert(response);
                window.location.replace('facial_recog_add_photo.php')
            }
        });
    }
</script>
