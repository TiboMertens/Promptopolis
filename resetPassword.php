<?php 
include_once("bootstrap.php");

if(isset($_GET['token'])){
    $token = $_GET['token'];
    $user= new User();
    $user->setResetToken($token);
    $resetToken= $user->checkResetToken();
    $timestamp= $user->checkTimestamp();
    if($resetToken && $timestamp){
        if(!empty($_POST)){
            $password = $_POST['password'];
            $user->setPassword($password);
            $user->updatePassword();
            echo "Your password has been changed!";
        }
    } else {
        echo "Invalid reset link.";
    }
}else{
    $result = "No token found";
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>reset</h1>
    <form action="" method="post">
        <input type="password" name="password">
        <button>reset</button>
    </form>
</body>
</html>