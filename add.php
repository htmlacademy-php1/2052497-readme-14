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
 // Запрос типов контента
$sql_types = 'SELECT * FROM type_content';
$result = mysqli_query($con, $sql_types);
$types = mysqli_fetch_all($result, MYSQLI_ASSOC);
 // Определяем тип нового поста из парамета запроса
$get_type = filter_input(INPUT_GET, 'id');
if (!in_array($get_type, array_column($types, 'id')) && $get_type || !$get_type) {
    $get_type = 1;
};
$user_id = 1;
$header = $_POST['header'] ?? null;
$text = $_POST['text'] ?? null;
$link = $_POST['link'] ?? null;
$video = $_POST['video'] ?? null;
$photo_url = $_POST['photo_url'] ?? null;
$str_hashtags = $_POST['hashtags'] ?? null;
$quote_author = $_POST['quote-author'] ?? null;
$tags_pettern = '/^#[A-zА-яёЁ0-9]{1,15}$/u';
$errors = [];                          
$flag = 0;
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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {   
 // Валидация заголовка    
    if (empty($header) || mb_strlen($header) > 50) {
        $errors['header'] = 'Заголовок не может быть пустым и длинее 50 символов';
        $flag = 1;
    };
 // Валидация хештегов
 if ($str_hashtags) {
    $hashtags = explode(' ', $str_hashtags);
     foreach ($hashtags as $key => $tag) {
        if (!preg_match($tags_pettern, $tag) && $tag) {
            $errors['hashtag'] = 'Хештег должен быть от 1 до 15 символов(A-z А-я 0-9) и начинаться c "#"';
            $flag = 1;
        };
    };
};
 // Валидация автора цитаты
    if (empty($quote_author) && $get_type == 2 || mb_strlen($quote_author) > 30 && $get_type == 2) {
        $errors['author'] = 'Имя не может быть пустым и длинее 30 символов';
        $flag = 1;
    };
 // Валидация содержимого поста-текста
    if (empty($text) && $get_type == 1 || mb_strlen($text) > 1000 && $get_type == 1) {
    $errors['content'] = 'Текст не может быть пустым и длинее 1000 символов';
    $flag = 1;
    };
 // Валидация содержимого поста-цитаты
    if (empty($text) && $get_type == 2 || mb_strlen($text) > 300 && $get_type == 2) {
        $errors['content'] = 'Текст не может быть пустым и длинее 300 символов';
        $flag = 1;
    };
 // Проверка полей на заполненость для ссылки или фото
    if (empty($photo_url) && empty($_FILES['photo']['name']) && $get_type == 3) {
        $errors['content'] = 'Выберите или добавьте ссылку на фото';
        $flag = 1;
 // Валидация файла с пк пользавателя
    } elseif (!empty($_FILES['photo']['name'])) {
        $mime_type = $_FILES['photo']['type'];
        foreach ($rules_photo as $rule_photo) {
            if ($mime_type === $rule_photo['mime_type']) {         
               $type_file = $rule_photo['type'];            
            };
        };
        if(!$type_file) {
           $errors['file'] = 'Неверный формат изображения';
           $flag = 1;
        };
        if($type_file && $_FILES['photo']['size'] > 1*1024*1024) {
           $errors['file'] = 'Размер файла до 2MB';
           $flag = 1;
        };
 // Загрузка файла на сервер
        if($flag == 0 && !empty($_FILES['photo']['name'])) {
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
            if(!$type_file) {
                $errors['file'] = 'Неверный формат изображения';
                $flag = 1;
            } elseif ($type_file && $flag == 0) {
// Сохранение файла на сервере 
                $file_name = uniqid() . $type_file;
                $file_path = __DIR__ . '/uploads/';
                $photo = '/uploads/' . $file_name;
                file_put_contents($file_path . $file_name, $buffer);
            };
        };
  // Валидация видео-ссылки
    if (!filter_var($video, FILTER_VALIDATE_URL) && $get_type == 4) {
    $errors['content'] = 'Поле не можнет быть пустым и иметь формат ссылки';
    $flag = 1;
 };
 // Проверка наличия видео по ссылке
 if (filter_var($video, FILTER_VALIDATE_URL) && check_youtube_url($video)) {
    $errors['content'] = check_youtube_url($link) ;
    $flag = 1;
 };
 // Валидация видео-ссылки
 if (!filter_var($link, FILTER_VALIDATE_URL) && $get_type == 5) {
    $errors['content'] = 'Поле не можнет быть пустым и иметь формат ссылки';
    $flag = 1;
 };
};

 // Добавляем новый пост в БД
if ($flag == 0) {
    $sql_add_post = 'INSERT INTO posts (header, quote_author, text_content, photo_content, video_content, link_content, user_id, type_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
    $add_post = mysqli_prepare($con, $sql_add_post);
    mysqli_stmt_bind_param($add_post, 'ssssssss', $header, $quote_author, $text, $photo, $video, $link, $user_id, $get_type);
    $res_add_post = mysqli_stmt_execute($add_post);
    $new_post_id = mysqli_insert_id($con);
    print($new_post_id);
 // Добавляем хештеги поста
    if ($new_post_id){
        foreach ($hashtags as $key => $hashtag) {
            $sql_hashtag = "SELECT id FROM hashtags WHERE name = '$hashtag'";
            $result = mysqli_query($con, $sql_hashtag);
            $sql_hashtag_id = mysqli_fetch_assoc($result);
             if (!$sql_hashtag_id) {
                $sql_new_hashtag = "INSERT INTO hashtags (name) VALUES (?)";
                $hashtag_prepare = mysqli_prepare($con, $sql_new_hashtag);
                mysqli_stmt_bind_param($hashtag_prepare, 's', $hashtag );
                $res_new_hashtag = mysqli_stmt_execute($hashtag_prepare);
                $hashtag_id = mysqli_insert_id($con);
                print($hashtag_id . ' ff');
             } else {
                 $hashtag_id = $sql_hashtag_id['id'];
             };
    $sql_post_hashtag = "INSERT INTO post_hashtag (post_id, hashtag_id) VALUES ($new_post_id, $hashtag_id)";
    $result_post_hashtag = mysqli_query($con, $sql_post_hashtag);
        };
    };
            if ($new_post_id) {
            header("Location: /post.php?id=$new_post_id");
            exit;
            } else {
            $errors['post'] = 'НЕ УДАЛОСЬ ЗАГРУЗИТЬ ДАННЫЕ';
};
};


$page_content = include_template('adding-post.php', ['types' => $types, 'get_type' => $get_type, 'errors' => $errors, '_POST' => $_POST]);

$layout_content = include_template('layout.php', ['page_content' => $page_content, 'user_name' => $user_name, 'is_auth' => $is_auth, 'page_title' => 'НОВЫЙ ПОСТ']);
print($layout_content);
?>
