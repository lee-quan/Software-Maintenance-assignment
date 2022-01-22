<?php
session_start();
include_once "php/config.php";
if (!isset($_SESSION['unique_id'])) {
    header("location: login.php");
}


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
                    <a href="facial_recog_add_photo.php" class="align-content-center" style="color: #333;"><i class="fas fa-arrow-left"></i></a>
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
            <div style="min-height:500px; max-height:500px; "class="d-flex align-items-center justify-content-center">
                <div class="d-flex flex-column gap-3">
                    <a href="imageSubmit.php" style="border-radius:20px;" type="button" class="btn btn-lg btn-outline-secondary">
                        Upload Photo
                    </a>
                    <hr>
                    <a href="takePicture.php" style="border-radius:20px;" type="button" class="btn btn-lg btn-outline-secondary">
                        Take Photo
                    </a>
                </div>
            </div>
        </section>
    </div>
</body>