<?php
require_once 'vendor/autoload.php';
include_once("bootstrap.php");

$config = parse_ini_file('config/config.ini', true);

$key = $config[' keys ']['SENDGRID_API_KEY'];

if (!empty($_POST)) {
    $email = $_POST['email'];
    $user = new \Promptopolis\Framework\User();
    try {
        $correctEmail = $user->checkEmail($email);
        if ($correctEmail == true) {
            $user->setEmail($email);
            $user->setResetToken(bin2hex(openssl_random_pseudo_bytes(32)));
            $user->saveResetToken();
            $user->sendResetMail($key);
        }
    } catch (Throwable $e) {
        $emailError = $e->getMessage();
    }
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/c2626c7e45.js" crossorigin="anonymous"></script>
    <title>forgot password</title>
</head>

<body class="bg-cover" style="background-image: url('images/signup-image.jpg')">
    <form action="" method="post">

        <div class="absolute inset-0 flex items-center justify-center">
            <div class="bg-[#2A2A2A] w-1/2 md:w-1/3 xl:w-1/4 mx-auto my-auto rounded">
                <div class="pl-5 pt-5 text-white">
                    <a href="login.php"><i class="fa-solid fa-arrow-left fa-xl"></i></a>
                </div>
                <h2 class="text-center pt-10 pb-7 text-2xl md:text-3xl font-bold mx-auto text-white">Forgot password?</h2>
                <div class="grid justify-items-center">
                    <div class="w-30">
                        <div class="mb-4">
                            <label class="block font-bold mb-0.5 text-white" for="email">Email</label>
                            <input class="w-full lg:w-55 px-3 py-2 border-[3px] rounded hover:border-[#A25AFB] active:border-[#A25AFB] <?php echo isset($emailError) ? 'border-red-500' : ''; ?>" style="height: 35px; font-size:1rem;" type="text" name="email">
                            <?php if (isset($emailError)) : ?>
                                <p class="text-red-500 text-xs italic" style="width: 200px;"><?php echo htmlspecialchars($emailError); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="flex flex-col items-center mb-10">
                            <button class="bg-[#BB86FC] hover:bg-[#A25AFB] text-white font-bold py-2 px-4 rounded mb-2" style="padding-left: 4.25rem; padding-right: 4.25rem;">Send mail</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>

</html>