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
<h1>Не подверженное</h1>
<form method="post" action="./controller.php">
    <input type="text" name="text" placeholder="текст"><br>
    <input type="hidden" value="success" name="success">
    <input type="submit" name="send"></input>
</form>
<?php
// $text = $_POST['text'];
// $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
// $send = $_POST['send'];

// if ($send) {

//     $stmt = $pdo->prepare('INSERT INTO `post` (`text`) VALUES (:text)');
//     $stmt->bindParam('text', $text);
//     $stmt->execute();

// }

$stmt_posts = $pdo->prepare('SELECT * FROM success');
    $stmt_posts->execute();
    $fetch_posts = $stmt_posts->fetch();
while ($fetch_posts = $stmt_posts->fetch()) {
    echo $fetch_posts['text']."<br>";
}

?>