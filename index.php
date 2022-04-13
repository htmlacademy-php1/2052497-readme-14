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

$sql_posts = 'SELECT  u.username, u.avatar, p.header, p.datatime_add, t.type,
        p.quote_author, p.text_content, p.photo_content, p.video_content, p.link_content 
        FROM posts p 
        INNER JOIN users u ON p.user_id = u.id 
        INNER JOIN type_content t ON p.type_id = t.id
        ORDER BY view_count DESC';
$result = mysqli_query($con, $sql_posts);
$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
 
function convert_date_toeasy_form($date) {
  
    $date = time() - strtotime($date);
    $min = $date / 60;
    $hour = $min / 60;
    $day = $hour / 24;
    $week = $day / 7;
    $month = $week / 5;

    if ($min < 60) {
        $date = ceil($min);
        $date .= ' ' . get_noun_plural_form($date, 'минуту', 'минуты', 'минут');
    }else if ($hour < 24 && $min >= 60) {
        $date = ceil($hour);
        $date .= ' ' . get_noun_plural_form($date, 'час', 'часа', 'часов');
    }else if ($day < 7 && $hour >= 24) {
        $date = ceil($day);
        $date .= ' ' . get_noun_plural_form($date, 'день', 'дня', 'дней');
    }else if ($day >= 7 && $week < 5) {
        $date = ceil($week);
        $date .= ' ' . get_noun_plural_form($date, 'неделю', 'недели', 'недель');
    }else if ($week >= 5) {
        $date = ceil($month);
        $date .= ' ' . get_noun_plural_form($date, 'месяц', 'месяца', 'месяцев');
    }
    return $date;
};

function limit_string_lenght($post, $lenght=300) {
    if (strlen($post) > $lenght) {
       $words = explode(' ', $post);
       $lenght_post = 0;
       for ($i=0; $i<count($words); $i++) {
       $lenght_post += strlen($words[$i]);
       if  ($lenght_post > $lenght) {
           break;
       };
    }
    $words = array_slice($words, 0, $i-1);
    $post = implode(' ', $words);
    $post = '<p>' . $post . "..." . '</p>' . '<a class="post-text__more-link" href="#">Читать далее</a>';
    } else {
        $post = '<p>' . $post . '</p>';
    };
      return $post;
};

$page_content = include_template('main.php', ['post' => $posts, 'type' => $types]);
$layout_content = include_template('layout.php', ['page_content' => $page_content, 'user_name' => $user_name, 'is_auth' => $is_auth, 'page_title' => 'readme: популярное',]);
print($layout_content);
?>
