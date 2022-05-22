    CREATE DATABASE readme
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

    USE readme;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    email VARCHAR(128) UNIQUE NOT NULL,
    username VARCHAR(64) UNIQUE NOT NULL,
    password VARCHAR(64) NOT NULL,
    avatar TINYTEXT NULL
);

CREATE TABLE type_content (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    type VARCHAR(64) NOT NULL,
    name VARCHAR(64) NOT NULL
);

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    header TINYTEXT NOT NULL,
    quote_author TINYTEXT NULL,
    text_content TEXT NULL,
    photo_content TINYTEXT NULL,
    video_content TINYTEXT NULL,
    link_content TINYTEXT NULL,
    view_count int(11) DEFAULT '0',
    user_id INT NOT NULL, 
    FOREIGN KEY (user_id) REFERENCES users (id),
    type_id INT NOT NULL,
    FOREIGN KEY (type_id) REFERENCES type_content (id),
    repost int(64) DEFAULT NULL,
    creator int(11) DEFAULT NULL
);
CREATE FULLTEXT INDEX search ON posts(header, text_content);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    content TEXT NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (id),
    post_id INT NOT NULL,
    FOREIGN KEY (post_id) REFERENCES posts (id)
);

CREATE TABLE likes (
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (id),
    post_id INT NOT NULL,
    FOREIGN KEY (post_id) REFERENCES posts (id),
    PRIMARY KEY (user_id, post_id)
);

CREATE TABLE subscriptions (
    follower_id INT NOT NULL,
    FOREIGN KEY (follower_id) REFERENCES users (id),
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (id),
    PRIMARY KEY (follower_id, user_id)
);

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    dt_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    content  TEXT NOT NULL,
    from_user_id INT NOT NULL,
    FOREIGN KEY (from_user_id) REFERENCES users (id),
    to_user_id INT NOT NULL,
    FOREIGN KEY (to_user_id) REFERENCES users (id),
    new tinyint(1) NOT NULL DEFAULT '1'
);

CREATE TABLE hashtags (
    id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    name TINYTEXT NOT NULL
);

CREATE TABLE post_hashtag (
    post_id INT NOT NULL,
    FOREIGN KEY (post_id) REFERENCES posts (id),
    hashtag_id INT NOT NULL,
    FOREIGN KEY (hashtag_id) REFERENCES hashtags (id),
    PRIMARY KEY (post_id, hashtag_id)
);
