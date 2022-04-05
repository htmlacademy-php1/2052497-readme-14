CREATE DATABASE readme
DEFAULT CHARACTER SET utf8
DEFAULT COLLATE utf8_general_ci;

USE readme;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    datatime_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    email VARCHAR(128) UNIQUE NOT NULL,
    login VARCHAR(64) UNIQUE NOT NULL,
    password VARCHAR(64) UNIQUE NOT NULL,
    avatar TINYTEXT
);

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    datatime_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    header TINYTEXT NOT NULL,
    quote_author TINYTEXT,
    text_content TEXT,
    photo_content TINYTEXT,
    video_content TINYTEXT,
    link_content TINYTEXT,
    view_count INT,
    user_id INT, 
    type_id INT,
    hashtag_id INT
);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    datatime_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    content TEXT,
    user_id INT,
    post_id INT
);

CREATE TABLE likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    post_id INT
);

CREATE TABLE subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    follower_id INT,
    user_id INT
);

CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    datatime_add TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    content  TEXT,
    from_user_id INT,
    to_user_id INT
);

CREATE TABLE hashtags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name TINYTEXT
);

CREATE TABLE type_content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(64),
    name VARCHAR(64)
);