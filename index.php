<?php
require_once 'helpers.php';
$is_auth = rand(0, 1);

$post = [
    [
        'header' => 'Цитата',
        'type' => 'post-quote',
        'content' => 'Мы в жизни любим только раз, а после ищем лишь похожих',
        'username' => 'Лариса',
        'avatar' => 'userpic-larisa-small.jpg'
    ],
    [
        'header' => 'Игра престолов',
        'type' => 'post-text',
        'content' => 'Не могу дождаться начала финального сезона своего любимого сериала!',
        'username' => 'Владик',
        'avatar' => 'userpic.jpg'
    ],
    [
        'header' => 'Наконец, обработал фотки!',
        'type' => 'post-photo',
        'content' => 'rock-medium.jpg',
        'username' => 'Виктор',
        'avatar' => 'userpic-mark.jpg' 
    ],
    [
        'header' => 'Моя мечта',
        'type' => 'post-photo',
        'content' => 'coast-medium.jpg',
        'username' => 'Лариса',
        'avatar' => 'userpic-larisa-small.jpg' 
    ],
    [
        'header' => 'Лучшие курсы',
        'type' => 'post-link',
        'content' => 'www.htmlacademy.ru',
        'username' => 'Владик',
        'avatar' => 'userpic.jpg'
    ]
];


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
$user_name = 'Евгений';


$page_content = include_template('main.php', ['post' => $post]);
$layout_content = include_template('layout.php', ['page_content' => $page_content, 'user_name' => $user_name, 'is_auth' => $is_auth, 'page_title' => 'readme: популярное',]);
print($layout_content);

?>
