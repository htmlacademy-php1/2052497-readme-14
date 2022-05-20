<?php
require_once 'init.php';
require_once 'session.php';
require_once 'helpers.php';

// Сортрировка по дате, лайкам или просмотрам
$get_order = 'view';
$order = 'p.view_count';
$get_order = htmlspecialchars(filter_input(INPUT_GET, 'order'));
if (in_array($get_order, ['likes', 'date'], true)) {
    if ($get_order === 'likes') {
        $order = 'p.id';
    } elseif ($get_order === 'date') {
        $order = 'p.dt_add';
    }
};

//Сортировка по типу контента
$types = get_all_types($con);
$sql_sort_type = "";
$get_type_id = filter_input(INPUT_GET, 'type');
if (in_array($get_type_id, array_column($types, 'id'))) {
    $sql_sort_type = "WHERE p.type_id = " . $get_type_id;
};

// Пагинация 
$offset = '';
$page = 1;
$sql_count_post = "SELECT COUNT(p.id) AS count_posts FROM posts p $sql_sort_type";
$res_count = mysqli_query($con, $sql_count_post);
$count_posts = mysqli_fetch_assoc($res_count);
$count_posts = $count_posts['count_posts'];
$count_page = ceil($count_posts / 9) - 1;
if (filter_input(INPUT_GET, 'page')) {
    $page = htmlspecialchars(filter_input(INPUT_GET, 'page'));
    $offset = 'OFFSET ' . ($page - 1) * 9;
};
// Запрос постов
$sql_posts = "SELECT p.id, p.user_id, u.username, u.avatar, p.header, p.dt_add, t.type,
        p.quote_author, p.text_content, p.photo_content, p.video_content, p.link_content, 
        COUNT(DISTINCT c.id) AS comments_count, COUNT(DISTINCT l.user_id) AS likes_count
        FROM posts p 
        INNER JOIN users u ON p.user_id = u.id 
        INNER JOIN type_content t ON p.type_id = t.id
        LEFT JOIN comments c ON c.post_id = p.id
        LEFT JOIN likes l ON l.post_id = p.id
        $sql_sort_type
        GROUP BY p.id, c.post_id, l.post_id        
        ORDER BY $order DESC
        LIMIT 9
        $offset";
$result = mysqli_query($con, $sql_posts);
$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

$page_content = include_template('main.php', ['posts' => $posts, 'types' => $types, 'get_type_id' => $get_type_id, 'page' => $page, 'count_page' => $count_page, 'get_order' => $get_order]);
$layout_content = include_template('layout.php', ['page_content' => $page_content, 'user' => $user, 'page_title' => 'readme: популярное']);
print($layout_content);
