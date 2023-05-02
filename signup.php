<?php
require_once 'vendor/autoload.php';
include_once("bootstrap.php");

$config = parse_ini_file(__DIR__ . "\src\Promptopolis\Framework\config\config.ini");

$key = $config['SENDGRID_API_KEY'];

if (!empty($_POST)) {
    try {
        // create a new user
        $user = new \Promptopolis\Framework\User();
        try {
            $user->setUsername($_POST['username']);
        } catch (Throwable $e) {
            $usernameError = $e->getMessage();
        }

        try {
            $user->setEmail($_POST['email']);
        } catch (Throwable $e) {
            $emailError = $e->getMessage();
        }

        try {
            $user->setPassword($_POST['password']);
        } catch (Throwable $e) {
            $passwordError = $e->getMessage();
        }

        // generate a random token  (32 characters)
        $user->setVerifyToken(bin2hex(openssl_random_pseudo_bytes(32)));


        //$user->signup ---> daarin functie save & mail aanroepen.
        // save the user to the database
        $user->signup($key);
            
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <link rel="stylesheet" href="css/styles.css">
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
</head>
<!-- style="background-image: url(images/signup-image.jpg);" -->
<body class="bg-cover bg-[#121212]" >

    <form action="" method="post">
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="bg-[#2a2a2a] w-1/2 md:w-1/3 xl:w-1/4 mx-auto my-auto rounded">
                <h2 class="text-center text-white py-10 text-3xl font-bold">Sign up</h2>
                <div class="grid justify-items-center">
                    <div class="w-30">
                        <div class="mb-4">
                            <label class="block font-bold mb-0.5 text-white" for="Email">Email</label>
                            <input class="w-30 lg:w-55 px-3 py-2 border-[3px] rounded hover:border-[#BB86FC] active:border-[#BB86FC] <?php echo htmlspecialchars(isset($emailError)) ? 'border-red-500' : ''; ?>" style="height: 35px; font-size:1rem;" type="text" name="email">
                            <!-- if there is an error, show it -->
                            <?php if (isset($emailError)) : ?>
                                <p class="text-red-500 text-xs italic"><?php echo htmlspecialchars($emailError); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="mb-4">
                            <label class="block font-bold mb-0.5 text-white" for="Username">Username</label>
                            <input class="w-30 lg:w-50 px-3 py-2 border-[3px] rounded hover:border-[#BB86FC] active:border-[#BB86FC] <?php echo htmlspecialchars(isset($usernameError)) ? 'border-red-500' : ''; ?>" style="height: 35px; font-size:1rem;" type="text" name="username" style="height:17px">
                            <!-- if there is an error, show it -->
                            <?php if (isset($usernameError)) : ?>
                                <p class="text-red-500 text-xs italic"><?php echo htmlspecialchars($usernameError); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="mb-4">
                            <label class="block font-bold mb-0.5 text-white" for="Password">Password <span style="font-size: 7.65px; font-style: italic;">*a minimum of 5 characters</span></label>
                            <input class="w-30 lg:w-50 px-3 py-2 border-[3px] rounded hover:border-[#BB86FC] active:border-[#BB86FC] <?php echo htmlspecialchars(isset($passwordError)) ? 'border-red-500' : ''; ?>" style="height: 35px; font-size:1rem;" type="password" name="password" style="height:17px">
                            <!-- if there is an error, show it -->
                            <?php if (isset($passwordError)) : ?>
                                <p class="text-red-500 text-xs italic" style="width: 200px;"><?php echo htmlspecialchars($passwordError); ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="flex flex-col items-center mb-8">
                            <button class="bg-[#BB86FC] hover:bg-[#a25afb] text-white font-bold py-2 px-4 rounded mb-2" style="padding-left: 4.65rem; padding-right: 4.65rem;">Sign up</button>
                            <div>
                                <a href="login.php" class="italic underline text-xs ml-1 text-white hover:text-[#A25AFB]">Already have an account?</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>

</html>