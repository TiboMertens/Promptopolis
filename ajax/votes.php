<?php
require_once('../vendor/autoload.php');
session_start();

if (!empty($_POST)) {
    $user_id = $_POST['user_id'];
    $loggedInUser_id = $_SESSION['id']['id'];

    $user = new \Promptopolis\Framework\User();
    $moderator = new \Promptopolis\Framework\Moderator();
    $moderator->updateVotes($user_id, $loggedInUser_id);

    $votes = $user->getVotes($user_id);
    $moderator->checkStatus($user_id);

    $result = [
        "status" => "success",
        "message" => "Vote was saved",
        "votes" => $votes
    ];

    echo json_encode($result);
}
