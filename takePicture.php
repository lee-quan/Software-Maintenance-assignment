<?php

session_start();
include_once "php/config.php";
$user_id = $_SESSION['user_id'];

include_once "header.php";

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
                <h3 class="mt-4">Take a Picture</h3>
                <!-- <input hidden type="file" id="mypic" accept="image/*" capture="camera" capture> -->

                <div class="w-100 d-flex flex-column my-4 align-items-center">
                    <video class="w-100" muted id="video" playsinline autoplay></video>
                    <canvas hidden id="canvas" class=""></canvas>
                </div>

                <div class="controller d-flex justify-content-between">
                    <button class="btn btn-outline-secondary" id="snap">Capture</button>
                    <button hidden class="btn btn-outline-secondary mx-2" id="retake">Retake Photo</button>
                    <form name="form" id="form1" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                        <input value="" id="my_hidden_img" hidden type="text" name="image">
                        <button hidden class="btn btn-outline-success mx-2" id="submit" type="submit" name="submit">Submit Photo</button>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <script>
        'use strict';

        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const snap = document.getElementById("snap");
        const errorMsgElement = document.querySelector('span#errorMsg');

        const constraints = {
            audio: false,
            video: {
                width: 1280,
                height: 720
            }
        };

        // Access webcam
        async function init() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia(constraints);
                handleSuccess(stream);
            } catch (e) {
                errorMsgElement.innerHTML = `navigator.getUserMedia error:${e.toString()}`;
            }
        }

        // Success
        function handleSuccess(stream) {
            window.stream = stream;
            video.srcObject = stream;
        }

        // Load init
        init();

        // Draw image
        var context = canvas.getContext('2d');

        const videoFrame = document.getElementById("video");
        const pictureCanvas = document.getElementById("canvas");
        const retakeButton = document.getElementById("retake");
        const submitButton = document.getElementById("submit");

        snap.addEventListener("click", function() {
            context.drawImage(video, 0, 0, 300, 160);
            videoFrame.hidden = true;
            pictureCanvas.hidden = false;
            snap.hidden = true;
            retakeButton.hidden = false;
            submitButton.hidden = false;

            var image = new Image();
            image.src = canvas.toDataURL('image/jpeg', 1.0);
            // document.getElementById('my_hidden_img').src = image;
            document.getElementById('my_hidden_img').value = canvas.toDataURL();
        });

        retake.addEventListener("click", function() {
            videoFrame.hidden = false;
            pictureCanvas.hidden = true;
            snap.hidden = false;
            retakeButton.hidden = true;
            submitButton.hidden = true;
        });
    </script>
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
        $img = $_POST['image'];

        $gen = "INSERT INTO face_unlock (user_id, img, img_id, img_type)
        VALUES ('{$user_id}','" . str_replace('data:image/png;base64,', '', $img) . "','" . time() . "','image/png')";
        $insert_query = mysqli_query($conn, $gen);

        if ($insert_query) {
            echo "<script>alert('success');</script>";
        } else {
            echo "Something went wrong. Please try again!";
        }
        // echo '<img src="'.$img.'" alt="">';
        // $img = str_replace('data:image/png;base64,', '', $img);
        // $img = str_replace(' ', '+', $img);
        // $fileData = base64_decode($img);
        // //saving
        // $fileName = 'photo.jpg';
        // file_put_contents($fileName, $fileData);

        // $time = time();
        // $new_img_name = $time . $img_name . ".jpg";

        // echo "<script>alert('success'); window.location.replace('facial_recog_add_photo.php');</script>";
    }


    ?>
</body>