<?php
require_once 'helpers.php';
$is_auth = rand(0, 1);
$user_name = 'Евгений';
date_default_timezone_set('Europe/Moscow');
$con = mysqli_connect("localhost", "root", "","readme");
if (!$con) {
    die ('Отсутствует подключение');
};

mysqli_set_charset($con, "utf8");
$sql_types = 'SELECT * FROM type_content';
$result = mysqli_query($con, $sql_types);
$types = mysqli_fetch_all($result, MYSQLI_ASSOC);

if ($post_id = filter_input(INPUT_GET, 'id')) {
    $sql_post_id = $post_id;
};
/**Информация о посте и его создателе */
$sql_posts = "SELECT p.id, p.user_id, u.username, u.avatar, p.header, p.datatime_add, u.dt_add_user, t.type,
     p.quote_author, p.text_content, p.photo_content, p.video_content, p.link_content 
     FROM posts p 
     INNER JOIN users u ON p.user_id = u.id 
     INNER JOIN type_content t ON p.type_id = t.id
     WHERE p.id = $sql_post_id";
$result = mysqli_query($con, $sql_posts);
$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
foreach ($posts as $posts);

if ($posts['id'] != $post_id) {
    die ('Пост не найден');
};

/**Количество постов у пользователя */
$sql_count_post = "SELECT COUNT(id) amount FROM posts WHERE user_id = 3";
$result = mysqli_query($con, $sql_count_post);
$count_posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
foreach ($count_posts as $count_posts);

/**Количество подписчиков у пользователя */
$sql_count_follow = "SELECT COUNT(follower_id) amount FROM subscriptions WHERE user_id = 2";
$result = mysqli_query($con, $sql_count_follow);
$count_followers = mysqli_fetch_all($result, MYSQLI_ASSOC);
foreach ($count_followers as $count_followers);

/**Количество лайков у поста */
$sql_count_likes = "SELECT COUNT(user_id) amount FROM likes WHERE post_id = 1";
$result = mysqli_query($con, $sql_count_likes);
$count_likes = mysqli_fetch_all($result, MYSQLI_ASSOC);
foreach ($count_likes as $count_likes);

/**Количество комментариев к посту */
$sql_count_comment = "SELECT COUNT(content) amount FROM comments WHERE post_id = 1";
$result = mysqli_query($con, $sql_count_comment);
$count_comments = mysqli_fetch_all($result, MYSQLI_ASSOC);
foreach ($count_comments as $count_comments);

$post_info = include_template($posts['type'] . '.php', ['post' => $posts]);
$page_content = include_template('post-details.php', 
    ['post' => $posts, 'type' => $types, 'post_info' => $post_info,
    'count_posts' => $count_posts, 'count_followers' => $count_followers,
    'count_likes' => $count_likes, 'count_comments' => $count_comments]);
$layout_content = include_template('layout.php', ['page_content' => $page_content, 'user_name' => $user_name, 'is_auth' => $is_auth, 'page_title' => $posts['header']]);
print($layout_content);
?>
