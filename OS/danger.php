<?php
$file_name = $_GET['file_name'];
$result_code = null;

if (isset($file_name)) {
    
    system("echo. > ".$file_name, $result_code);
    if ($result_code === 0) {
        echo "Файл  ".$file_name."  создан успешно";
    } else {
        echo $file_name." не является внутренней или внешней
        командой, исполняемой программой или пакетным файлом.";
    }
}

