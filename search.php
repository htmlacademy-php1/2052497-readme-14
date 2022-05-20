<?php
require_once 'init.php';
require_once 'session.php';
require_once 'helpers.php';

$search = trim(filter_input(INPUT_GET, 'search'));
$search = htmlspecialchars($search);
$tags_pettern = '/^#[A-zА-яёЁ0-9]{1,15}$/u';
$sql_where = '';
$sql_order = '';
$posts = [];

//Проверяем есть ли хештег и записываем условие для БД
if (isset($search) && preg_match($tags_pettern, $search)) {
    $sql_where = "WHERE h.name = '$search'";
    $sql_order = "ORDER BY p.dt_add ASC";
}
// Если поиск по тексту, записываем условие для БД
elseif (isset($search) && empty($sql_where)) {
    $sql_where = "WHERE MATCH(p.header, p.text_content) AGAINST('$search')";
};
// Запрос списка постов
if (isset($sql_where)) {
    $sql_posts = "SELECT p.id, p.user_id, u.username, u.avatar, p.header, u.dt_add, t.type,
    p.quote_author, p.text_content, p.photo_content, p.video_content, p.link_content, p.view_count, 
    COUNT(DISTINCT c.id) AS comments_count, COUNT(DISTINCT l.user_id) AS likes_count,
    (SELECT COUNT(*) FROM posts  WHERE repost = p.id) AS reposts_count
    FROM posts p 
    INNER JOIN users u ON p.user_id = u.id 
    INNER JOIN type_content t ON p.type_id = t.id
    LEFT JOIN comments c ON c.post_id = p.id
    LEFT JOIN likes l ON l.post_id = p.id
    LEFT JOIN post_hashtag ph ON ph.post_id = p.id 
    LEFT JOIN hashtags h ON ph.hashtag_id = h.id
    $sql_where
    GROUP BY p.id, c.post_id, l.post_id
    $sql_order";
    $result_posts = mysqli_query($con, $sql_posts);
    $posts = mysqli_fetch_all($result_posts, MYSQLI_ASSOC);
    foreach ($posts as $post) {
        $post['hashtags'] = get_hashtags($con, $post['id']);        
    };
};

if ($posts) {
    $page = 'search-result.php';
} else {
    $page = 'no-results.php';
};

$page_content = include_template($page, ['posts' => $posts, 'user' => $user, 'search' => $search]);
$layout_content = include_template('layout.php', ['page_content' => $page_content, 'user' => $user, 'page_title' => 'Поиск']);
print($layout_content);
