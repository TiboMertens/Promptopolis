<?php
require_once('../vendor/autoload.php');
session_start();

if (!empty($_POST)) {
    $prompt_id = $_POST['prompt_id'];
    $loggedInUser_id = $_SESSION['id'];

    $like = new \Promptopolis\Framework\Like();
    $like->setPrompt_id($prompt_id);
    $canLike = $like->updateLikes($like->getPrompt_id(), $loggedInUser_id);

    $likeState = $_POST['state'];

    $likes = $like->getLikes($like->getPrompt_id());


    if ($canLike == true) {
        $message = "liked prompt";
        $state = "remove";
    } else {
        $message = "unliked prompt";
        $state = "add";
    }
 
    $result = [
        "status" => "success",
        "message" => $message,
        "likes" => $likes,
        "state" => $likeState,
    ];

    echo json_encode($result);
}
