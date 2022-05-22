<?php
require_once 'helpers.php';
require_once 'init.php';
require_once 'session.php';
require_once 'vendor/autoload.php';

// Запрос типов контента
$sql_types = 'SELECT * FROM type_content';
$result = mysqli_query($con, $sql_types);
$types = mysqli_fetch_all($result, MYSQLI_ASSOC);
// Определяем тип нового поста из парамета запроса
$get_type_id = filter_input(INPUT_GET, 'id');
if (!in_array($get_type_id, array_column($types, 'id')) && $get_type_id || !$get_type_id) {
    $type = current($types);
    $get_type = $type['type'];
    $get_type_id = $type['id'];
} else {
    foreach ($types as $type) {
        if ($type['id'] == $get_type_id) {
            $get_type = $type['type'];
        };
    };
};
htmlspecialchars(filter_input(INPUT_GET, 'header'));
$has_errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $user['id'];
    $header = htmlspecialchars(filter_input(INPUT_POST, 'header'));
    $text = htmlspecialchars(filter_input(INPUT_POST, 'text'));
    $link = htmlspecialchars(filter_input(INPUT_POST, 'link'));
    $video = htmlspecialchars(filter_input(INPUT_POST, 'video'));
    $photo_url = htmlspecialchars(filter_input(INPUT_POST, 'photo_url'));
    $str_hashtags = htmlspecialchars(filter_input(INPUT_POST, 'hashtags'));
    $quote_author = htmlspecialchars(filter_input(INPUT_POST, 'quote-author'));
    $tags_pettern = '/^#[A-zА-яёЁ0-9]{1,15}$/u';
    $has_errors = [];
    $rules_photo = [
        [
            'mime_type' => 'image/png',
            'type' => '.png'
        ],
        [
            'mime_type' => 'image/jpeg',
            'type' => '.jpeg'
        ],
        [
            'mime_type' => 'image/gif',
            'type' => '.gif'
        ]
    ];
    // Валидация заголовка    
    if (empty($header) || mb_strlen($header) > 50) {
        $has_errors['header'] = 'Заголовок не может быть пустым и длинее 50 символов';
    };
    // Валидация хештегов
    if ($str_hashtags) {
        $hashtags = explode(' ', $str_hashtags);
        foreach ($hashtags as $key => $tag) {
            if (!preg_match($tags_pettern, $tag) && $tag) {
                $has_errors['hashtag'] = 'Хештег должен быть от 1 до 15 символов(A-z А-я 0-9) и начинаться c "#"';
            };
        };
    };
    // Валидация автора цитаты
    if ($get_type === 'quote' && (empty($quote_author) || mb_strlen($quote_author) > 30)) {
        $has_errors['author'] = 'Имя не может быть пустым и длинее 30 символов';
    };
    // Валидация содержимого поста-текста
    if ($get_type === 'text' && (empty($text) || mb_strlen($text) > 1000)) {
        $has_errors['content'] = 'Текст не может быть пустым и длинее 1000 символов';
    };
    // Валидация содержимого поста-цитаты
    if ($get_type === 'quote' && (empty($text) || mb_strlen($text) > 300)) {
        $has_errors['content'] = 'Текст не может быть пустым и длинее 300 символов';
    };
    // Проверка полей на заполненость для ссылки или фото
    if (empty($photo_url) && empty($_FILES['photo']['name']) && $get_type === 'photo') {
        $has_errors['content'] = 'Выберите или добавьте ссылку на фото';
        // Валидация файла с пк пользавателя
    } elseif (!empty($_FILES['photo']['name'])) {
        $mime_type = $_FILES['photo']['type'];
        foreach ($rules_photo as $rule_photo) {
            if ($mime_type === $rule_photo['mime_type']) {
                $type_file = $rule_photo['type'];
            };
        };
        if (!$type_file) {
            $has_errors['file'] = 'Неверный формат изображения';
        };
        if ($type_file && $_FILES['photo']['size'] > 1 * 1024 * 1024) {
            $has_errors['file'] = 'Размер файла до 2MB';
        };
        // Загрузка файла на сервер
        if (!$has_errors && !empty($_FILES['photo']['name'])) {
            $file_name = uniqid() . $type_file;
            $file_path = __DIR__ . '/uploads/';
            $photo = '/uploads/' . $file_name;
            move_uploaded_file($_FILES['photo']['tmp_name'], $file_path . $file_name);
        };
        // Валидация ссылки на фото 
    } elseif (empty($_FILES['photo']['name']) && filter_var($photo_url, FILTER_VALIDATE_URL)) {
        // Записывае файл в буфер и узнаем тип файла         
        $buffer = file_get_contents($photo_url);
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->buffer($buffer);
        // Проверка файла на соответствие формату
        foreach ($rules_photo as $rule_photo) {
            if ($mime_type === $rule_photo['mime_type']) {
                $type_file = $rule_photo['type'];
            };
        };
        if (!$type_file) {
            $has_errors['file'] = 'Неверный формат изображения';
        } elseif ($type_file && !$has_errors) {
            // Сохранение файла на сервере 
            $file_name = uniqid() . $type_file;
            $file_path = __DIR__ . '/uploads/';
            $photo = '/uploads/' . $file_name;
            file_put_contents($file_path . $file_name, $buffer);
        };
    };
    // Валидация видео-ссылки
    if (!filter_var($video, FILTER_VALIDATE_URL) && $get_type === 'video') {
        $has_errors['content'] = 'Поле не можнет быть пустым и иметь формат ссылки';
    };
    // Проверка наличия видео по ссылке
    if (filter_var($video, FILTER_VALIDATE_URL) && !check_youtube_url($video)) {
        $has_errors['content'] = check_youtube_url($video);
    };
    // Валидация ссылки
    if (!filter_var($link, FILTER_VALIDATE_URL) && $get_type === 'link') {
        $has_errors['content'] = 'Поле не можнет быть пустым и иметь формат ссылки';
    };

    // Добавляем новый пост в БД
    if (!$has_errors) {
        $sql_add_post = 'INSERT INTO posts (header, quote_author, text_content, photo_content, video_content, link_content, user_id, type_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
        $add_post = mysqli_prepare($con, $sql_add_post);
        mysqli_stmt_bind_param($add_post, 'ssssssss', $header, $quote_author, $text, $photo, $video, $link, $user_id, $get_type_id);
        $res_add_post = mysqli_stmt_execute($add_post);
        $new_post_id = mysqli_insert_id($con);
        // Добавляем хештеги поста
        if ($new_post_id) {
            foreach ($hashtags as $key => $hashtag) {
                $sql_hashtag = "SELECT id FROM hashtags WHERE name = '$hashtag'";
                $result = mysqli_query($con, $sql_hashtag);
                $sql_hashtag_id = mysqli_fetch_assoc($result);
                if (!$sql_hashtag_id) {
                    $sql_new_hashtag = "INSERT INTO hashtags (name) VALUES (?)";
                    $hashtag_prepare = mysqli_prepare($con, $sql_new_hashtag);
                    mysqli_stmt_bind_param($hashtag_prepare, 's', $hashtag);
                    $res_new_hashtag = mysqli_stmt_execute($hashtag_prepare);
                    $hashtag_id = mysqli_insert_id($con);
                } else {
                    $hashtag_id = $sql_hashtag_id['id'];
                };
                $sql_post_hashtag = "INSERT INTO post_hashtag (post_id, hashtag_id) VALUES ($new_post_id, $hashtag_id)";
                $result_post_hashtag = mysqli_query($con, $sql_post_hashtag);
            };
        };
        if ($new_post_id) {
            $sql_followers = "SELECT u.username, u.email FROM users u
            LEFT JOIN subscriptions s ON s.follower_id = u.id
            WHERE s.user_id = $user_id";
            $res_followers = mysqli_query($con, $sql_followers);
            $followers = mysqli_fetch_all($res_followers, MYSQLI_ASSOC);
            foreach ($followers as $follower) {
                $message = new Swift_Message();
                $message->setTo($follower['email']);
                $message->setFrom("info@readme.ru");
                $message->setSubject("Новая публикация от пользователя " . $user['username']);
                $message->setBody("Здравствуйте, " . $follower['username'] . ". Пользователь " . $user['username'] . " только что опубликовал новую запись „" . $header . "“. Посмотрите её на странице пользователя: http://readme/profile.php?user=" . $user_id);
                $mailer = new Swift_Mailer($transport);
                $mailer->send($message);
            };
            header("Location: /post.php?id=$new_post_id");
            exit;
        } else {
            $has_errors['post'] = 'НЕ УДАЛОСЬ ЗАГРУЗИТЬ ДАННЫЕ';
        };
    };
};

$page_content = include_template('adding-post.php', ['types' => $types, 'get_type' => $get_type, 'get_type_id' => $get_type_id, 'has_errors' => $has_errors, '_POST' => $_POST]);

$layout_content = include_template('layout.php', ['page_content' => $page_content, 'user' => $user, 'page_title' => 'НОВЫЙ ПОСТ']);
print($layout_content);
