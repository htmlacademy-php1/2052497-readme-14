<?php
require_once 'init.php';
require_once 'session.php';
require_once 'helpers.php';

$sql_types = 'SELECT * FROM type_content';
$result = mysqli_query($con, $sql_types);
$types = mysqli_fetch_all($result, MYSQLI_ASSOC);


$sql_sort_type = "";
if ($type_on = filter_input(INPUT_GET, 'type')) {
    $sql_sort_type = "WHERE p.type_id = " . $type_on;
};

$sql_posts = "SELECT p.id, p.user_id, u.username, u.avatar, p.header, p.dt_add, t.type,
        p.quote_author, p.text_content, p.photo_content, p.video_content, p.link_content 
        FROM posts p 
        INNER JOIN users u ON p.user_id = u.id 
        INNER JOIN type_content t ON p.type_id = t.id
        $sql_sort_type
        ORDER BY view_count DESC";


$result = mysqli_query($con, $sql_posts);
$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

$page_content = include_template('main.php', ['posts' => $posts, 'types' => $types, 'type_on' => $type_on]);
$layout_content = include_template('layout.php', ['page_content' => $page_content, 'user' => $user, 'page_title' => 'readme: популярное']);
print($layout_content);
?>
