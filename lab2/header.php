<?php


error_reporting(0);
$headers = getallheaders();
$token = $headers['Authorization'];

$connect = mysqli_connect('localhost', 'root', '', 'security');
$str = "SELECT * FROM `users` WHERE `token`='$token'";
$query = mysqli_query($connect, $str);
$check = mysqli_num_rows($query);

if ($check == 1) {
    echo "Вы авторизованны";
} else {
    echo "Вы не авторизованны";
}

