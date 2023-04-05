<?php
include_once("bootstrap.php");
session_start();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/c2626c7e45.js" crossorigin="anonymous"></script>
</head>
<style>
    @media (min-width: 640px) {
        .text-base {
            width: 20em;
        }
    }

    @media (max-width: 490px) {
        .text-base {
            width: 6em;
        }
    }

    @media (min-width: 900px) {
        .text-base {
            width: 30em;
        }
    }

    @media (min-width: 1024px) {
        .text-base {
            width: 40em;
        }
    }
</style>

<body class="bg-gray-400">
    <nav class="bg-gray-700">
        <div class="grid grid-cols-3 md:flex">
            <div class="pt-3 pb-2.5 ml-5 ">
                <a href="#"><img src="images/logo.svg" alt="logo" class="w-50 h-7"></a>
            </div>

            <div class="flex mt-[2px] md:flex-1 justify-center">
                <form class="flex h-9">
                    <div class="">
                        <input type="text" placeholder="Search.." class="text-base mt-2 p-1.5 rounded-l h-7 bg-white w-30" style="@media (min-width: 640px) {width:15em}">
                    </div>
                    <button type="submit" class="text-sm cursor-pointer rounded-r mt-2 px-2 bg-blue-600">
                        <i class="fa fa-search relative top-[0.75px]"></i>
                    </button>
                </form>
            </div>

            <div class="mt-1 flex flex-row-reverse">
                <div class="mt-2 mr-5 ml-2 relative bottom-[2px]">
                    <?php if (isset($_SESSION['loggedin'])) : ?>
                        <a href="logout.php" class="text-sm underline text-white">Logout</a>
                    <?php else : ?>
                        <a href="login.php" class="text-sm underline text-white">Login</a>
                    <?php endif; ?>
                </div>
                <img src="https://api.lorem.space/image/face?w=150&h=150" alt="profile picture" class="w-10 h-10 rounded-full mt-[1px]">
            </div>
        </div>
    </nav>
    <div class="bg-gradient-to-b from-gray-700 to-gray-400 flex flex-col justify-center items-center" style="height:500px;">
        <h1 class="text-3xl font-bold text-white text-center mb-10 lg:text-5xl">Lorem ipsum dolor, sit amet consectetur adipiscing elit!</h1>
        <div class="flex justify-center items-center">
            <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-7 mr-5 xl:mr-10 xl:mt-10 rounded text-lg xl:text-xl xl:py-3 xl:px-10">
                Button 1
            </a>
            <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-7 xl:mt-10 text-lg xl:text-xl xl:py-3 xl:px-10 rounded">
                Button 2
            </a>
        </div>
    </div>

    <!-- <main></main> Prompts etc -->
</body>


</html>