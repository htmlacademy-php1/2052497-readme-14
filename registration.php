<?php
require_once 'helpers.php';
require_once 'init.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $has_errors = [];
    $avatar = null;
    // Проверка емэйла на пустоту, корректность и уникальность.
    if (empty($_POST['email'])) {
        $has_errors['email'] = 'Введите email';
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $has_errors['email'] = 'Некорректный email';
    } else {
        $sql_email = 'SELECT email FROM users';
        $result_email = mysqli_query($con, $sql_email);
        $all_emails = mysqli_fetch_all($result_email, MYSQLI_ASSOC);

        if (in_array($_POST['email'], array_column($all_emails, 'email'))) {
            $has_errors['email'] = 'Пользователь с таким email уже существует';
        } else {
            $email = $_POST['email'];
        };
    };

    // Проверка логина на пустоту и уникальность.
    if (empty($_POST['login'])) {
        $has_errors['login'] = 'Введите логин';
    } else {
        $sql_login = 'SELECT username FROM users';
        $result_login = mysqli_query($con, $sql_login);
        $all_login = mysqli_fetch_all($result_login, MYSQLI_ASSOC);

        if (in_array($_POST['login'], array_column($all_login, 'username'))) {
            $has_errors['login'] = 'Пользователь с таким логином уже существует';
        } else {
            $login = $_POST['login'];
        };
    };

    // Проверка паролей на пустоту и совпадение
    if (empty($_POST['password'])) {
        $has_errors['password'] = 'Введите пароль';
    };
    if (empty($_POST['password-repeat'])) {
        $has_errors['password-repeat'] = 'Повторите пароль';
    };
    if ($_POST['password-repeat'] != $_POST['password']) {
        $has_errors['different-password'] = 'Пароли не совпадают';
    } else {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    };
    // Проверка аватакри на соответствие формату и размеру, сохранение на сервере   
    if (!empty($_FILES['photo']['name'])) {
        $mime_type = $_FILES['photo']['type'];
        $rules_photo = [
            [
                'mime_type' => 'image/png',
                'type' => '.png'
            ],
            [
                'mime_type' => 'image/jpeg',
                'type' => '.jpeg'
            ]
        ];
        foreach ($rules_photo as $rule_photo) {
            if ($mime_type === $rule_photo['mime_type']) {
                $type_file = $rule_photo['type'];
            };
        };
        if (!$type_file) {
            $has_errors['file'] = 'Неверный формат изображения';
        } elseif ($_FILES['photo']['size'] > 1 * 1024 * 1024) {
            $has_errors['file'] = 'Размер файла до 2MB';
        };
        if (!$has_errors && !empty($_FILES['photo']['name'])) {
            $file_name = uniqid() . $type_file;
            $file_path = __DIR__ . '/uploads/';
            $avatar = '/uploads/' . $file_name;
            move_uploaded_file($_FILES['photo']['tmp_name'], $file_path . $file_name);
        };
    };
    if (!$has_errors) {
        $sql_add_user = 'INSERT INTO users (email, username, password, avatar) VALUES (?, ?, ?, ?)';
        $add_user = mysqli_prepare($con, $sql_add_user);
        mysqli_stmt_bind_param($add_user, 'ssss', $email, $login, $password, $avatar);
        $res_add_user = mysqli_stmt_execute($add_user);

        if ($res_add_user) {
            header("Location: /index.php");
            exit;
        } else {
            $has_errors['add-user'] = 'НЕ УДАЛОСЬ ЗАГРУЗИТЬ ДАННЫЕ';
        };
    };
};

$page_content = include_template('reg-user.php', ['has_errors' => $has_errors]);
$layout_content = include_template('layout.php', ['page_content' => $page_content, 'user_name' => $user_name, 'is_auth' => $is_auth, 'page_title' => 'readme: Регистрация']);
print($layout_content);
