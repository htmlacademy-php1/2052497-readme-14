<?php
    require_once 'init.php';
    require_once 'session.php';
    require_once 'helpers.php';

    $profile_id = $user['id'];
    $profile = [];
    $user_id = $user['id'];
    $has_errors = [];
if (filter_input(INPUT_GET, 'user')) {
    $profile_id = htmlspecialchars($_GET['user']);
};
    $your_profile = ($profile_id === $user_id);

    // Запрос информации профиля 
    $sql_profile = "SELECT u.id, u.username, u.dt_add, u.avatar, COUNT(DISTINCT s.follower_id) subscription, 
    COUNT(DISTINCT p.id) posts, COUNT(DISTINCT s1.follower_id) followers
    FROM users u 
    LEFT JOIN posts p ON p.user_id = u.id
    LEFT JOIN subscriptions s ON s.user_id = u.id AND s.follower_id = $user_id
    LEFT JOIN subscriptions s1 ON s1.user_id = u.id
    WHERE u.id = $profile_id";
    $res_profile = mysqli_query($con, $sql_profile);
    $profile = mysqli_fetch_assoc($res_profile);

    $get_type = 'posts';
    $page = 'profile-posts.php';
    $likes = [];
    $posts = [];
    $subscribers = [];
// Запрос списка лайков к постам профиля
if (filter_input(INPUT_GET, 'type') === 'likes') {
    $get_type = 'likes';
    $page = 'profile-likes.php';
    $sql_likes = "SELECT u.id user_id, u.username, u.avatar, p.id post_id, c.type, 
    p.text_content, p.photo_content, p.video_content, p.link_content, l.dt_add
    FROM `likes` l
    LEFT JOIN `users` u ON l.user_id = u.id
    LEFT JOIN `posts` p ON l.post_id = p.id            
    LEFT JOIN `type_content` c ON p.type_id = c.id
    WHERE p.user_id = $profile_id            
    ORDER BY l.dt_add DESC";
    $res_likes = mysqli_query($con, $sql_likes);
    $likes = mysqli_fetch_all($res_likes, MYSQLI_ASSOC);
}
// Запрос списка подписок профиля
elseif (filter_input(INPUT_GET, 'type') === 'sub') {
    $get_type = 'sub';
    $page = 'profile-subscriptions.php';
    $sql_subscriptions = "SELECT u.id, u.username, u.dt_add, u.avatar, COUNT(DISTINCT p.id) posts,
    COUNT(DISTINCT s2.follower_id) followers, COUNT(DISTINCT s3.user_id) subscription
    FROM `subscriptions` s1
    LEFT JOIN `users` u ON s1.user_id = u.id
    LEFT JOIN `posts` p ON s1.user_id = p.user_id
    LEFT JOIN `subscriptions` s2 ON s1.user_id = s2.user_id
    LEFT JOIN `subscriptions` s3 ON s3.user_id = u.id AND s3.follower_id = $user_id
    WHERE s1.follower_id = $profile_id
    GROUP BY u.id";
    $res_subscriptions = mysqli_query($con, $sql_subscriptions);
    $subscribers = mysqli_fetch_all($res_subscriptions, MYSQLI_ASSOC);
}
//Запрос списка постов профиля
else {
    $sql_posts = "SELECT p.id, p.user_id, u.username, u.avatar, p.header, u.dt_add, t.type,
    p.quote_author, p.text_content, p.photo_content, p.video_content, p.link_content, p.view_count, 
    COUNT(DISTINCT c.id) comments_count, COUNT(DISTINCT l.user_id) likes_count
    FROM posts p 
    INNER JOIN users u ON p.user_id = u.id 
    INNER JOIN type_content t ON p.type_id = t.id
    LEFT JOIN comments c ON c.post_id = p.id
    LEFT JOIN likes l ON l.post_id = p.id
    WHERE p.user_id = $profile_id
    GROUP BY p.id, c.post_id, l.post_id";
    $res_posts = mysqli_query($con, $sql_posts);
    $posts = mysqli_fetch_all($res_posts, MYSQLI_ASSOC);
};

    $profile_content = include_template($page, ['subscribers' => $subscribers, 'likes' => $likes, 'posts' => $posts, 'profile' => $profile, 'con' => $con, 'has_errors' => $has_errors]);
    $page_content = include_template('user-profile.php', ['profile_content' => $profile_content, 'profile' => $profile, 'your_profile' => $your_profile, 'get_type' => $get_type]);
    $layout_content = include_template('layout.php', ['page_content' => $page_content, 'user' => $user, 'page_title' => htmlspecialchars($profile['username'])]);
    print($layout_content);
