<?php
session_start();
//var_dump($_POST);die();
$data['user']=$_SESSION['user_id'];
$user=$_SESSION['user_id'];;
$file=$_POST['file'];
$path=$_SERVER['DOCUMENT_ROOT'].'/docs/audios/'.$user.'/'.$file;
header('Content-type: audio/mp3');
header('Content-Disposition: attachment; filename="'.$file.'"');
readfile($path);