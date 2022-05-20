<?php
session_start();
if (empty($_SESSION)) {
    header("Location: /index.php");
} else {
    $user = $_SESSION;
    $user_id = $user['id'];
    $sql_new_message = "SELECT COUNT(`new`) AS `new` FROM `messages` WHERE `to_user_id` = $user_id and `new`";
    $res_new_message = mysqli_fetch_assoc(mysqli_query($con, $sql_new_message));
    $user['new_message'] = $res_new_message['new'];
};
