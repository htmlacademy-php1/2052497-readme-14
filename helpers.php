<?php
/**
 * Запрос типов контента 
 *
 * @param $con mysqli Ресурс соединения
 *
 * @return array $types Массив с типами контента
 */
function get_all_types($con) {
$sql_types = 'SELECT * FROM type_content';
$result = mysqli_query($con, $sql_types);
$types = mysqli_fetch_all($result, MYSQLI_ASSOC);
return $types;
};

/**
 * Запрос комментариев к посту
 *
 * @param $con mysqli Ресурс соединения
 * @param int $post_id id поста для запроса комментариев
 *
 * @return array $comments Массив с комментариями
 */
function get_comments($con, $post_id){
    $sql_comments = "SELECT c.content, c.dt_add, u.id AS user_id, u.avatar, u.username FROM comments c
    INNER JOIN users u ON u.id = c.user_id
    WHERE c.post_id = $post_id";
    $result_comments = mysqli_query($con, $sql_comments);
    $comments = mysqli_fetch_all($result_comments, MYSQLI_ASSOC);
    return $comments;
};

/**
 * Запрос Xeштегов  к посту
 *
 * @param $con mysqli Ресурс соединения
 * @param int $post_id id поста для запроса xeштегов
 *
 * @return  array $hashtags Массив с хештегами
 */
function get_hashtags($con, $post_id) {
    $sql_hashtags = "SELECT h.name FROM post_hashtag ph
    INNER JOIN hashtags h ON ph.hashtag_id = h.id
    WHERE ph.post_id = $post_id";
    $result_hashtags = mysqli_query($con, $sql_hashtags);
    $hashtags = mysqli_fetch_all($result_hashtags, MYSQLI_ASSOC);
    return $hashtags;
};

/**
 * Меняет формат даты: "H:i" если от даты прошло меньше 24 часов и "d M" если больше
 *
 * @param string $date дата 
 *
 * @return string $date Дата в нужном формате
 */
function convert_date($date) {
  
    $diff = time() - strtotime($date);
    if ($diff < 86400) {
        $date = date("H:i", strtotime($date));
        
    } else {
        $date = date("d M", strtotime($date));
    }

    return $date;
};

/**
 * Показывает сколько прошло времени от временной метки в удобном формате **минут назад 
 *
 * @param string $date дата 
 *
 * @return string $date разница в удобном формате
 */
function convert_date_toeasy_form($date) {
  
    $date = time() - strtotime($date);
    $min = $date / 60;
    $hour = $min / 60;
    $day = $hour / 24;
    $week = $day / 7;
    $month = $week / 5;
    $year = $month / 12;

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
    }else if ($week >= 5 && $month < 12) {
        $date = ceil($month);
        $date .= ' ' . get_noun_plural_form($date, 'месяц', 'месяца', 'месяцев');
    }else if ($month >= 12) {
        $date = ceil($year);
        $date .= ' ' . get_noun_plural_form($date, 'год', 'года', 'лет');
    }
    return $date;
};

/**
 * Ограничивае длину отображаемого текста до 300 символов в посте-плитке  
 *
 * @param string $post строка для обработки
 * @param int $lenght ограничение на длину строки, по умолчанию 300 символов  
 *
 * @return string $post строка из 300 символов и "..."
 */
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

/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } else {
                if (is_string($value)) {
                    $type = 's';
                } else {
                    if (is_double($value)) {
                        $type = 'd';
                    }
                }
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int)$number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Функция проверяет доступно ли видео по ссылке на youtube
 * @param string $url ссылка на видео
 *
 * @return string Ошибку если валидация не прошла
 */
function check_youtube_url($url)
{
    $id = extract_youtube_id($url);

    set_error_handler(function () {}, E_WARNING);
    $headers = get_headers('https://www.youtube.com/oembed?format=json&url=http://www.youtube.com/watch?v=' . $id);
    restore_error_handler();

    if (!is_array($headers)) {
        return "Видео по такой ссылке не найдено. Проверьте ссылку на видео";
    }

    $err_flag = strpos($headers[0], '200') ? 200 : 404;

    if ($err_flag !== 200) {
        return "Видео по такой ссылке не найдено. Проверьте ссылку на видео";
    }

    return true;
}

/**
 * Возвращает код iframe для вставки youtube видео на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_video($youtube_url)
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = "https://www.youtube.com/embed/" . $id;
        $res = '<iframe width="760" height="400" src="' . $src . '" frameborder="0"></iframe>';
    }

    return $res;
}

/**
 * Возвращает img-тег с обложкой видео для вставки на страницу
 * @param string|null $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_cover(string $youtube_url = null)
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = sprintf("https://img.youtube.com/vi/%s/mqdefault.jpg", $id);
        $res = '<img alt="youtube cover" width="320" height="120" src="' . $src . '" />';
    }

    return $res;
}

/**
 * Извлекает из ссылки на youtube видео его уникальный ID
 * @param string $youtube_url Ссылка на youtube видео
 * @return array
 */
function extract_youtube_id($youtube_url)
{
    $id = false;

    $parts = parse_url($youtube_url);

    if ($parts) {
        if ($parts['path'] === '/watch') {
            parse_str($parts['query'], $vars);
            $id = $vars['v'] ?? null;
        } else {
            if ($parts['host'] === 'youtu.be') {
                $id = substr($parts['path'], 1);
            }
        }
    }

    return $id;
}

/**
 * @param $index
 * @return false|string
 */
function generate_random_date($index)
{
    $deltas = [['minutes' => 59], ['hours' => 23], ['days' => 6], ['weeks' => 4], ['months' => 11]];
    $dcnt = count($deltas);

    if ($index < 0) {
        $index = 0;
    }

    if ($index >= $dcnt) {
        $index = $dcnt - 1;
    }

    $delta = $deltas[$index];
    $timeval = rand(1, current($delta));
    $timename = key($delta);

    $ts = strtotime("$timeval $timename ago");
    $dt = date('Y-m-d H:i:s', $ts);

    return $dt;
}
