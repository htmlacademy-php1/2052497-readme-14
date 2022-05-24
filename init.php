<?php

require_once 'vendor/autoload.php';
require_once 'requisites.php';
date_default_timezone_set('Europe/Moscow');
$con = mysqli_connect($db_host, $db_username, $db_user_password, $db_name);
if (!$con) {
    die('Отсутствует подключение');
};
mysqli_set_charset($con, "utf8");

$transport = new Swift_SmtpTransport($smtp_server, $smtp_port, $smtp_protocol);
$transport->setUsername($smtp_email);
$transport->setPassword($smtp_password);
$mailer = new Swift_Mailer($transport);
