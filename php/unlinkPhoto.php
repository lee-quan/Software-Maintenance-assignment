<?php

print_r("hi");
if(isset($_GET['path'])){
    $file = $_GET['path'];
    unlink($file);
    echo "success";
}
?>