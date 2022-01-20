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

$imageSubmitetd = (sizeof($files));
?>

<h3>Must submit 3 picture</h3>

<p>Image submitted: <?= $imageSubmitetd ?></p>

<?php
// unlink("test.txt");

?>

<ul>
    <?php
    foreach ($files as $file) {
        echo "<li>$file <button class='unset' filename='$file'> delete </button></li>";
    }
    if ($imageSubmitetd < 3) {
        $Left = 3 - $imageSubmitetd;

    }
    ?>
</ul>
<br>
<?php if ($imageSubmitetd < 3) : ?>
    <form action="#" method="POST" enctype="multipart/form-data">
        <input type="file" name="image">
        <button type="submit" name='submit' id='faceData'> Submit Face Data </button>
    </form>
<?php else: ?>
    <form action="#" method="POST" enctype="multipart/form-data">
        <input type="file" name="image" disabled>
        <button type="submit" name='submit' id='faceData' disabled> Submit Face Data </button>
    </form>
<?php endif ?>
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
        echo "nto set";
    }
}

?>

<script>
$(".unset").click(function (e) { 
    e.preventDefault();
    // alert(this.attr("filename"));
    var filename = ($(this).attr("filename"));
    var completePath = ("../<?= $folder."/"?>"+filename);
    $.ajax({
        type: "GET",
        url: "php/unlinkPhoto.php",
        data: {
            path : completePath
        },
        success: function (response) {
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