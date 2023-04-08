<?php
include_once("bootstrap.php");

// Start output buffering
ob_start();

// Retrieve offset and limit values from POST request
$offset = $_POST['offset'];
$limit = $_POST['limit'];

// Retrieve array of prompts from database using the offset and limit values
$Prompts = Prompt::getAllToApprovePrompts($offset, $limit);

// Encode the array of prompts as a JSON object and send it back as the response to the AJAX request
echo json_encode($Prompts);

// Flush the output buffer
ob_end_flush();
