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
    <script src="https://cdn.tailwindcss.com"></script>
    <title>forgot password</title>
</head>
<body class="bg-cover" style="background-image: url('images/signup-image.jpg')">
<form action="" method="post">
    
    <div class="absolute inset-0 flex items-center justify-center">
        <div class="bg-[#EAEAEA] w-1/2 md:w-1/3 xl:w-1/4 mx-auto my-auto rounded">
            <h2 class="text-center pt-10 pb-7 text-2xl md:text-3xl font-bold mx-auto">Forgot password?</h2>
            <div class="grid justify-items-center">
                <div class="w-30">
                    <div class="mb-4">
                        <label class="block font-bold mb-0.5" for="email">Email</label>
                        <input class="w-full lg:w-55 px-3 py-2 border-2 rounded hover:border-[#143DF1] active:border-[#143DF1] " style="height: 35px; font-size:1rem;" type="text" name="email">
                    </div>
                    <div class="flex flex-col items-center mb-10">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2" style="padding-left: 4rem; padding-right: 4rem;">Send mail</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
</body>
</html>