<?php
require_once 'vendor/autoload.php';
include_once("bootstrap.php");

$user = new \Promptopolis\Framework\User();
$prompt = new Promptopolis\Framework\Prompt();
$like = new Promptopolis\Framework\Like();
$comment = new Promptopolis\Framework\Comment();
$report = new Promptopolis\Framework\Report();

try {
    //if id is set and not NULL, get prompt details
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $prompt_id = $_GET['id'];
        $prompt->setId($prompt_id);
        $likes = $like->getLikes($prompt_id);
        if (isset($_SESSION['loggedin'])) {
            $userDetails = $user->getUserDetails($_SESSION['id']);
            $profilePicture = $userDetails['profile_picture_url'];
            $username = $userDetails['username'];

            $prompt_id = $_GET['id'];
            $prompt->setId($prompt_id);

            if ($like->checkLiked($prompt_id, $_SESSION['id'])) {
                $likeState = "remove";
            } else {
                $likeState = "add";
            }

            if ($user->checkFavourite($_SESSION['id'], $prompt_id)) {
                $state = "remove";
            } else {
                $state = "add";
            }

            if (\Promptopolis\Framework\Purchase::checkBought($prompt_id, $_SESSION['id'])) {
                $boughtState = "bought";
            } else {
                $boughtState = "buy";
            }
        }

        $allComments = $comment->getComments($prompt_id);

        $likes = $like->getLikes($prompt_id);

        //get prompt details
        $promptDetails = $prompt->getPromptDetails();
        if ($promptDetails['id'] == null || $promptDetails == false || $promptDetails == null || $promptDetails == "" || $promptDetails == 0) {
            throw new Exception("Prompt not found");
        }

        //get data
        $title = $promptDetails['title'];
        $motivation = $promptDetails['message'];
        $denied = $promptDetails['is_denied'];
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

        //get author id
        $authorID = $promptDetails['user_id'];
        if (isset($_SESSION["loggedin"])) {
            //check if user is a moderator
            $isModerator = $user->isModerator($_SESSION['id']);
            if ($promptDetails['is_approved'] == 0 && !$isModerator) {
                header("Location: index.php");
            }
        }

        if ($like->checkLiked($prompt_id, $_SESSION['id'])) {
            $likeState = "remove";
        } else {
            $likeState = "add";
        }

        if ($denied == 1 && $authorID != $_SESSION['id']) {
            header("Location: index.php");
        }

        //if prompt is not approved, only moderators and the author can see it
        if ($promptDetails['is_approved'] != 1 && $authorID != $_SESSION['id']) {
            //if user is not a moderator, redirect to index
            if (!$isModerator) {
                header("Location: index.php");
            } else {
                $moderator = new Promptopolis\Framework\Moderator();
            }
        }

        //get author name
        $userDetails = $user->getUserDetails($authorID);
        if ($userDetails == false) {
            $authorName = "deleted user";
            $authorID = "";
        } else {
            $authorName = $userDetails['username'];
        }

        try {
            if (isset($_POST['buy'])) {
                $purchase = new Promptopolis\Framework\Purchase();
                $purchase->purchase($prompt_id, $_SESSION['id']);
            }
        } catch (\Throwable $th) {
            $purchaseError = $th->getMessage();
        }

        //check if the user has bought this prompt
        $hasBought = $user->hasBought($prompt_id, $_SESSION['id']);
    } else {
        throw new exception('No correct id provided');
    }

    if ($promptDetails["is_approved"] == 0 || $promptDetails["is_reported"] == 1) {
        //if user is not a moderator, redirect to index
        if (!$isModerator) {
            header("Location: index.php");
        } else {
            $moderator = new Promptopolis\Framework\Moderator();
        }

        if (isset($_POST['approve'])) {
            $moderator->approve($prompt_id);
            //if prompt is appoved, check if user can be verified - if yes, verify user
            if ($user->checkToVerify($id)) {
                $user->verify($id);
            }
            //redirect to showcase
            header("Location: index.php");
        }

        if (isset($_POST['deny'])) {
            $motivation = $_POST['motivation'];

            $moderator->deny($prompt_id, $motivation);

            //redirect to showcase
            header("Location: index.php");
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
                <?php if ($denied == 1) : ?>
                    <div class="flex gap-2 mt-5">
                        <h1 class="font-bold text-white text-[22px] mb-2">Why your prompt was denied: </h1>
                        <p class="text-white relative top-[5px]"><?php echo htmlspecialchars($motivation) ?></p>
                    </div>
                <?php else : ?>
                    <div class="text-[#cccccc] text-[14px] lg:text-[16px]">
                        <div class="flex gap-4">
                            <h1 class="text-[32px] lg:text-[36px] text-white font-bold mt-2 mb-3"><?php echo htmlspecialchars($title); ?></h1>
                            <i data-fav="<?php echo $state ?>" data-id=<?php echo $prompt_id ?> class="<?php echo $state == 'add' ? 'fa-regular' : 'fa-solid' ?> fa-bookmark fa-xl cursor-pointer relative top-[38px]" name="fav" style="color: #bb86fc;"></i>
                            <div class="flex mb-[4px] ml-4 bg-[#121212]">
                                <i id="heart" data-liked="<?php echo $likeState ?>" data-id=<?php echo $prompt_id ?> class="<?php echo $likeState == 'add' ? 'fa-regular' : 'fa-solid' ?> fa-heart fa-xl cursor-pointer relative top-[36px]" name="like" style="color: #bb86fc;"></i>
                                <p class="liking text-[#BB86FC] font-bold relative top-[25px] left-[5px]"><?php echo htmlspecialchars($likes) ?></p>
                            </div>
                            <i id="flag" class="<?php echo $promptDetails['is_reported'] == 1 ? 'fa-solid' : 'fa-regular' ?> fa-flag fa-xl cursor-pointer relative top-[37px] ml-3 " name="flag" style="color: #bb86fc;"></i>
                        </div>
                        <div class="relative">
                            <div class="flex justify-between mb-3 <?php echo $hasBought || $promptDetails['is_approved'] == 0 || $promptDetails['is_reported'] ? '' : 'filter blur' ?>">
                                <div class="flex-1">
                                    <p>Uploaded on: &nbsp;<?php echo htmlspecialchars($tstamp); ?></p>
                                </div>
                                <div class="flex-1 justify-end mr-5 md:mr-0">
                                    <p class="text-right">Made by: &nbsp; <a href="profile.php?id=<?php echo htmlspecialchars($authorID) ?>"><span class="underline font-bold text-[#BB86FC] hover:text-[#A25AFB]"><?php echo htmlspecialchars($authorName); ?></span></a></p>
                                </div>
                            </div>
                            <div class="flex justify-between mb-3 <?php echo $hasBought || $promptDetails['is_approved'] == 0 || $promptDetails['is_reported'] ? '' : 'filter blur' ?>">
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
                            <div class="mr-5 mb-5 <?php echo $hasBought || $promptDetails['is_approved'] == 0 || $promptDetails['is_reported'] ? '' : 'filter blur' ?>">
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
                                <?php if ($promptDetails["is_approved"] == 0 || $promptDetails["is_reported"]==1) : ?>
                                    <form action="" method="post">
                                        <button type=submit name="approve" class="bg-[#BB86FC] hover:bg-[#A25AFB] text-white font-bold py-2 px-4 w-[170px] rounded mb-2">Approve prompt</a>
                                            <button type=submit id="deny" class="bg-[#BB86FC] hover:bg-[#A25AFB] text-white font-bold ml-5 py-2 px-4 w-[170px] rounded mb-2">Deny prompt</a>
                                    </form>
                                <?php elseif ($boughtState == 'buy') : ?>
                                    <form action="" method="post">
                                        <button name="buy" class="bg-[#BB86FC] hover:bg-[#A25AFB] text-white font-bold py-2 px-4 rounded mb-2">Buy prompt</button>
                                    </form>
                                    <p class="text-white text-[16px] font-bold relative bottom-1 ml-3"><?php echo htmlspecialchars($price) . "credit(s)"; ?></p>
                                <?php endif ?>
                            </div>
                            <?php if (isset($purchaseError)) : ?>
                                <p class="text-red-500 text-xs italic relative"><?php echo $purchaseError ?></p>
                            <?php endif ?>
                        <?php endif ?>
                    </div>
                <?php endif ?>
            </div>
            <div class="flex justify-center md:mt-[60px] lg:mt-5 ml-6 mr-6 pt-[70px]">
                <div class="relative">
                    <!-- <h2 class="font-bold text-white text-[22px] mb-2">Example</h2> -->
                    <img src="<?php echo htmlspecialchars($image2); ?>" alt="prompt example" class=" rounded-md h-[300px] w-[500px] object-cover md:h-[200px] md:w-[250px] <?php echo $hasBought || $promptDetails['is_approved'] == 0 || $promptDetails['is_reported'] ? '' : 'filter blur' ?>">
                    <img src="<?php echo htmlspecialchars($image3); ?>" alt="prompt example" class=" rounded-md h-[300px] w-[500px] object-cover mt-5 md:h-[200px] md:w-[250px] <?php echo $hasBought || $promptDetails['is_approved'] == 0 || $promptDetails['is_reported'] ? '' : 'filter blur' ?>">
                    <img src="<?php echo htmlspecialchars($image4); ?>" alt="prompt example" class=" rounded-md h-[300px] w-[500px] object-cover mt-5 md:h-[200px] md:w-[250px] <?php echo $hasBought || $promptDetails['is_approved'] == 0 || $promptDetails['is_reported'] ? '' : 'filter blur' ?>">

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
        <div id="denyPopup" class="hidden fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 justify-center items-center z-50">
            <div class="bg-[#2A2A2A] p-8 rounded shadow-md text-center">
                <form action="" method="post">
                    <h2 class="text-lg font-bold mb-4 text-white">Write your motivation to deny this prompt.<span class="text-[12px]">(optional)</span></h2>
                    <input type="text" name="motivation" placeholder="Enter your motivation here" class="border border-gray-300 rounded px-4 py-2 mb-4 w-full">
                    <!-- add close button -->
                    <div class="flex gap-5">
                        <button class="close bg-[#BB86FC] hover:bg-[#A25AFB] text-white font-bold py-2 w-full rounded mb-2">Close</button>
                        <button name="deny" class="bg-[#BB86FC] hover:bg-[#A25AFB] text-white font-bold py-2 w-full rounded mb-2">Deny prompt</button>
                    </div>
                </form>
            </div>
        </div>
        <div id="reportPopup" class="hidden fixed top-0 left-0 w-full h-full bg-black bg-opacity-50 justify-center items-center z-50">
            <div class="bg-[#2A2A2A] p-8 rounded shadow-md text-center">
                <form action="" method="post">
                    <h2 class="text-lg font-bold mb-4 text-white">Are you sure you want to report this prompt?</h2>
                    <!-- add close button -->
                    <div class="flex gap-5">
                        <button class="close bg-[#BB86FC] hover:bg-[#A25AFB] text-white font-bold py-2 w-full rounded mb-2">Cancel</button>
                        <button data-id=<?php echo $prompt_id ?> name="report" class="bg-[#BB86FC] hover:bg-[#A25AFB] text-white font-bold py-2 w-full rounded mb-2">Report prompt</button>
                    </div>
                </form>
            </div>
        </div>
        </div>
    <?php endif ?>

    <?php if (isset($_SESSION["loggedin"])): ?>
    <form data-id="<?php echo $prompt_id ?>" data-user=" <?php echo $_SESSION["username"] ?>" id="comment-form" class="max-w-md mx-auto mb-10">
        <h2 class="font-bold text-white text-[22px] mb-2">Place your comment</h2>
        <textarea id="comment" name="comment" class="w-full px-4 py-2 mb-4 leading-tight border rounded-md appearance-none focus:outline-none focus:shadow-outline"></textarea>
        <p id="error" class="text-red-500 text-xs italic"> </p>
        <button type="submit" class="w-full px-4 py-2 font-bold text-white bg-purple-600 rounded-md hover:bg-purple-700">Send</button>
    </form>
    <?php endif ?>

    <div class="max-w-md mx-auto mb-10">
    <h2 class="font-bold text-white text-[22px] mb-2"> All comments</h2>
    <div id="comments-container"> </div>
    <?php foreach($allComments as $comment): ?>
        <p class="text-white"> <?php echo $comment["comment_by"] ?> </p>
        <p class=" bg-white p-[10px] my-[10px] rounded-[10px] w-full"><?php echo $comment["comment"] ?></p>
    <?php endforeach ?>
    </div>

    <script src="js/commenting.js"></script>
    <script src="js/reportPrompt.js"></script>
    <script src="js/reportPromptPopup.js"></script>
    <script src="js/liking.js"></script>
    <script src="js/fav.js"></script>
    <?php if ($promptDetails["is_approved"] == 0 || $promptDetails["is_reported"] == 1) : ?>
        <!-- <script src="js/deny.js"></script> -->
        <script>const deny = document.getElementById("deny");
            const overlay = document.getElementById("denyPopup");
            const close2 = document.querySelector(".close");

            deny.addEventListener("click", (e) => {
                e.preventDefault();
                overlay.classList.remove("hidden");
                overlay.classList.add('flex');
            });

            close2.addEventListener("click", () => {
                overlay.classList.add("hidden");
                overlay.classList.add('flex');
            });
        </script>
    <?php endif ?>
</body>

</html>