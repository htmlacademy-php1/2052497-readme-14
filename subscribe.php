<?php
require_once 'init.php';
require_once 'session.php';

if (filter_input(INPUT_GET, 'sub')) {
    $user = htmlspecialchars($_GET['sub']);
    $follower = $_SESSION['id'];
    $sql_sub = "SELECT id FROM users WHERE id = $user";
    $res_sub = mysqli_query($con, $sql_sub);
    $check = mysqli_fetch_assoc($res_sub);
    if (isset($check) && $user !== $follower) {
        $sql_subscribe = "INSERT INTO subscriptions (follower_id, user_id) VALUES (?,?)";
        $subscribe_pre = mysqli_prepare($con, $sql_subscribe);
        mysqli_stmt_bind_param($subscribe_pre, 'ss', $follower, $user);
        $res_subscribe = mysqli_stmt_execute($subscribe_pre);
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
};
if (filter_input(INPUT_GET, 'unsub')) {
    $user = htmlspecialchars($_GET['unsub']);
    $follower = $_SESSION['id'];
    $sql_sub = "SELECT id FROM users WHERE id = $user";
    $res_sub = mysqli_query($con, $sql_sub);
    $check = mysqli_fetch_assoc($res_sub);
    if (isset($check) && $user !== $follower) {
        $sql_subscribe = "DELETE FROM subscriptions WHERE user_id = $user AND follower_id = $follower";
        $subscribe_pre = mysqli_query($con, $sql_subscribe);        
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
} else {
header("Location: /feed.php");
exit;
};


?>