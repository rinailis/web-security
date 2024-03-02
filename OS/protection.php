<?php
$file_name = $_GET['file_name'];
$result_code = null;

if (isset($file_name)) {
    // $filter = preg_replace('/\s+/', '_', $file_name);
    $file_name = escapeshellarg($file_name);

    system("echo. > ".$file_name, $result_code);
    if ($result_code === 0) {
        echo "Файл  ".$file_name."  создан успешно";
    } else {
        echo $file_name." не является внутренней или внешней
        командой, исполняемой программой или пакетным файлом.";
    }
}

// $file_name = escapeshellcmd ($file_name);
// system("cd C:\OSPanel\domains\web-security\OS\ del ".$file_name.".pdf", $result_code);

// if (preg_match('/^[A-Za-z]+$/',  $file_name)) {
// } else {
//     echo 'Допустимы только строчные и заглавные латинские буквы';
// }