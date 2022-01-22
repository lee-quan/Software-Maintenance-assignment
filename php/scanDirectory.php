<?php



$path    = "../".$_GET['dir'];

if(file_exists($path)){
    $files = array_diff(scandir($path), array('.', '..'));
    print_r(json_encode($files));
}else{
    
}
