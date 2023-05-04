<?php
require_once('../vendor/autoload.php');
session_start();

if (!empty($_POST)) {
    $id = $_POST['id'];
    $state = $_POST['state'];

    $user = new \Promptopolis\Framework\User();
    if ($state == "Follow") {
        $user->followUser($id);
        $message = "Unfollow";
    } else {
        $user->unfollowUser($id);
        $message = "Follow";
    }

    $result = [
        "status" => "success",
        "message" => $message
    ];

    echo json_encode($result);
}
