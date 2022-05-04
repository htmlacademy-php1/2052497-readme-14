<?php
require_once 'helpers.php';
require_once 'init.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $has_errors = [];
    $avatar = null;
    $email = htmlspecialchars($_POST['email']);
    $login = htmlspecialchars($_POST['login']);
    $password = htmlspecialchars($_POST['password']);
    $password_repeat = htmlspecialchars($_POST['password-repeat']);
    
    // Проверка емэйла на пустоту, корректность и уникальность.
    if (empty($email)) {
        $has_errors['email'] = 'Введите email';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $has_errors['email'] = 'Некорректный email';
    } else {       
        $sql_email = "SELECT email FROM users WHERE email = '$email'";
        $result_email = mysqli_query($con, $sql_email);
        $same_email = mysqli_fetch_assoc($result_email);
        if (isset($same_email)) {
            $has_errors['email'] = 'Пользователь с таким email уже существует';
        };
    };
    // Проверка логина на пустоту и уникальность.
    if (empty($login)) {
        $has_errors['login'] = 'Введите логин';
    } else {        
        $sql_login = "SELECT username FROM users WHERE username = '$login'";
        $result_login = mysqli_query($con, $sql_login);
        $same_login = mysqli_fetch_assoc($result_login);
        if (isset($same_login)) {
            $has_errors['login'] = 'Пользователь с таким логином уже существует';
        };
    };

    // Проверка паролей на пустоту и совпадение
    if (empty($password)) {
        $has_errors['password'] = 'Введите пароль';
    };
    if (empty($password_repeat)) {
        $has_errors['password-repeat'] = 'Повторите пароль';
    };
    if ($password_repeat !== $password) {
        $has_errors['different-password'] = 'Пароли не совпадают';
    } else {
        $password = password_hash($password, PASSWORD_DEFAULT);
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
