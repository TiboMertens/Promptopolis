<?php
require_once 'vendor/autoload.php';
include_once("bootstrap.php");

// retrieve the token from the URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $user = new \Promptopolis\Framework\User();

    // retrieve the user from the database using the token
    $verify = $user->checkVerifyToken($token);

    // if the token is valid, activate the user's account
    if ($verify) {
        $user->activate($verify['id']);
        $result = "Account activated!";
    } else {
        $result = "Invalid token!";
    }
} else {
    $result = "Invalid token!";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Verify account</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#2A2A2A]">
    <div class="flex flex-col items-center justify-center h-screen">
        <h1 class="text-center text-[26px] font-bold text-white"><?php echo $result ?></h1>
        <a class="mt-4 text-[#BB86FC] hover:text-[#A25AFB]" href="index.php">Go to homepage</a>
    </div>

</body>

</html>