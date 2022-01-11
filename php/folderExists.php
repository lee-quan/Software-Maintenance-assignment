<?php

if(isset($_GET['folder_path']) && !empty($_GET['folder_path'])){
    $folder = $_GET['folder_path'];
    if(is_dir("../".$folder)){
        echo 1;
    }else{
        echo 0;
    }
}
?>