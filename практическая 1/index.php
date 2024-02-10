<?php
error_reporting(0);
$connect = mysqli_connect('localhost', 'root', '', 'security');
session_start();

?>
<h1>Авторизация</h1>
<form method="post" action="./">
    <input type="text" name="login" placeholder="Логин">
    <input type="password" name="password" placeholder="Пароль">
    <input type="submit" name="send"></input>
</form>
<?php
$login = $_POST['login'];
$password = $_POST['password'];
$send = $_POST['send'];
if ($send) {
    $str = "SELECT * FROM `users` WHERE `login`='$login' AND `password`='$password'";
    echo $str;
    $query = mysqli_query($connect, $str);
    $fetch = mysqli_fetch_array($query);
    $check = mysqli_num_rows($query);

    if ($check == 1) {
        $_SESSION['role'] = $fetch['role'];
    } else {
        echo "Неверный логин или пароль";
    }
}
if($_SESSION['role']) {
     if ($_SESSION['role'] == 2) {
        header('Location:./admin.php');
    } else {
        header('Location:./user.php');
    }
}
?>
