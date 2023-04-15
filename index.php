<?php
include_once("bootstrap.php");

//start the session
session_start();

if (isset($_SESSION['loggedin'])) {
    //check if user is an admin
    $user = new User();
    $isModerator = $user->isModerator($_SESSION['id']['id']);

    if ($isModerator) {
        // new Moderator();
        //get 15 prompts to approve
        $promptsToApprove = Prompt::get15ToApprovePrompts();
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/c2626c7e45.js" crossorigin="anonymous"></script>
</head>

<body class="bg-gray-400">
    <nav class="bg-gray-700">
        <div class="grid grid-cols-3 md:flex">
            <div class="pt-3 pb-2.5 ml-5 ">
                <a href="#"><img src="images/logo.svg" alt="logo" class="w-50 h-7"></a>
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
                        <a href="logout.php" class="text-sm underline text-white">Logout</a>
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
    <div class="bg-gradient-to-b from-gray-700 to-gray-400 flex flex-col justify-center items-center">
        <h1 class="text-3xl font-bold text-white text-center mb-10 lg:text-5xl">Lorem ipsum dolor, sit amet consectetur adipiscing elit!</h1>
        <div class="flex justify-center items-center">
            <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-7 mr-5 xl:mr-10 xl:mt-10 rounded text-lg xl:text-xl xl:py-3 xl:px-10">
                Buy a prompt
            </a>
            <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-7 xl:mt-10 text-lg xl:text-xl xl:py-3 xl:px-10 rounded">
                Sell a prompt
            </a>
        </div>
    </div>

    <main>
        <!-- check if user is logged in -->
        <?php if (isset($_SESSION['loggedin'])) : ?>
            <!-- check if user is an admin, if yes, show the first 15 prompts to approve -->
            <?php if ($isModerator) : ?>
                <section>
                    <h1 class="font-bold text-[24px] text-white ml-2 mb-2">Need approval <a href="showcase.php?filter=toApprove&page=1" class="text-[12px] text-blue-600 hover:text-blue-700 hover:text-[14px]">Expand<i class="fa-solid fa-arrow-right pl-1"></i></a></h1>
                    <div class="flex overflow-x-auto">
                        <div class=" flex flex-shrink-0">
                            <?php foreach ($promptsToApprove as $promptToApprove) : ?>
                                <a href="promptDetails.php?id=<?php echo $promptToApprove['id'] ?>&approve">
                                    <img src="<?php echo $promptToApprove['cover_url']; ?>" alt="prompt">
                                </a>
                            <?php endforeach; ?>
                            <div class="pt-20 mt-2 px-10">
                                <a href="showcase.php" class="text-blue-600 hover:text-blue-700 font-bold underline">View all</a>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endif; ?>
        <?php endif; ?>
        <!-- nieuwe prompts worden chronologisch getoond - gebruik AJAX infinite scroll(check infinite scroll van Tibo && check Joris zijn video's) - feature britt -->
        <section></section>
    </main>
</body>


</html>