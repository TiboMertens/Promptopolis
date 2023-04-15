<?php
include_once("bootstrap.php");
//Get id from logged in user
session_start();
$id = $_SESSION['id']['id'];

$user = new User();
$user->setId($id);
$userDetails = $user->getUserDetails();
//get username form userdetails
$username = $userDetails['username'];
//get bio from userdetails
$bio = $userDetails['bio'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/c2626c7e45.js" crossorigin="anonymous"></script>
</head>

<body class="bg-gray-400">
    <nav class="bg-gray-700">
        <div class="grid grid-cols-3 md:flex">
            <div class="pt-3 pb-2.5 ml-5 ">
                <a href="index.php"><img src="images/logo.svg" alt="logo" class="w-50 h-7"></a>
            </div>

            <div class="flex mt-[2px] md:flex-1 justify-center">
                <form class="flex h-9">
                    <div class="">
                        <input type="text" placeholder="Search.." class="text-base mt-2 p-1.5 rounded-l h-7 bg-white w-30">
                    </div>
                    <button type="submit" class="text-sm cursor-pointer rounded-r mt-2 px-2 bg-blue-600">
                        <i class="fa fa-search relative top-[0.75px]"></i>
                    </button>
                </form>
            </div>

            <div class="mt-1 flex flex-row-reverse">
                <div class="mt-2 mr-5 ml-2 relative bottom-[2px]">
                    <!-- If the user is logged in, show the logout button, else show the login button -->
                    <?php if (isset($_SESSION['loggedin'])) : ?>
                        <a href="logout.php" class="fa-solid fa-arrow-right-from-bracket text-xl "></a>
                    <?php else : ?>
                        <a href="login.php" class="text-sm underline text-white">Login</a>
                    <?php endif; ?>
                </div>
                <a href="profile.php">
                    <img src="images/signup-image.jpg" alt="profile picture" class="w-10 h-10 rounded-full mt-[1px]">
                </a>
            </div>
        </div>
    </nav>

    <header class="bg-slate-200">
        <div class="flex flex-col items-center md:flex-row md:items-center xl:items-center">
            <img class="w-24 h-24 mt-10 md:w-36 md:h-36 md:m-20 xl:w-48 xl:h-48 xl:m-20 rounded-full" src="images/signup-image.jpg" alt="">
            <div class="mr-5 ml-5 mb-10  ">
                <div class="mt-10 flex justify-center items-center gap-5 md:mt-15 xl:mt-20 md:flex md:justify-start">
                    <h1 class="font-bold text-[36px] ml-2 mb-2 xl:text-[50px]"><?php echo htmlspecialchars($username); ?></h1>
                    <a class="w-20 h-10 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-700 flex justify-center items-center" href="editProfile.php">Edit</a>
                </div>

                <p class="w-50"><?php echo htmlspecialchars($bio); ?></p>
            </div>
        </div>
    </header>

</body>

</html>