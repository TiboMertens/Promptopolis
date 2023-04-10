<?php 
require_once 'vendor/autoload.php';
include_once(__DIR__ . "/classes/User.php");
include_once(__DIR__ . "/classes/Db.php");

$config = parse_ini_file('config/config.ini', true);

$key = $config[' keys ']['SENDGRID_API_KEY'];

apache_setenv('SENDGRID_API_KEY', $key);

if(!empty($_POST)){
    $email = $_POST['email'];
    $user = new User();
    $correctEmail= $user->checkEmail($email);
    if($correctEmail == true){
        $user->setEmail($email);
        $user->setResetToken(bin2hex(openssl_random_pseudo_bytes(32)));
        $user->saveResetToken();
        $user->sendResetMail();
    }else{
        echo "email not found";
    }
}




?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>forgot password</title>
</head>
<body>
    <h1>reset password</h1>
    <form action="" method="post">
        <input type="text" name="email">
        <button>send reset mail</button>
    </form>
</body>
</html>