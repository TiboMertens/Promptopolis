<?php
include_once("bootstrap.php");

if (!empty($_POST)) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = new User();
    if (!empty($_POST)) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $user = new User();
        $result = $user->canLogin($username, $password);

        if ($result === true) {
            session_start();
            $_SESSION['loggedin'] = true;

            header("Location: index.php");
            return;
        } else {
            $error = $result;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-cover" style="background-image: url('images/signup-image.jpg')">
    <form action="" method="post">
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="bg-[#EAEAEA] w-1/2 md:w-1/3 xl:w-1/4 mx-auto my-auto rounded">
                <h2 class="text-center pt-10 pb-7 text-3xl font-bold">Login</h2>
                <div class="grid justify-items-center">
                    <div class="w-30">
                        <div class="mb-4">
                            <label class="block font-bold mb-0.5" for="username">Username</label>
                            <input class="w-full lg:w-55 px-3 py-2 border-2 rounded hover:border-[#143DF1] active:border-[#143DF1] <?php echo isset($error) ? 'border-red-500' : ''; ?>" style="height: 35px; font-size:1rem;" type="text" name="username">
                        </div>
                        <div class="mb-4">
                            <label class="block font-bold mb-0.5" for="password">Password</label>
                            <div class="flex flex-col items-end">
                                <input class="w-full lg:w-50 px-3 py-2 border-2 rounded hover:border-[#143DF1] active:border-[#143DF1] <?php echo isset($error) ? 'border-red-500' : ''; ?>" style="height: 35px; font-size:1rem;" type="password" name="password">
                                <a href="forgotPassword.php" class="block italic text-xs mt-1 underline">Forgot password?</a>
                            </div>
                        </div>
                        <!-- If there is an error, show it -->
                        <?php if (isset($error)) : ?>
                            <p class="text-red-500 text-xs italic"><?php echo $error; ?></p>
                        <?php endif; ?>
                        <div class="flex flex-col items-center mb-10">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-2" style="padding-left: 5rem; padding-right: 5rem;">Log in</button>
                            <div>
                                <a href="signup.php" class="italic underline text-xs ml-1">No account yet?</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>



</html>