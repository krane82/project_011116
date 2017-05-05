<?php
session_start();
$action=$_POST['action'];
$data['user']=$_SESSION['user_id'];
if(!empty($_POST['folder']))
{
    $folder=$_POST['folder'];
}   else {
    $folder = $_SESSION['user_id'];
}
$file=$_POST['file'];
$path=$_SERVER['DOCUMENT_ROOT'].'/docs/audios/'.$folder.'/'.$file;
if(file_exists($path)) {
    header('Content-type: audio/mp3');
    header('Content-Disposition: attachment; filename="' . $file . '"');
    readfile($path);
}