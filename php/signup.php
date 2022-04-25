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

session_start();
include_once "config.php";
$fname = mysqli_real_escape_string($conn, $_POST['fname']);
$lname = mysqli_real_escape_string($conn, $_POST['lname']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['password']);
if (!empty($fname) && !empty($lname) && !empty($email) && !empty($password)) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $sql = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
        if (mysqli_num_rows($sql) > 0) {
            echo "$email - This email already exist!";
        } else {
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
                        $data = file_get_contents( $tmp_name );
                        $base64 = 'data:' . $img_type . ';base64,' . base64_encode($data);
                        // echo "type = ". $img_type;

                        $ran_id = rand(time(), 100000000);
                        $status = "Active now";
                        $encrypt_pass = md5($password);
                        $gen = "INSERT INTO users (unique_id, fname, lname, email, password, img, img_type, status)
                                VALUES ({$ran_id}, '{$fname}','{$lname}', '{$email}', '{$encrypt_pass}', '".base64_encode($data)."','".$img_type."','{$status}')";
                        $insert_query = mysqli_query($conn, $gen);

                        if ($insert_query) {
                            $select_sql2 = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
                            if (mysqli_num_rows($select_sql2) > 0) {
                                $result = mysqli_fetch_assoc($select_sql2);
                                $_SESSION['unique_id'] = $result['unique_id'];
                                $_SESSION['user_id'] = $result['user_id'];
                                echo "success";
                            } else {
                                echo "This email address not Exist!";
                            }
                        } else {
                            echo "Something went wrong. Please try again!";
                        }
                    }
                } else {
                    echo "Please upload an image file - jpeg, png, jpg";
                }
            } else {
                echo "Please upload an image file - jpeg, png, jpg";
            }
        }
    } else {
        echo "$email is not a valid email!";
    }
} else {
    echo "All input fields are required!";
}
