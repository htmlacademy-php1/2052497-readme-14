<?php
require_once 'helpers.php';
require_once 'init.php';
session_start();
if (isset($_SESSION['username'])) {
    header("Location: /feed.php");
};
$has_errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = htmlspecialchars($_POST['login']);
    $password = htmlspecialchars($_POST['password']);
    $user_data = [];
    if (empty($login)) {
        $has_errors['login'] = 'Введите логин';
    }
    if (empty($password)) {
        $has_errors['password'] = 'Введите пароль';
    }
    if (empty($has_errors)) {
        $sql_user_data = "SELECT * FROM users WHERE username = '$login'";
        $res_user_data = mysqli_query($con, $sql_user_data);
        $user_data = mysqli_fetch_assoc($res_user_data);
        if (empty($user_data['username'])) {
            $has_errors['login'] = 'Пользователь с таким логином не найден';
        } elseif (!password_verify($password, $user_data['password'])) {
            $has_errors['password'] = 'Неверный пароль';
        }
    }
    if (empty($has_errors)) {
        $_SESSION = $user_data;
        header("Location: /feed.php");
    };
};

$layout_content = include_template('login-layout.php', ['has_errors' => $has_errors]);
print($layout_content);
