<?php
require_once('../vendor/autoload.php');
session_start();

if (!empty($_POST)) {
    $user_id = $_POST['user_id'];
    $loggedInUser_id = $_SESSION['id'];

    $user = new \Promptopolis\Framework\User();
    $moderator = new \Promptopolis\Framework\Moderator();
    $canVote = $moderator->updateVotes($user_id, $loggedInUser_id);
    if ($user_id != $loggedInUser_id) {
        if ($canVote) {
            $status = "error";
            $message = "You have already voted for this user";
        } else {
            $status = "success";
            $message = "";
        }
        $votes = $user->getVotes($user_id);
        $moderator->checkStatus($user_id);
    } else {
        $status = "error";
        $message = "You cannot vote for yourself";
    }

    $result = [
        "status" => $status,
        "message" => $message,
        "votes" => $votes
    ];

    echo json_encode($result);
}
