<?php
require_once('../vendor/autoload.php');
session_start();

if (!empty($_POST)) {
    $user_id = $_POST['user_id'];
    $loggedInUser_id = $_SESSION['id']['id'];

    $user = new \Promptopolis\Framework\User();
    $moderator = new \Promptopolis\Framework\Moderator();
    $canVote = $moderator->updateVotes($user_id, $loggedInUser_id);

    if ($canVote == false) {
        $status = "error";
        $message = "You have already voted for this user";
    } else {
        $status = "success";
        $message = "Vote was saved";
    }

    $votes = $user->getVotes($user_id);
    $moderator->checkStatus($user_id);

    $result = [
        "status" => $status,
        "message" => $message,
        "votes" => $votes
    ];

    echo json_encode($result);
}
