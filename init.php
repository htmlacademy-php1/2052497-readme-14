<?php
$is_auth = rand(0, 1);
$user_name = 'Евгений';
date_default_timezone_set('Europe/Moscow');
$con = mysqli_connect("localhost", "root", "","readme");
if (!$con) {
    die ('Отсутствует подключение');
};
mysqli_set_charset($con, "utf8");
?>