<?php

require_once('../vendor/autoload.php');
session_start();

if (!empty($_POST['comment'])) {
    try {
        //getUsername function from Comment class
        $comment = new \Promptopolis\Framework\Comment();
        $id = $_POST['id'];
        $comment->setComment($_POST['comment']);
        $comment->setUsername($_SESSION['username']);
        $newCredits = $comment->save($id);
        echo json_encode(array('status' => 'success', 'message' => 'Comment saved', 'credits' => $newCredits));
    } catch (\Throwable $th) {
        //throw $th;
    }
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Comment cannot be empty'));
}
