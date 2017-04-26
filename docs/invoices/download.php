<?php
session_start();
$data['user']=$_SESSION['user_id'];
$user=$_SESSION['user_id'];;
$file=$_POST['file'];
$path=$_SERVER['DOCUMENT_ROOT'].'/docs/invoices/'.$user.'/'.$file;
header('Content-type: application/pdf');
header("Content-Disposition: filename=".$file);
readfile($path);