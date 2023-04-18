<?php
include_once("bootstrap.php");

if (isset($_SESSION['loggedin'])) {
    //check if user is an admin
    $user = new User();
    $isModerator = $user->isModerator($_SESSION['id']['id']);

    if ($isModerator) {
        // new Moderator();
        //get 15 prompts to approve
        $promptsToApprove = Prompt::get15ToApprovePrompts();
    }
    //::newPrompts
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

<body class="bg-[#121212]">
    <?php include_once("inc/nav.inc.php") ?>
    
    <div class="flex flex-col justify-center items-center h-[420px]">
        <h1 class="text-3xl font-bold text-white text-center mb-10 lg:text-5xl">Lorem ipsum dolor, sit amet consectetur adipiscing elit!</h1>
        <div class="flex justify-center items-center">
            <a href="showcase.php?filter=All" class="bg-[#BB86FC] hover:bg-[#A25AFB] text-white font-bold py-2 px-7 mr-5 xl:mr-10 xl:mt-10 rounded text-lg xl:text-xl xl:py-3 xl:px-10">
                Buy a prompt
            </a>
            <a href="#" class="bg-[#BB86FC] hover:bg-[#A25AFB] text-white font-bold py-2 px-7 xl:mt-10 text-lg xl:text-xl xl:py-3 xl:px-10 rounded">
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
                    <h1 class="font-bold text-[24px] text-white mb-2 ml-5">Need approval <a href="showcase.php?filter=toApprove&page=1" class="text-[12px] text-[#BB86FC] hover:text-[#A25AFB] hover:text-[14px]">Expand<i class="fa-solid fa-arrow-right pl-1"></i></a></h1>
                    <div class="flex overflow-x-auto bg-[#2A2A2A] m-5 pt-7 px-7 pb-4 rounded-lg">
                        <div class=" flex flex-shrink-0 gap-5">
                            <?php foreach ($promptsToApprove as $promptToApprove) : ?>
                                <a href="promptDetails.php?id=<?php echo $promptToApprove['id'] ?>&approve">
                                    <img  src="<?php echo $promptToApprove['cover_url']; ?>" alt="prompt" class="w-[270px] h-[150px] object-cover object-center rounded-lg">
                                    <h2 class="text-white font-bold text-[18px] mt-2"><?php echo $promptToApprove['title'] ?></h2>
                                </a>
                            <?php endforeach; ?>
                            <div class="pt-20 mt-2 px-10">
                                <a href="showcase.php" class="text-[#BB86FC] hover:bg-[#A25AFB] font-bold underline">View all</a>
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