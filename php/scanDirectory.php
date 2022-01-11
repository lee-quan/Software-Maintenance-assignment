<?php

$path    = "../".$_GET['dir'];
$files = array_diff(scandir($path), array('.', '..'));

print_r(json_encode($files));
