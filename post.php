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
$sql_post = "SELECT p.id, p.user_id, u.username, u.avatar, p.header, u.dt_add, t.type,
     p.quote_author, p.text_content, p.photo_content, p.video_content, p.link_content, p.view_count
     FROM posts p 
     INNER JOIN users u ON p.user_id = u.id 
     INNER JOIN type_content t ON p.type_id = t.id
     WHERE p.id = $sql_post_id";
$result_post = mysqli_query($con, $sql_post);
$post = mysqli_fetch_assoc($result_post);

if ($post['id'] != $post_id) {
    die ('Пост не найден');
};


/**Количество постов у пользователя */
$sql_count_post = "SELECT COUNT(id) amount FROM posts WHERE user_id = $post[user_id]";
$result_count_post = mysqli_query($con, $sql_count_post);
$count_posts = mysqli_fetch_assoc($result_count_post);


/**Количество подписчиков у пользователя */
$sql_count_follow = "SELECT COUNT(follower_id) amount FROM subscriptions WHERE user_id = $post[user_id]";
$result_count_follow = mysqli_query($con, $sql_count_follow);
$count_followers = mysqli_fetch_assoc($result_count_follow);


/**Количество лайков у поста */
$sql_count_likes = "SELECT COUNT(user_id) amount FROM likes WHERE post_id = $post_id";
$result_count_likes = mysqli_query($con, $sql_count_likes);
$count_likes = mysqli_fetch_assoc($result_count_likes);


/**Количество комментариев к посту */
$sql_count_comment = "SELECT COUNT(content) amount FROM comments WHERE post_id = $post_id";
$result_count_comment = mysqli_query($con, $sql_count_comment);
$count_comments = mysqli_fetch_assoc($result_count_comment);

//Массив с комментариями
$sql_comments = "SELECT c.content, c.dt_add, u.avatar, u.username FROM comments c
    INNER JOIN users u ON u.id = c.user_id
    WHERE c.post_id = $post_id";
$result_comments = mysqli_query($con, $sql_comments);
$comments = mysqli_fetch_all($result_comments, MYSQLI_ASSOC);


//Массив с хештегами
$sql_hashtags = "SELECT h.name FROM posts p
INNER JOIN post_hashtag ph ON p.id = ph.post_id
INNER JOIN hashtags h ON ph.hashtag_id = h.id
WHERE p.id = $post_id";
$result_hashtags = mysqli_query($con, $sql_hashtags);
$hashtags = mysqli_fetch_all($result_hashtags, MYSQLI_ASSOC);



$post_info = include_template('post-' . $post['type'] . '.php', ['post' => $post]);
$page_content = include_template('post-details.php', 
    ['post' => $post, 'type' => $types, 'post_info' => $post_info,
    'count_posts' => $count_posts, 'count_followers' => $count_followers,
    'count_likes' => $count_likes, 'count_comments' => $count_comments, 'hashtags' => $hashtags, 'comments' => $comments]);
$layout_content = include_template('layout.php', ['page_content' => $page_content, 'user_name' => $user_name, 'is_auth' => $is_auth, 'page_title' => $post['header']]);
print($layout_content);
?>
