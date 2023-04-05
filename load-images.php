<?php
include_once("bootstrap.php");
ob_start();

$prompt = new Prompt();
$offset = $_POST['offset'];
$limit = $_POST['limit'];
$images = $prompt->getToApproveImages($offset, $limit);
echo json_encode($images);

ob_end_flush();

