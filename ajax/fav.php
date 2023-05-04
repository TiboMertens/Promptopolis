<?php 
require_once('../vendor/autoload.php');
session_start();
if (!empty($_POST)) {
    $state = $_POST['state'];
    $prompt_id = $_POST['id'];

    $user = new \Promptopolis\Framework\User();

    if ($state == "add") {
        $user->addFav($prompt_id);
        $message = "added to favourites";
        $state = "remove";
    } else {
        $user->removeFav($prompt_id);
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