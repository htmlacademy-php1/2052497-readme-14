<?php
require_once 'init.php';
require_once 'session.php';
require_once 'vendor/autoload.php';


if (filter_input(INPUT_GET, 'user_id')) {
    $user = htmlspecialchars($_GET['user_id']);
    $follower = $_SESSION['id'];
    //Проверка существования пользователя и подписки
    $sql_sub = "SELECT DISTINCT u.id, u.username, email, s.follower_id FROM users u
    LEFT JOIN subscriptions s ON s.follower_id = $follower and s.user_id = $user
    WHERE id = $user";
    $res_sub = mysqli_query($con, $sql_sub);
    $check = mysqli_fetch_assoc($res_sub);
    // Запись подписки если ее нет, или удаление если есть
    if (isset($check['id']) && empty($check['follower_id']) && $user !== $follower) {
        $sql_subscribe = "INSERT INTO subscriptions (follower_id, user_id) VALUES (?,?)";
        $subscribe_pre = mysqli_prepare($con, $sql_subscribe);
        mysqli_stmt_bind_param($subscribe_pre, 'ss', $follower, $user);
        $res_subscribe = mysqli_stmt_execute($subscribe_pre);
        $message = new Swift_Message();
        $message->setTo($check['email']);
        $message->setFrom('info@readme.ru');
        $message->setSubject("У вас новый подписчик");
        $message->setBody("Здравствуйте, " . $check['username'] . ". На вас подписался новый пользователь " . $_SESSION['username'] . ". Вот ссылка на его профиль: http://readme/profile.php?user=" . $_SESSION['id']);
        $mailer = new Swift_Mailer($transport);
        $mailer->send($message);
    } elseif (isset($check['id']) && isset($check['follower_id'])) {
        $sql_subscribe = "DELETE FROM subscriptions WHERE user_id = $user AND follower_id = $follower";
        $subscribe_pre = mysqli_query($con, $sql_subscribe);
    };
};
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
