<?php
require_once('../vendor/autoload.php');
session_start();

if (!empty($_POST)) {
    $id = $_POST['id'];
    $reportPrompt = new \Promptopolis\Framework\Report();
    $reportPrompt->flagPrompt($id);
    $result = [
        "status" => "success",
        "message" => "Prompt reported"
    ];

    echo json_encode($result);
}
