<?php
include_once("bootstrap.php");
ob_start();

$offset = $_POST['offset'];
$limit = $_POST['limit'];
$images = Prompt::getAllToApproveImages($offset, $limit);
echo json_encode($images);

ob_end_flush();

