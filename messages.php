<?php

require_once 'helpers.php';
require_once 'init.php';
require_once 'session.php';
$get_penpal = null;
$penpal = [];
$messages = [];
$has_errors = [];

// Список пользавателей с кем была переписка
$sql_users = "SELECT u.id, u.username, u.avatar,
    (SELECT m.content FROM messages m
    WHERE u.id = m.to_user_id OR u.id = m.from_user_id
    ORDER BY m.id DESC
    LIMIT 1) AS content,
    (SELECT m.dt_add FROM messages m
    WHERE u.id = m.to_user_id OR u.id = m.from_user_id
    ORDER BY m.id DESC
    LIMIT 1) AS dt_add,
    (SELECT COUNT(*) FROM messages m 
    WHERE m.from_user_id = u.id AND m.to_user_id = $user_id AND m.new) AS new_message
    FROM users u
    LEFT JOIN messages m ON u.id = m.from_user_id OR u.id = m.to_user_id
    WHERE (m.to_user_id = $user_id AND m.to_user_id != u.id) OR (m.from_user_id = $user_id AND m.from_user_id != u.id)
    GROUP BY u.id
    ORDER BY dt_add DESC";
$res_users = mysqli_query($con, $sql_users);
$penpals = mysqli_fetch_all($res_users, MYSQLI_ASSOC);
// Получаем id собеседника, проверяем есть ли история переписки
if (filter_input(INPUT_GET, 'penpal')) {
    $get_penpal = htmlspecialchars($_GET['penpal']);
    $sql_history_mess = "SELECT id FROM messages
    WHERE (from_user_id = $get_penpal and to_user_id = $user_id) 
    OR (from_user_id = $user_id and to_user_id = $get_penpal)
    LIMIT 1";
    $res_check_mess = mysqli_query($con, $sql_history_mess);
    $check_penpal = mysqli_fetch_assoc($res_check_mess);
    //Если истории переписки нет то запрашиваем данные пользователя и вставляем в массив с собеседниками
    if (empty($check_penpal)) {
        $sql_user = "SELECT id, username, avatar FROM users WHERE id = $get_penpal";
        $res_user = mysqli_query($con, $sql_user);
        $new_penpal = mysqli_fetch_assoc($res_user);
        $new_penpal['content'] = '';
        $new_penpal['dt_add'] = '';
        $new_penpal['new_message'] = 0;
        array_unshift($penpals, $new_penpal);
    }
};
// Достаем Id первого пользователя
$penpal = current($penpals);
if (empty($get_penpal) && isset($penpal['id'])) {
    $get_penpal = $penpal['id'];
};
if (isset($get_penpal)) {
    $page = 'messages.php';
    // Валидация и запись сообщения
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_message = htmlspecialchars(trim($_POST['new_message']));
        $get_user_id = htmlspecialchars($_POST['user_id']);
        if (empty($new_message)) {
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

    // Запрос списка сообщений
    $sql_messages = "SELECT m.content, m.dt_add, u.id AS user_id, u.avatar, u.username FROM messages m
    LEFT JOIN users u ON m.from_user_id = u.id
    WHERE m.from_user_id IN ($user_id, $get_penpal) AND m.to_user_id IN ($user_id, $get_penpal)
    ORDER BY m.id ASC";
    $res_messages = mysqli_query($con, $sql_messages);
    $messages = mysqli_fetch_all($res_messages, MYSQLI_ASSOC);
    mysqli_query($con, "UPDATE messages SET `new` = 0 WHERE to_user_id = $user_id AND from_user_id = $get_penpal");
} else {
    $page = 'no-penpal.php';
};


$page_content = include_template(
    $page,
    [
        'has_errors' => $has_errors,
        'penpals' => $penpals,
        'messages' => $messages,
        'get_penpal' => $get_penpal,
        'user' => $user
    ]
);
$layout_content = include_template(
    'layout.php',
    [
        'page_content' => $page_content,
        'user' => $user,
        'page_title' => 'Сообщения'
    ]
);
print($layout_content);
