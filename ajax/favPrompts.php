<?php 
require_once('../vendor/autoload.php');
session_start();
if (!empty($_POST)) {
    $user = new \Promptopolis\Framework\User();

    $state = $_POST['state'];
    $prompt_id = $_POST['id'];

    $user->setPrompt_id($prompt_id);

    if ($state == "add") {
        $user->addFav($user->getPrompt_id());
        $message = "added to favourites";
        $state = "remove";
    } else {
        $user->removeFav($user->getPrompt_id());
        $message = "removed from favourites";
        $state = "add";
    }

    $result = [
        "status" => "succes",
        "message" => $message,
        "state" => $state,
    ];

    echo json_encode($result);
}