<?php

require_once 'helpers.php';
require_once 'init.php';
require_once 'session.php';
$user_id = $_SESSION['id'];
$post_id = htmlspecialchars(filter_input(INPUT_GET, 'post_id'));
//Проверка наличия поста
$sql_post = "SELECT `user_id` FROM `posts` WHERE `id` = $post_id";
$res_post_user = mysqli_fetch_assoc(mysqli_query($con, $sql_post));
$post_user_id = $res_post_user['user_id'];
//Репост
if (isset($post_user_id) && $post_user_id !== $user_id) {
    $sql_repost = "INSERT INTO `posts` (`user_id`, `repost`, `type_id`, `header`, `quote_author`, 
    `text_content`, `photo_content`, `video_content`, `link_content`, `creator`)
    SELECT $user_id, `id`, `type_id`, `header`, `quote_author`,`text_content`, `photo_content`, `video_content`, `link_content`, `user_id`
    FROM `posts`
    WHERE `id` = $post_id";
    mysqli_query($con, $sql_repost);
    header("Location: /profile.php?user=$user_id");
    exit;
};
header("Location: " . $_SERVER['HTTP_REFERER']);
exit;
