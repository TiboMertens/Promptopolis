<?php
require_once 'vendor/autoload.php';
include_once("bootstrap.php");
if (isset($_SESSION["loggedin"])) {
    if (!empty($_POST)) {
        try {
            //get the id and password from the form
            $id = $_SESSION['id'];
            $password = $_POST['password'];
            $newPassword = $_POST['newPassword'];

            //create a new user object
            $user = new \Promptopolis\Framework\User();
            $user->setId($id);


            try {
                $user->canChangePassword($password);
            } catch (\Throwable $th) {
                $oldPasswordError = $th->getMessage();
            }

            try {
                $user->setPassword($newPassword);

                $result = $user->changePassword();
                header("Location: profile.php");
            } catch (\Throwable $th) {
                $newPasswordError = $th->getMessage();
            }
        } catch (Throwable $e) {
            $error = $e->getMessage();
        }


        if (isset($oldPasswordError)) {
            $border = "border-red-500";
        } else {
            $border = "border-white";
        }

        if (isset($newPasswordError)) {
            $newborder = "border-red-500";
        } else {
            $newborder = "border-white";
        }
    }
} else {
    header("Location: login.php");
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-cover bg-[#121212]" style="background-image: url('images/signup-image.jpg')">
    <form action="" method="post">
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="bg-[#2A2A2A] w-1/2 md:w-1/3 xl:w-1/4 mx-auto my-auto rounded">
                <h2 class="text-center pt-10 pb-7 text-3xl font-bold text-white">Change password</h2>
                <div class="grid justify-items-center">
                    <div class="w-30">
                        <div class="mb-4">
                            <label class="block font-bold mb-0.5 text-white" for="password">Current password</label>
                            <div class="flex flex-col items-end">
                                <input class=" w-full lg:w-50 px-3 py-2 border-[3px] <?php echo htmlspecialchars($border) ?> rounded hover:border-[#A25AFB] active:border-[#A25AFB]" style="height: 35px; font-size:1rem;" type="password" name="password">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block font-bold mb-0.5 text-white" for="password">New password</label>
                            <div class="flex flex-col items-end">
                                <input class="w-full lg:w-50 px-3 py-2 border-[3px] <?php echo isset($newPasswordError) ? 'border-red-500' : 'border-white'; ?> rounded hover:border-[#A25AFB] active:border-[#A25AFB]" style="height: 35px; font-size:1rem;" type="password" name="newPassword">
                            </div>
                        </div>
                        <!-- If there is an error, show it -->
                        <?php if (isset($oldPasswordError)) : ?>
                            <p class="text-red-500 text-xs italic"><?php echo htmlspecialchars($oldPasswordError); ?></p>
                        <?php endif; ?>
                        <?php if (isset($newPasswordError)) : ?>
                            <p class="text-red-500 text-xs italic"><?php echo htmlspecialchars($newPasswordError); ?></p>
                        <?php endif; ?>

                        <div class="flex flex-col items-center mb-10">
                            <button class="bg-[#BB86FC] hover:bg-[#A25AFB] text-white font-bold py-2 px-4 rounded mb-2" style="padding-left: 5rem; padding-right: 5rem;">Change password</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>

</html>