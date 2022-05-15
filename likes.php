<?php
require_once 'helpers.php';
require_once 'init.php';
require_once 'session.php';

$post_id = htmlspecialchars(filter_input(INPUT_GET, 'post_id'));
$user_id = $user['id'];

if (isset($post_id)){
    //Проверка наличия поста
    $check_post = "SELECT id FROM posts WHERE id = $post_id";
    $res_check = mysqli_query($con, $check_post);
    $check = mysqli_fetch_assoc($res_check);
    if(isset($check)) {    
    //Добавление записи лайка
    $sql_like = 'INSERT INTO likes (user_id, post_id) VALUES (?, ?)';
    $add_like = mysqli_prepare($con, $sql_like);
    mysqli_stmt_bind_param($add_like, 'ss', $user_id, $post_id);
    $res_like = mysqli_stmt_execute($add_like);
    header("Location: " . $_SERVER['HTTP_REFERER']); 
    exit;   
    } else {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
} else{
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}
?>