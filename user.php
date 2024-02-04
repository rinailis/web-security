<?php
session_start();
if (!$_SESSION['role']) {
    header('Location:/');
}
?>
<h1>Пользователь</h1>
