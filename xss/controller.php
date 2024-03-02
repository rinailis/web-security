<?php
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



$text = $_POST['text'];
if ($_POST['success']) {
    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
$send = $_POST['send'];

if ($send) {
    if ($_POST['success']) {
        $stmt = $pdo->prepare('INSERT INTO `success` (`text`) VALUES (:text)');
    }else {
        $stmt = $pdo->prepare('INSERT INTO `post` (`text`) VALUES (:text)');
    }
    $stmt->bindParam('text', $text);
    $stmt->execute();
}
if ($_POST['success']) {
    header('Location:./success.php');
} else {
    header('Location:./');
}


?>