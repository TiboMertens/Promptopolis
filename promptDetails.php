<?php
require_once 'vendor/autoload.php';
include_once("bootstrap.php");

$user = new \Promptopolis\Framework\User();

if (isset($_SESSION['loggedin'])) {
    $userDetails = $user->getUserDetails($_SESSION['id']);
    $profilePicture = $userDetails['profile_picture_url'];
}

try {
    //if id is set and not NULL, get prompt details
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $prompt_id = $_GET['id'];
        $prompt = new Promptopolis\Framework\Prompt();
        $prompt->setId($prompt_id);

        //get prompt details
        $promptDetails = $prompt->getPromptDetails();
        if ($promptDetails['id'] == null || $promptDetails == false || $promptDetails == null || $promptDetails == "" || $promptDetails == 0) {
            throw new Exception("Prompt not found");
        }

        //get data
        $title = $promptDetails['title'];
        $description = $promptDetails['description'];
        $cover_url = $promptDetails['cover_url'];
        $image2 = $promptDetails['image_url2'];
        $image3 = $promptDetails['image_url3'];
        $image4 = $promptDetails['image_url4'];
        $tstamp = $promptDetails['tstamp'];
        $price = $promptDetails['price'];
        $tag1 = $promptDetails['tag_names'][0];
        if (isset($promptDetails['tag_names'][1])) {
            $tag2 = $promptDetails['tag_names'][1];
        }
        if (isset($promptDetails['tag_names'][2])) {
            $tag3 = $promptDetails['tag_names'][2];
        }
        $model = $promptDetails['model'];
        $isApproved = $promptDetails['is_approved'];

        //get author id
        $authorID = $promptDetails['user_id'];
        if (isset($_SESSION["loggedin"])) {
            //check if user is a moderator
            $isModerator = $user->isModerator($_SESSION['id']);
            if ($promptDetails['is_approved'] == 0 && !$isModerator) {
                header("Location: index.php");
            }
        }

        if ($user->checkFavourite($_SESSION['id'], $prompt_id)) {
            $state = "remove";
        } else {
            $state = "add";
        }

        //get author name
        $userDetails = $user->getUserDetails($authorID);
        if ($userDetails == false) {
            $authorName = "deleted user";
            $authorID = "";
        } else {
            $authorName = $userDetails['username'];
        }
    } else {
        throw new exception('No correct id provided');
    }

    if ($isApproved == 0) {
        //if user is not a moderator, redirect to index
        if (!$isModerator) {
            header("Location: index.php");
        } else {
            $moderator = new Promptopolis\Framework\Moderator();
        }

        if (isset($_POST['approve'])) {
            $moderator->approve($prompt_id);
            //if prompt is appoved, check if user can be verified - if yes, verify user
            if ($user->checkToVerify()) {
                $user->verify();
            }
            //redirect to showcase
            header("Location: showcase.php");
        }

        if (isset($_POST['deny'])) {
            $motivation = $_POST['motivation'];

            $moderator->deny($prompt_id, $motivation);

            //redirect to showcase
            header("Location: showcase.php");
        }
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
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/c2626c7e45.js" crossorigin="anonymous"></script>
    <title><?php echo htmlspecialchars($title) ?> - Details</title>
</head>

<body class="bg-[#121212]">
    <?php include_once("inc/nav.inc.php"); ?>
    <!-- if error, show error -->
    <?php if (isset($error)) : ?>
        <div class="flex flex-col items-center justify-center h-screen">
            <h1 class="text-center text-[26px] font-bold text-white"><?php echo htmlspecialchars($error) ?></h1>
            <a class="mt-4 text-[#BB86FC] hover:text-[#A25AFB]" href="index.php">Go to homepage</a>
        </div>
    <?php else : ?>
        <main class="ml-auto mr-auto max-w-[500px] md:flex md:max-w-[700px] lg:max-w-[900px] xl:max-w-[1100px]">
            <div class="m-5 md:mt-[60px] lg:mt-5 pt-[70px]">
                <div class=""><img src="<?php echo htmlspecialchars($cover_url); ?>" alt="prompt cover" class="rounded-md max-h-[600px] xl:max-h-[500px] xl:w-[700px]"></div>
                <div class="text-[#cccccc] text-[14px] lg:text-[16px]">
                    <div class="flex gap-4">
                        <h1 class="text-[32px] lg:text-[36px] text-white font-bold mt-2 mb-3"><?php echo htmlspecialchars($title); ?></h1>
                        <i data-fav="<?php echo $state ?>" data-id=<?php echo $prompt_id ?> class="<?php echo $state == 'add' ? 'fa-regular' : 'fa-solid' ?> fa-bookmark fa-xl cursor-pointer relative top-[38px]" name="fav" style="color: #bb86fc;"></i>
                    </div>
                    <div class="relative">
                        <div class="flex justify-between mb-3">
                            <div class="flex-1">
                                <p>Uploaded on: &nbsp;<?php echo htmlspecialchars($tstamp); ?></p>
                            </div>
                            <div class="flex-1 justify-end mr-5 md:mr-0">
                                <p class="text-right">Made by: &nbsp; <a href="profile.php?id=<?php echo htmlspecialchars($authorID) ?>"><span class="underline font-bold text-[#BB86FC] hover:text-[#A25AFB]"><?php echo htmlspecialchars($authorName); ?></span></a></p>
                            </div>
                        </div>
                        <div class="flex justify-between mb-3">
                            <div class="flex-1">
                                <p>Model: &nbsp; <?php echo htmlspecialchars($model); ?></p>
                            </div>
                            <div class="flex flex-1 gap-4 justify-end mr-5 md:mr-0">
                                <p>Tags: </p>
                                <p><?php echo htmlspecialchars($tag1); ?></p>
                                <?php if (isset($tag2)) : ?>
                                    <p><?php echo htmlspecialchars($tag2); ?></p>
                                <?php endif ?>
                                <?php if (isset($tag3)) : ?>
                                    <p><?php echo htmlspecialchars($tag3); ?></p>
                                <?php endif ?>
                            </div>
                        </div>
                        <div class="mr-5 mb-5">
                            <h2 class="font-bold text-white text-[22px] mb-2">Description</h2>
                            <p><?php echo htmlspecialchars($description); ?></p>
                        </div>
                    </div>
                    <?php
                    if (!isset($_SESSION["loggedin"])) {
                        // Als de gebruiker niet is ingelogd, houd de overlay-klasse intact
                        echo '<a href="login.php"><div class="absolute inset-0 bg-black bg-opacity-25 backdrop-blur-md flex justify-center items-center"><p class="text-[#BB86FC] hover:text-[#A25AFB] font-bold text-[20px]">Login to see details</p></div></a>';
                    }
                    ?>
                    <?php if (isset($_SESSION["loggedin"])) : ?>
                        <div class="flex mb-3 items-center">
                            <?php if ($isApproved == 0) : ?>
                                <form action="" method="post">
                                    <button type=submit name="approve" class="bg-[#BB86FC] hover:bg-[#A25AFB] text-white font-bold py-2 px-4 w-[170px] rounded mb-2">Approve prompt</a>
                                        <button type=submit id="deny" class="bg-[#BB86FC] hover:bg-[#A25AFB] text-white font-bold ml-5 py-2 px-4 w-[170px] rounded mb-2">Deny prompt</a>
                                </form>
                            <?php else : ?>
                                <a href="#" class="bg-[#BB86FC] hover:bg-[#A25AFB] text-white font-bold py-2 px-4 rounded mb-2">Buy prompt</a>
                                <p class="text-white text-[16px] font-bold relative bottom-1 ml-3"><?php echo htmlspecialchars($price) . "credit(s)"; ?></p>
                            <?php endif ?>
                        </div>
                    <?php endif ?>
                </div>
            </div>
            <div class="flex justify-center md:mt-[60px] lg:mt-5 ml-6 mr-6 pt-[70px]">
                <div class="relative">
                    <!-- <h2 class="font-bold text-white text-[22px] mb-2">Example</h2> -->
                    <img src="<?php echo htmlspecialchars($image2); ?>" alt="prompt example" class=" rounded-md h-[300px] w-[500px] object-cover md:h-[200px] md:w-[250px]">
                    <img src="<?php echo htmlspecialchars($image3); ?>" alt="prompt example" class=" rounded-md h-[300px] w-[500px] object-cover mt-5 md:h-[200px] md:w-[250px]">
                    <img src="<?php echo htmlspecialchars($image4); ?>" alt="prompt example" class=" rounded-md h-[300px] w-[500px] object-cover mt-5 md:h-[200px] md:w-[250px]">

                    <?php
                    if (isset($_SESSION["loggedin"])) {
                        // Als de gebruiker is ingelogd, verwijder de overlay-klasse
                        echo '<div class="absolute inset-0"></div>';
                    } else {
                        // Als de gebruiker niet is ingelogd, houd de overlay-klasse intact
                        echo '<a href="login.php"><div class="absolute inset-0 bg-black bg-opacity-25 backdrop-blur-md flex justify-center items-center"><p class="text-[#BB86FC] hover:text-[#A25AFB] font-bold text-[20px]">Login to see details</p></div></a>';
                    }
                    ?>
                </div>
            </div>
        </main>
        <div class="hidden fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 justify-center items-center z-50">
            <div class="bg-[#2A2A2A] p-8 rounded shadow-md text-center">
                <form action="" method="post">
                    <h2 class="text-lg font-bold mb-4 text-white">Write your motivation to deny this prompt.</h2>
                    <input type="text" name="motivation" placeholder="Enter your motivation here" class="border border-gray-300 rounded px-4 py-2 mb-4 w-full">
                    <!-- add close button -->
                    <div class="flex gap-5">
                        <button class="close bg-[#BB86FC] hover:bg-[#A25AFB] text-white font-bold py-2 w-full rounded mb-2">Close</button>
                        <button name="deny" class="bg-[#BB86FC] hover:bg-[#A25AFB] text-white font-bold py-2 w-full rounded mb-2">Deny prompt</button>
                </form>
            </div>
        </div>
        </div>
    <?php endif ?>
    <script>
        const deny = document.getElementById("deny");
        const overlay = document.querySelector(".hidden");
        const close = document.querySelector(".close");

        deny.addEventListener("click", (e) => {
            e.preventDefault();
            overlay.classList.remove("hidden");
            overlay.classList.add('flex');
        });

        close.addEventListener("click", () => {
            overlay.classList.add("hidden");
            overlay.classList.add('flex');
        });
    </script>
    <script src="js/fav.js"></script>
</body>

</html>