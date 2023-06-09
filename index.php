<?php
require_once 'vendor/autoload.php';
include_once("bootstrap.php");

use Promptopolis\Framework\Prompt;
use Promptopolis\Framework\Report;

if (isset($_SESSION['loggedin'])) {

    //check if user is an admin
    $user = new \Promptopolis\Framework\User();
    $userDetails = $user->getUserDetails($_SESSION['id']);
    $profilePicture = $userDetails['profile_picture_url'];
    $isModerator = $user->isModerator($_SESSION['id']);

    $followedPrompts = Prompt::getFollowedPrompts($_SESSION['id']);

    if ($isModerator == true) {
        // new Moderator();
        //get 15 prompts to approve
        $promptsToApprove = Prompt::get15ToApprovePrompts();
        $reportedPrompts = Prompt::getReportedPrompts();
        $reportedUsers = Report::allFlaggedUsers();
    }
}
//::newPrompts
$newPrompts = Prompt::getNewPrompts();
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
        <h1 class="text-3xl font-bold text-white text-center mb-10 lg:text-5xl">Promptopolis</h1>
        <div class="flex justify-center items-center">
            <a href="showcase.php?filter=All" class="bg-[#BB86FC] hover:bg-[#A25AFB] text-white font-bold py-2 px-7 mr-5 xl:mr-10 xl:mt-10 rounded text-lg xl:text-xl xl:py-3 xl:px-10">
                Buy a prompt
            </a>
            <a href="uploadPrompt.php" class="bg-[#BB86FC] hover:bg-[#A25AFB] text-white font-bold py-2 px-7 xl:mt-10 text-lg xl:text-xl xl:py-3 xl:px-10 rounded">
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
                    <h1 class="font-bold text-[24px] text-white mb-2 ml-5">Need approval <a href="showcase.php?filterApprove=not_approved&filterOrder=new&filterModel=all&page=1" class="text-[12px] text-[#BB86FC] hover:text-[#A25AFB] hover:text-[14px]">Expand<i class="fa-solid fa-arrow-right pl-1"></i></a></h1>
                    <div class="flex overflow-x-auto bg-[#2A2A2A] m-5 pt-7 px-7 pb-4 rounded-lg">
                        <div class=" flex flex-shrink-0 gap-5">
                            <?php if (count($promptsToApprove) <= 0) : ?>
                                <p class="text-[#BB86FC] text-[20px] font-bold relative bottom-1">No prompts to approve</p>
                            <?php endif ?>
                            <?php foreach ($promptsToApprove as $promptToApprove) : ?>
                                <a href="promptDetails.php?id=<?php echo htmlspecialchars($promptToApprove['id']) ?>&filterApprove=not_approved">
                                    <img src="<?php echo htmlspecialchars($promptToApprove['cover_url']); ?>" alt="prompt" class="w-[270px] h-[150px] object-cover object-center rounded-lg">
                                    <h2 class="text-white font-bold text-[18px] mt-2"><?php echo htmlspecialchars($promptToApprove['title']) ?></h2>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>
                <section>
                    <h1 class="font-bold text-[24px] text-white mb-2 ml-5">Reported prompts <a href="showcase.php?filterApprove=reported&filterOrder=new&filterModel=all&page=1" class="text-[12px] text-[#BB86FC] hover:text-[#A25AFB] hover:text-[14px]">Expand<i class="fa-solid fa-arrow-right pl-1"></i></a></h1>
                    <div class="flex overflow-x-auto bg-[#2A2A2A] m-5 pt-7 px-7 pb-4 rounded-lg">
                        <div class=" flex flex-shrink-0 gap-5">
                            <?php if (count($reportedPrompts) <= 0) : ?>
                                <p class="text-[#BB86FC] text-[20px] font-bold relative bottom-1">No reported prompts</p>
                            <?php endif ?>
                            <?php foreach ($reportedPrompts as $reportedPrompt) : ?>
                                <a href="promptDetails.php?id=<?php echo htmlspecialchars($reportedPrompt['id']) ?>&filterApprove=reported">
                                    <img src="<?php echo htmlspecialchars($reportedPrompt['cover_url']); ?>" alt="prompt" class="w-[270px] h-[150px] object-cover object-center rounded-lg">
                                    <h2 class="text-white font-bold text-[18px] mt-2"><?php echo htmlspecialchars($reportedPrompt['title']) ?></h2>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>
                <section>
                <h1 class="font-bold text-[24px] text-white mb-2 ml-5">Reported users</h1>
                    <div class="flex overflow-x-auto bg-[#2A2A2A] m-5 pt-7 px-7 pb-4 rounded-lg">
                        <div class=" flex flex-shrink-0 gap-5">
                            <?php if (count($reportedUsers) <= 0) : ?>
                                <p class="text-[#BB86FC] text-[20px] font-bold relative bottom-1">No reported users</p>
                            <?php endif ?>
                            <?php foreach ($reportedUsers as $reportedUser) : ?>
                                <a href="profile.php?id=<?php echo htmlspecialchars($reportedUser['id']) ?>">
                                <img src="<?php echo htmlspecialchars($reportedUser['profile_picture_url']); ?>" alt="prompt" class="w-[270px] h-[150px] object-cover object-center rounded-lg">
                                <h2 class="text-white font-bold text-[18px] mt-2"><?php echo htmlspecialchars($reportedUser['username']) ?></h2>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>
        <?php endif; ?>
        <!-- nieuwe prompts worden chronologisch getoond - feature britt -->
        <section>
            <h1 class="font-bold text-[24px] text-white mb-2 ml-5">New prompts <a href="showcase.php?filterOrder=new" class="text-[12px] text-[#BB86FC] hover:text-[#A25AFB] hover:text-[14px]">Expand<i class="fa-solid fa-arrow-right pl-1"></i></a></h1>
            <div class="flex overflow-x-auto bg-[#2A2A2A] m-5 pt-7 px-7 pb-4 rounded-lg">
                <div class=" flex flex-shrink-0 gap-5">
                    <?php if (count($newPrompts) <= 0) : ?>
                        <p class="text-[#BB86FC] text-[20px] font-bold relative bottom-1">No new prompts</p>
                    <?php endif ?>
                    <?php foreach ($newPrompts as $newPrompt) : ?>
                        <a href="promptDetails.php?id=<?php echo htmlspecialchars($newPrompt['id']) ?>&filterOrder=new">
                            <img src="<?php echo htmlspecialchars($newPrompt['cover_url']); ?>" alt="prompt" class="w-[270px] h-[150px] object-cover object-center rounded-lg">
                            <h2 class="text-white font-bold text-[18px] mt-2"><?php echo htmlspecialchars($newPrompt['title']) ?></h2>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php if (isset($_SESSION['loggedin'])) : ?>
            <section>
                <h1 class="font-bold text-[24px] text-white mb-2 ml-5">Followed creators <a href="showcase.php?filterOrder=new" class="text-[12px] text-[#BB86FC] hover:text-[#A25AFB] hover:text-[14px]">Expand<i class="fa-solid fa-arrow-right pl-1"></i></a></h1>
                <div class="flex overflow-x-auto bg-[#2A2A2A] m-5 pt-7 px-7 pb-4 rounded-lg">
                    <div class=" flex flex-shrink-0 gap-5">
                        <?php if (count($followedPrompts) <= 0) : ?>
                            <p class="text-[#BB86FC] text-[20px] font-bold relative bottom-1">No prompts from creators you follow</p>
                        <?php endif ?>
                        <?php foreach ($followedPrompts as $followedPrompt) : ?>
                            <a href="promptDetails.php?id=<?php echo htmlspecialchars($followedPrompt['id']) ?>">
                                <img src="<?php echo htmlspecialchars($followedPrompt['cover_url']); ?>" alt="prompt" class="w-[270px] h-[150px] object-cover object-center rounded-lg">
                                <h2 class="text-white font-bold text-[18px] mt-2"><?php echo htmlspecialchars($followedPrompt['title']) ?></h2>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </main>
</body>


</html>