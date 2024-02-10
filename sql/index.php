<?php
error_reporting(0);
$connect = mysqli_connect('localhost', 'root', '', 'security');
session_start();
$host = 'localhost';
$db   = 'security';
$user = 'root';
$pass = '';
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, $user, $pass, $opt);



?>
<h1>Подготовленный запрос</h1>
<form method="post" action="./">
    <input type="text" name="login" placeholder="Логин">
    <input type="password" name="password" placeholder="Пароль">
    <input type="submit" name="send"></input>
</form>
<?php
$login = $_POST['login'];
$password = $_POST['password'];
$send = $_POST['send'];

$login = addslashes($login);
$password = addslashes($password);

if ($send) {

    $stmt = $pdo->prepare('SELECT * FROM users WHERE `login`=:login AND `password`=:password');
    $stmt->bindParam('login', $login);
    $stmt->bindParam('password', $password);
    $stmt->execute();
    $check = $stmt->rowCount();
    $fetch = $stmt->fetch();

    if ($check == 1) {
        $_SESSION['role'] = $fetch['role'];
    } else {
        echo "Неверный логин или пароль";
    }
}
if ($_SESSION['role']) {
    if ($_SESSION['role'] == 2) {
        header('Location:./admin.php');
    } else {
        header('Location:./user.php');
    }
}
?>