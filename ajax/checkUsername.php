<?php
require_once('../vendor/autoload.php');
if (!empty($_POST)) {
    $username = $_POST['username'];

    $user = new \Promptopolis\Framework\User();

    $name = $user->checkUsername($username);

    if (!$name) {
        $status = "error";
        $message = "Username already exists";
    } else {
        $status = "success";
        $message = "";
    }

    $result = [
        "status" => $status,
        "message" => $message,
    ];

    echo json_encode($result);
}