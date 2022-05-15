<?php
require_once 'helpers.php';
require_once 'init.php';
require_once 'session.php';
$user_id = $user['id'];
$get_penpal_id = '';
$messages = '';
$has_errors = [];

// Список пользавателей с кем была переписка
$sql_users = "SELECT DISTINCT m.id, m.content, m.dt_add, u.id, u.username, u.avatar FROM messages m
LEFT JOIN users u ON (m.to_user_id = u.id OR m.from_user_id = u.id) AND u.id != $user_id
WHERE m.to_user_id OR m.from_user_id = $user_id
GROUP by u.id
ORDER BY m.id DESC";
$res_users = mysqli_query($con, $sql_users);
$penpals = mysqli_fetch_all($res_users, MYSQLI_ASSOC);
$penpal = current($penpals);
$get_penpal_id = $penpal['id'];
if(filter_input(INPUT_GET, 'penpal')) {
    $get_penpal_id = htmlspecialchars($_GET['penpal']);
};
// Запрос списка сообщений
if (isset($get_penpal_id)) {
$sql_messages = "SELECT m.content, m.dt_add, u.id AS user_id, u.avatar, u.username FROM users u
LEFT JOIN messages m ON m.from_user_id = u.id
WHERE m.from_user_id IN ($user_id, $get_penpal_id) AND m.to_user_id IN ($user_id, $get_penpal_id)
GROUP BY m.id
ORDER BY m.id ASC";
$res_messages = mysqli_query($con, $sql_messages);
$messages = mysqli_fetch_all($res_messages, MYSQLI_ASSOC);
};

// Валидация и запись сообщения
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_message = htmlspecialchars(trim($_POST['new_message']));
    $get_user_id = htmlspecialchars($_POST['user_id']);
    if(empty($new_message)) {
        $has_errors['message'] = "Поле не может быть пустым";
    };
    if (empty($has_errors)) {
        $check_user = "SELECT id FROM users WHERE id = $get_user_id";
        $res_check = mysqli_query($con, $check_user);
        $check = mysqli_fetch_assoc($res_check);
        if (isset($check)) {
            $sql_message = 'INSERT INTO messages (from_user_id, to_user_id, content) VALUES (?, ?, ?)';
            $add_message = mysqli_prepare($con, $sql_message);
            mysqli_stmt_bind_param($add_message, 'sss', $user_id, $get_user_id, $new_message);
            $res_messages = mysqli_stmt_execute($add_message);
            $check_insert = mysqli_insert_id($con);
            if (empty($check_insert)) {
                $has_errors['message'] = "Не удалось отправить сообщение";
            } else {
                header("Location: " . $_SERVER['HTTP_REFERER']);
            }
        } else {
            $has_errors['message'] = "Пользователь не найден!";           
        };
    };
};


$page_content = include_template('messages.php', ['has_errors' => $has_errors, 'penpals' => $penpals, 'messages' => $messages, 'get_penpal_id' => $get_penpal_id, 'user' => $user]);
$layout_content = include_template('layout.php', ['page_content' => $page_content, 'user' => $user, 'page_title' => 'Сообщения']);
print($layout_content);


?>