<?php
session_start();
include_once "php/config.php";
if (!isset($_SESSION['unique_id'])) {
    header("location: login.php");
}
$user_id = $_SESSION['user_id'];
$imageSubmitetd = 0;
$folder    = "labeled_images/$user_id";
//check if any folder exists, if no make a new directory

if (file_exists($folder)) {
    // echo 1;
    // $path    = "../".$_GET['dir'];
    $path    = $folder;
    $files = array_diff(scandir($path), array('.', '..'));

    // print_r(json_encode($files));
} else {
    // echo 0;
    mkdir($folder, 0777, true);
    $path    = $folder;
    $files = array_diff(scandir($path), array('.', '..'));
}

include_once "header.php";
$imageSubmitetd = (sizeof($files));
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
                        foreach ($files as $file) {
                            echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                            <div>
                            $file
                            </div>
                            <button class='btn btn-outline-danger unset' filename='$file'><i class='fas fa-trash'></i> </button></li>";
                        }
                        if ($imageSubmitetd < 3) {
                            $Left = 3 - $imageSubmitetd;
                        }
                        ?>
                    </ul>
                    <!-- <div class="d-flex">
                        <div class="me-auto">
                            Photo 1
                        </div>
                        <div>
                            <i class="fas fa-trash"></i>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex">
                        <div class="me-auto">
                            Photo 2
                        </div>
                        <div>
                            <i class="fas fa-trash"></i>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex">
                        <div class="me-auto">
                            Photo 3
                        </div>
                        <div>
                            <i class="fas fa-trash"></i>
                        </div>
                    </div>
                    <hr> -->

                </div>
                <div class="d-flex flex-column">
                    <!-- Add face button -->
                    <a href="face_unlock_settings.php" style="border-radius:20px;" type="button" class="btn btn-lg btn-outline-secondary <?php echo''.($imageSubmitetd < 3 ? '' : 'disabled') ?>" >
                        Add Face
                    </a>
                </div>
                <!-- End Add Photo Section -->
            </div>
        </section>
    </div>
</body>

<script>
    $(".unset").click(function(e) {
        e.preventDefault();
        // alert(this.attr("filename"));
        var filename = ($(this).attr("filename"));
        var completePath = ("../<?= $folder . "/" ?>" + filename);
        $.ajax({
            type: "GET",
            url: "php/unlinkPhoto.php",
            data: {
                path: completePath
            },
            success: function(response) {
                console.log(response);
                alert("Succesfully deleted");
                window.location.replace('facial_recog_add_photo.php')
            }
        });
    });
</script>