<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<?php

session_start();
include_once "php/config.php";
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
        <div class="users">
            <header>

                <div class="content">
                    <?php
                    $sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$_SESSION['unique_id']}");
                    if (mysqli_num_rows($sql) > 0) {
                        $row = mysqli_fetch_assoc($sql);
                    }
                    ?>
                    <a href="face_unlock_settings.php" class="align-content-center" style="color: #333;"><i class="fas fa-arrow-left"></i></a>
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


            </header>
            <div style="min-height:500px; max-height:500px; " class="d-flex flex-column align-items-center justify-content-center">



                <h4>Must submit 3 picture</h4>

                <p>Image submitted: <?= $imageSubmitetd ?></p>

                <ul class="list-group-flush w-100">
                    <?php
                    foreach ($files as $file) {
                        echo "<li class='list-group-item d-flex justify-content-between align-items-center'>$file <button class='btn btn-outline-danger unset' filename='$file'><i class='fas fa-trash'></i> </button></li>";
                    }
                    if ($imageSubmitetd < 3) {
                        $Left = 3 - $imageSubmitetd;
                    }
                    ?>
                </ul>
                <br>

                <!-- OLD WAY -->
                
                <!-- <?php if ($imageSubmitetd < 3) : ?>
                    <form class="d-flex flex-column align-items-center" action="#" method="POST" enctype="multipart/form-data">
                        <input class="form-control mb-4" type="file" name="image">
                        <button class="btn btn-outline-secondary" type="submit" name='submit' id='faceData'> Submit Face Data </button>
                    </form>
                <?php else : ?>
                    <form class="d-flex flex-column align-items-center" action="#" method="POST" enctype="multipart/form-data">
                        <input class="form-control mb-4" type="file" name="image" disabled>
                        <button class="btn btn-outline-secondary" type="submit" name='submit' id='faceData' disabled> Submit Face Data </button>
                    </form>
                <?php endif ?> -->

                <!-- NEW WAY to disable buttons if submitted >= 3-->
                <form class="d-flex flex-column align-items-center" action="#" method="POST" enctype="multipart/form-data">
                    <input class="form-control mb-4" type="file" name="image" <?php echo"".($imageSubmitetd < 3 ? '' : 'disabled') ?>>
                    <button class="btn btn-outline-secondary" type="submit" name='submit' id='faceData' <?php echo"".($imageSubmitetd < 3 ? '' : 'disabled') ?>> Submit Face Data </button>
                </form>






            </div>
            </section>
        </div>
    </div>
</body>

<?php
// unlink("test.txt");

?>


<?php

function compressImage($source, $destination, $quality)
{
    // Get image info 
    $imgInfo = getimagesize($source);
    $mime = $imgInfo['mime'];

    // Create a new image from file 
    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($source);
            break;
        case 'image/png':
            $image = imagecreatefrompng($source);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($source);
            break;
        default:
            $image = imagecreatefromjpeg($source);
    }

    // Save image 
    imagejpeg($image, $destination, $quality);

    // Return compressed image 
    return $destination;
}


if (isset($_POST['submit'])) {
    echo "hihi";
    print_r($_FILES);
    if (isset($_FILES['image'])) {
        $img_name = $_FILES['image']['name'];
        $img_type = $_FILES['image']['type'];
        $tmp_name = $_FILES['image']['tmp_name'];

        $img_explode = explode('.', $img_name);
        $img_ext = end($img_explode);

        $extensions = ["jpeg", "png", "jpg"];
        if (in_array($img_ext, $extensions) === true) {
            $types = ["image/jpeg", "image/jpg", "image/png"];
            if (in_array($img_type, $types) === true) {
                $time = time();
                $new_img_name = $time . $img_name;
                if (compressImage($tmp_name, $folder . "/" . $new_img_name, 100)) {
                    echo "<script>alert('success'); window.location.replace('imageSubmit.php');</script>";
                }
            } else {
                echo "Please upload an image file - jpeg, png, jpg";
            }
        } else {
            echo "Please upload an image file - jpeg, png, jpg";
        }
    } else {
        echo "not set";
    }
}

?>

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
                window.location.replace('imageSubmit.php')
            }
        });
    });
</script>

<!-- <script>
    $('#faceData').click(function(e) {
        e.preventDefault();
        var file_data = $("#firstImg").prop("files")[0];
        var myFormData = new FormData();
        myFormData.append('file_data', pictureInput.files[0]);

        $.ajax({
            url: 'php/imageSubmit.php',
            type: 'POST',
            processData: false, // important
            contentType: false, // important
            dataType: 'json',
            data: myFormData
        });

    });
</script> -->