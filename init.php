<?php
require_once 'vendor/autoload.php';
date_default_timezone_set('Europe/Moscow');
$con = mysqli_connect("localhost", "root", "", "readme");
if (!$con) {
    die('Отсутствует подключение');
};
mysqli_set_charset($con, "utf8");
$transport = new Swift_SmtpTransport('smtp.gmail.com', 465, "ssl");
$transport->setUsername('info@readme.ru');
$transport->setPassword('12345readme');
$mailer = new Swift_Mailer($transport);
