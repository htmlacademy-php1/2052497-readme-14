<?php
require_once 'helpers.php';
require_once 'init.php';
require_once 'session.php';

$post_id = htmlspecialchars(filter_input(INPUT_GET, 'id'));
$has_errors = [];

/**Информация о посте и его создателе */
$sql_post = "SELECT p.id, p.user_id, u.username, u.avatar, p.header, u.dt_add, t.type,
    p.quote_author, p.text_content, p.photo_content, p.video_content, p.link_content, p.view_count, 
    COUNT(DISTINCT c.id) AS comments_count, COUNT(DISTINCT l.user_id) AS likes_count,
    (SELECT COUNT(*) FROM posts p WHERE p.repost = $post_id) AS reposts_count,
    (SELECT COUNT(*) FROM posts p WHERE p.user_id = u.id) AS posts_count,
    (SELECT COUNT(*) FROM subscriptions s WHERE s.user_id = u.id) AS followers_count,
    (SELECT COUNT(*) FROM subscriptions s WHERE s.follower_id = $user_id AND s.user_id = p.user_id)  AS subscription  
    FROM posts p 
    INNER JOIN users u ON p.user_id = u.id 
    INNER JOIN type_content t ON p.type_id = t.id
    LEFT JOIN comments c ON c.post_id = p.id
    LEFT JOIN likes l ON l.post_id = p.id
    WHERE p.id = $post_id
    GROUP BY p.id, c.post_id, l.post_id";
$result_post = mysqli_query($con, $sql_post);
$post = mysqli_fetch_assoc($result_post);
// Счетчик просмотров
if (isset($post)) {
    mysqli_query($con, "UPDATE `posts` SET `view_count` = `view_count` + 1 WHERE `posts`.`id` = $post_id");
    $post['view_count'] += 1;
} else {
    die('Пост не найден');
};
// Массив с комментариями
$limit = 'LIMIT 3';
if (filter_input(INPUT_GET, 'all') === 'on'){
    $limit = '';
};
$sql_comments = "SELECT c.id, c.content, c.dt_add, u.id AS user_id, u.avatar, u.username FROM comments c
    INNER JOIN users u ON u.id = c.user_id
    WHERE c.post_id = $post_id
    ORDER BY c.id DESC
    $limit";
$result_comments = mysqli_query($con, $sql_comments);
$comments = mysqli_fetch_all($result_comments, MYSQLI_ASSOC);

//Массив с хештегами
$hashtags = get_hashtags($con, $post['id']);

// Валидация и добавление комментария
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_comm = htmlspecialchars(trim($_POST['new_comm']));
    $get_post_id = htmlspecialchars($_POST['post_id']);
    if (empty($new_comm)) {
        $has_errors['comm'] = "Поле не может быть пустым";
    } elseif (strlen($new_comm) < 4) {
        $has_errors['comm'] = "Комментарий не должен быть короче 4х символов";
    }
    if (empty($has_errors)) {
        $check_post = "SELECT id FROM posts WHERE id = $get_post_id";
        $res_check = mysqli_query($con, $check_post);
        $check = mysqli_fetch_assoc($res_check);
        if (isset($check)) {
            $sql_comm = 'INSERT INTO comments (user_id, post_id, content) VALUES (?, ?, ?)';
            $add_comm = mysqli_prepare($con, $sql_comm);
            mysqli_stmt_bind_param($add_comm, 'sss', $user_id, $post_id, $new_comm);
            $res_comm = mysqli_stmt_execute($add_comm);
            header("Location: /profile.php?user=" . $post['user_id']);
            exit;
        } else {
            $has_errors['comm'] = "Пост не найден!";
        };
    };
};

$post_info = include_template('post-' . $post['type'] . '.php', ['post' => $post]);
$page_content = include_template('post-details.php', ['post' => $post, 'post_info' => $post_info,
    'hashtags' => $hashtags, 'comments' => $comments, 'user' => $user, 'has_errors' => $has_errors]);
$layout_content = include_template('layout.php', ['page_content' => $page_content, 'user' => $user, 'page_title' => $post['header']]);
print($layout_content);
