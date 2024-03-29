<?php

$initial_mail = $_POST['email'];
$initial_name = $_POST['name'];
$initial_questions = $_POST['questions'];

if (!$initial_questions) {
    $text_message = "<b>Имя:</b> $initial_name<br>
    <b>Электронная почта:</b> $initial_mail";
} else if ($initial_questions) {
    $text_message = "<b>Имя:</b> $initial_name<br>
    <b>Электронная почта:</b> $initial_mail<br>
    <b>Вопрос:</b> $initial_questions";
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include dirname(__DIR__) . '/PHPMAILER/PHPMailer.php';
include dirname(__DIR__) . '/PHPMAILER/SMTP.php';
include dirname(__DIR__) . '/PHPMAILER/Exception.php';

function send_mail_by_PHPMailer($to, $from, $subject, $message)
{

    // SEND MAIL by PHP MAILER
    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';
    // $mail->isHTML(true);                                  // Set email format to HTML


    $yourEmail = 'testerovich18@yandex.ru'; 
    $password = 'wyjlesohzmmxzlby'; 

    // настройки SMTP
    $mail->Mailer = 'smtp';
    $mail->Host = 'ssl://smtp.yandex.ru';
    $mail->Port = 465;
    $mail->isSMTP();
    $mail->SMTPAuth = true;
    $mail->Username = $yourEmail; 
    $mail->Password = $password; 
    $mail->SMTPDebug = true;


    $mail->setFrom($yourEmail);

    $mail->addAddress($to);

    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if (!$mail->send()) {
        echo 'Ошибка: ' . $mail->ErrorInfo;
    }
}


if ($text_message && $initial_mail && $initial_name) {
    send_mail_by_PHPMailer('office@malstream.ru', 'testerovich18@yandex.ru', 'Заявка с сайта', '<div>' . $text_message . '</div>');
}