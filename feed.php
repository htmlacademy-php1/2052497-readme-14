<?php
require_once 'init.php';
require_once 'session.php';
require_once 'helpers.php';

$types = get_all_types($con);
$get_type = "";
$get_type_id = filter_input(INPUT_GET, 'type');
if ($get_type_id) {
    foreach ($types as $type) {
        if ($type['id'] === $get_type_id) {
            $get_type = $type['type'];
        };
    };
};
// Получаем id подписок и записывае их в строку
$sql_subscriptions = "SELECT user_id as id FROM subscriptions WHERE follower_id = $user_id";
$res_subscriptions = mysqli_query($con, $sql_subscriptions);
$subscriptions = mysqli_fetch_all($res_subscriptions, MYSQLI_ASSOC);
$subscriptions_id = [];
foreach ($subscriptions as $subscriber) {
    $subscriptions_id[] = $subscriber['id'];
};
$sql_sub_id = implode(", ", $subscriptions_id);
// Фильтрация по типу контента
$sql_sort_type = "";
if (in_array($get_type, array_column($types, 'type'))) {
    $sql_sort_type = " AND t.type = " . "'$get_type'";
};
// Запрос постов для ленты
$posts = [];
if (count($subscriptions) > 0) {
    $sql_posts = "SELECT p.id, p.user_id, u.username, u.avatar, p.header, u.dt_add, t.type,
    p.quote_author, p.text_content, p.photo_content, p.video_content, p.link_content, p.view_count, 
    COUNT(DISTINCT c.id) AS comments_count, COUNT(DISTINCT l.user_id) AS likes_count,
    (SELECT COUNT(*) FROM posts  WHERE repost = p.id) AS reposts_count
    FROM posts p 
    INNER JOIN users u ON p.user_id = u.id 
    INNER JOIN type_content t ON p.type_id = t.id
    LEFT JOIN comments c ON c.post_id = p.id
    LEFT JOIN likes l ON l.post_id = p.id
    WHERE p.user_id IN ($sql_sub_id)$sql_sort_type
    GROUP BY p.id, c.post_id, l.post_id
    ORDER BY p.dt_add DESC";
    $result_posts = mysqli_query($con, $sql_posts);
    $posts = mysqli_fetch_all($result_posts, MYSQLI_ASSOC);
};

$page_content = include_template('feed-user.php', ['types' => $types, 'get_type' => $get_type, 'get_type_id' => $get_type_id, 'posts' => $posts]);
$layout_content = include_template('layout.php', ['page_content' => $page_content, 'user' => $user, 'page_title' => 'Readme: Лента']);
print($layout_content);
