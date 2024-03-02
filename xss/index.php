<?php
// error_reporting(0);
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
<h1>Подверженное</h1>
<form method="post" action="./controller.php">
    <input type="text" name="text" placeholder="текст"><br>
    <input type="submit" name="send"></input>
</form>
<?php
// $text = $_POST['text'];
// $send = $_POST['send'];

// if ($send) {

//     $stmt = $pdo->prepare('INSERT INTO `post` (`text`) VALUES (:text)');
//     $stmt->bindParam('text', $text);
//     $stmt->execute();

// }

$stmt_posts = $pdo->prepare('SELECT * FROM post');
    $stmt_posts->execute();
    $fetch_posts = $stmt_posts->fetch();
while ($fetch_posts = $stmt_posts->fetch()) {
    echo $fetch_posts['text']."<br>";
}

?>