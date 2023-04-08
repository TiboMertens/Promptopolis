<?php
require_once 'vendor/autoload.php';
include_once(__DIR__ . "/classes/User.php");
include_once(__DIR__ . "/classes/Db.php");

//TODO:needs to be secured
apache_setenv('SENDGRID_API_KEY', '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');

if (!empty($_POST)) {
    try {
        // create a new user
        $user = new User();
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

        // save the user to the database
        $user->save();

        //send email
        $mail = $user->sendVerifyEmail();
        if ($mail) {
            //redirect to index.php with success message
            header("Location:index.php?success=" . urlencode("Activation Email Sent!"));
        }
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
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-cover" style="background-image: url('images/signup-image.jpg')">

    <form action="" method="post">
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="bg-[#EAEAEA] w-1/2 md:w-1/3 xl:w-1/4 mx-auto my-auto rounded">
                <h2 class="text-center py-10 text-3xl font-bold">Sign up</h2>
                <div class="grid justify-items-center">
                    <div class="w-30">
                        <div class="mb-4">
                            <label class="block font-bold mb-0.5" for="Email">Email</label>
                            <input class="w-30 lg:w-55 px-3 py-2 border-2 rounded hover:border-[#143DF1] active:border-[#143DF1] <?php echo isset($emailError) ? 'border-red-500' : ''; ?>" style="height: 35px; font-size:1rem;" type="text" name="email">
                            <!-- if there is an error, show it -->
                            <?php if (isset($emailError)) : ?>
                                <p class="text-red-500 text-xs italic"><?php echo $emailError; ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="mb-4">
                            <label class="block font-bold mb-0.5" for="Username">Username</label>
                            <input class="w-30 lg:w-50 px-3 py-2 border-2 rounded hover:border-[#143DF1] active:border-[#143DF1] <?php echo isset($usernameError) ? 'border-red-500' : ''; ?>" style="height: 35px; font-size:1rem;" type="text" name="username" style="height:17px">
                            <!-- if there is an error, show it -->
                            <?php if (isset($usernameError)) : ?>
                                <p class="text-red-500 text-xs italic"><?php echo $usernameError; ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="mb-4">
                            <label class="block font-bold mb-0.5" for="Password">Password <span style="font-size: 7.65px; font-style: italic;">*a minimum of 5 characters</span></label>
                            <input class="w-30 lg:w-50 px-3 py-2 border-2 rounded hover:border-[#143DF1] active:border-[#143DF1] <?php echo isset($passwordError) ? 'border-red-500' : ''; ?>" style="height: 35px; font-size:1rem;" type="password" name="password" style="height:17px">
                            <!-- if there is an error, show it -->
                            <?php if (isset($passwordError)) : ?>
                                <p class="text-red-500 text-xs italic" style="width: 200px;"><?php echo $passwordError; ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="flex flex-col items-center mb-8">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2" style="padding-left: 4.65rem; padding-right: 4.65rem;">Sign up</button>
                            <div>
                                <a href="login.php" class="italic underline text-xs ml-1">Already have an account?</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>

</html>