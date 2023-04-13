<?php
include_once("bootstrap.php");
try {
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        $user = new User();
        $user->setResetToken($token);
        $resetToken = $user->checkResetToken();
        $timestamp = $user->checkTimestamp();
        if ($resetToken && $timestamp) {
            if (!empty($_POST)) {
                try {
                    $password = $_POST['password'];
                    $user->setPassword($password);
                    $user->updatePassword();
                    $result = "Password has been reset";
                } catch (Throwable $e) {
                    $passwordError = $e->getMessage();
                }
            }
        } else {
            throw new Exception("Token is not valid");
        }
    } else {
        throw new Exception("Token not found");
    }
} catch (Throwable $e) {
    $error = $e->getMessage();
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>

    <title>Document</title>
</head>

<body class="bg-cover" style="background-image: url('images/signup-image.jpg')">

    <?php if (isset($result)) : ?>
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="bg-[#EAEAEA] w-1/2 md:w-1/3 xl:w-1/4 mx-auto my-auto rounded text-center pt-10 pb-10">
                <h1 class="text-center text-[26px] font-bold pb-5"><?php echo $result ?></h1>
                <a class="mt-4 text-blue-500 hover:text-blue-700" href="login.php">Back to login.</a>
            </div>
        </div>
    <?php elseif (isset($error)) : ?>
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="bg-[#EAEAEA] w-1/2 md:w-1/3 xl:w-1/4 mx-auto my-auto rounded text-center pt-10 pb-10">
                <h1 class="text-center text-[26px] font-bold pb-5"><?php echo $error ?></h1>
                <a class="mt-4 text-blue-500 hover:text-blue-700" href="login.php">Back to login.</a>
            </div>
        </div>
    <?php else : ?>
        <form action="" method="post">
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="bg-[#EAEAEA] w-1/2 md:w-1/3 xl:w-1/4 mx-auto my-auto rounded">

                    <h2 class="text-center pt-10 pb-7 text-2xl md:text-3xl font-bold mx-auto">Reset password</h2>
                    <div class="grid justify-items-center">
                        <div class="w-30">
                            <div class="mb-4">
                                <label class="block font-bold mb-0.5" for="newPassword">New Password</label>
                                <input class="w-full lg:w-55 px-3 py-2 border-2 rounded hover:border-[#143DF1] active:border-[#143DF1] <?php echo isset($passwordError) ? 'border-red-500' : ''; ?>" style="height: 35px; font-size:1rem;" type="password" name="password">
                                <!-- if there is an error, show it -->
                                <?php if (isset($passwordError)) : ?>
                                    <p class="text-red-500 text-xs italic" style="width: 200px;"><?php echo $passwordError; ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="flex flex-col items-center mb-10">
                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2" style="padding-left: 5rem; padding-right: 5rem;">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>
</body>

</html>