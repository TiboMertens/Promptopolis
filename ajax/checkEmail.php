<?php
require_once('../vendor/autoload.php');
if (!empty($_POST)) {
    $email = $_POST['email'];

    $user = new \Promptopolis\Framework\User();

    $mail = $user->checkemail($email);

    if ($mail) {
        $status = "error";
        $message = "email already exists";
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