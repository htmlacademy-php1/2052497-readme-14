--Добавляем тип постов
INSERT INTO type_content (name, type)
    VALUES ('Текст', 'text'),
           ('Цитата', 'quote'),
           ('Картинка', 'photo'),
           ('Видео', 'video'),
           ('Ссылка', 'link');

--Добавляем пользователей
INSERT INTO users (email, login, password, avatar)
    VALUES ('larisa@gmail.com','Лариса','Aa145236','userpic-larisa-small.jpg'),
           ('vlad666@ya.ru','Влад','145236Aa','userpic.jpg'),
           ('VICTOR@icloud.com', 'Виктор', 'qwerty', 'userpic.jpg');

--Добавляем посты
INSERT INTO posts (header, quote_author, text_content, photo_content, video_content, link_content, view_count, user_id, type_id)
    VALUES ('Цитата', 'Сергей Есенин', 'Мы в жизни любим только раз, а после ищем лишь похожих', NULL, NULL, NULL, '1', '1', '2'),
            ('Игра престолов', NULL, 'Не могу дождаться начала финального сезона своего любимого сериала!', NULL, NULL, NULL, '10', '2', '1'),
            ('Наконец, обработал фотки!', NULL, NULL, 'rock-medium.jpg', NULL, NULL, '15', '3', '3'),
            ('Моя мечта', NULL, NULL, 'coast-medium.jpg', NULL, NULL, '23', '1', '3'),
            ('Лучшие курсы', NULL, NULL, NULL, NULL, 'www.htmlacademy.ru', '6', '2', '5');

--Добавляем комментарии
INSERT INTO comments (content, user_id, post_id)
    VALUES ('Тоже там учусь', '3', '5'),
            ('Тоже жду с нетерпением', '1', '2'),
            ('Классное фото', '2', '3');

--получить список постов с сортировкой по популярности и вместе с именами авторов и типом контента
SELECT view_count, t.type, text_content, photo_content, video_content, link_content, u.login FROM `posts` p
    INNER JOIN `users` u ON p.user_id = u.id
    INNER JOIN `type_content` t ON p.type_id = t.id
    ORDER BY view_count ASC;

--получить список постов для конкретного пользователя
SELECT text_content, photo_content, video_content, link_content FROM posts WHERE user_id = 2;

--получить список комментариев для одного поста, в комментариях должен быть логин пользователя
SELECT content, u.login FROM comments c
	INNER JOIN `users` u ON c.user_id = u.id
    WHERE c.post_id = 3;

--добавить лайк к посту
INSERT INTO likes SET user_id = '1', post_id = '5';

--подписаться на пользователя
INSERT INTO subscriptions SET follower_id = '1', user_id = '3';