<?php
require_once('../vendor/autoload.php');
session_start();

if (!empty($_POST)) {
    $user = new \Promptopolis\Framework\User();
    $moderator = new \Promptopolis\Framework\Moderator();
    $user_id = $_POST['user_id'];
    $loggedInUser_id = $_SESSION['id'];

    $moderator->setVoted_user_id($user_id);

    $canVote = $moderator->updateVotes($moderator->getVoted_user_id(), $loggedInUser_id);
    if ($moderator->getVoted_user_id() != $loggedInUser_id) {
        if ($canVote) {
            $status = "error";
            $message = "You have already voted for this user";
        } else {
            $status = "success";
            $message = "";
        }
        $votes = $user->getVotes($moderator->getVoted_user_id());
        $moderator->checkStatus($moderator->getVoted_user_id());
    } else {
        $status = "error";
        $message = "You cannot vote for yourself";
    }

    $result = [
        "status" => htmlspecialchars($status),
        "message" => htmlspecialchars($message),
        "votes" => htmlspecialchars($votes)
    ];

    echo json_encode($result);
}
