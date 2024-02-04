<?php
session_start();
if ($_SESSION['role'] != 2) {
    header('Location:/');
}
?>
<h1>Админ</h1>