<?php
require_once('../vendor/autoload.php');
session_start();

if (!empty($_POST)) {
    $id = $_POST['id'];
    $state = $_POST['state'];

    $reportUser = new \Promptopolis\Framework\Report();

    if ($state == "reported") {
        $reportUser->unflagUser($id);
        $message = "User unreported";
        $state = "report";
    } else {
        $reportUser->flagUser($id);
        $message = "User reported";
        $state = "reported";
    }

    $result = [
        "status" => "success",
        "message" => $message,
        "state" => $state
    ];

    echo json_encode($result);
}
