<?php
require_once('../vendor/autoload.php');
session_start();

if (!empty($_POST)) {
    $prompt_id = $_POST['prompt_id'];
    $loggedInUser_id = $_SESSION['id'];

    $like = new \Promptopolis\Framework\Like();
    $canLike = $like->updateLikes($prompt_id, $loggedInUser_id);

    $likeState = $_POST['state'];

    $likes = $like->getLikes($prompt_id);

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
