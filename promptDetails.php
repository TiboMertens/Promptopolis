<?php
include_once("bootstrap.php");
//DO NOT FORGET XSS PROTECTION

if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
}
try {
    //if id is set and not NULL, get prompt details
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $prompt_id = $_GET['id'];
        $prompt = new Prompt();
        $prompt->setId($prompt_id);

        //get prompt details
        $promptDetails = $prompt->getPromptDetails();

        //get data
        $title = $promptDetails['title'];
        $description = $promptDetails['description'];
        $cover_url = $promptDetails['cover_url'];
        $image2 = $promptDetails['image_url2'];
        $tstamp = $promptDetails['tstamp'];
        $price = $promptDetails['price'];
        $tag1 = $promptDetails['tag1'];
        $tag2 = $promptDetails['tag2'];
        $tag3 = $promptDetails['tag3'];
        $model = $promptDetails['model'];

        //get author id
        $authorID = $promptDetails['user_id'];
        $user = new User();
        $user->setId($authorID);
        //check if user is a moderator
        $isModerator = $user->isModerator($_SESSION['id']['id']);
        //get author name
        $userDetails = $user->getUserDetails();
        $authorName = $userDetails['username'];
    } else {
        throw new exception('No id provided');
    }

    //if on aprove page and approve button is clicked, approve prompt
    if (isset($_GET['approve'])) {
        //if user is not a moderator, redirect to index
        if (!$isModerator) {
            header("Location: index.php");
        } else {
            $moderator = new Moderator();
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
    <title><?php echo $title ?> - Details</title>
</head>

<body class="bg-[#121212]">
    <?php include_once("inc/nav.inc.php"); ?>
    <!-- if error, show error -->
    <?php if (isset($error)) : ?>
        <div class="flex flex-col items-center justify-center h-screen">
            <h1 class="text-center text-[26px] font-bold text-white"><?php echo $error ?></h1>
            <a class="mt-4 text-[#BB86FC] hover:text-[#A25AFB]" href="index.php">Go to homepage</a>
        </div>
    <?php else : ?>
        <main class="ml-auto mr-auto max-w-[500px] md:flex md:max-w-[700px] lg:max-w-[900px] xl:max-w-[1100px]">
            <div class="m-5 md:mt-[60px] lg:mt-5">
                <div class=""><img src="<?php echo htmlspecialchars($cover_url); ?>" alt="prompt cover" class="rounded-md xl:w-[1100px]"></div>
                <div class="text-[#cccccc] text-[14px] lg:text-[16px]">
                    <h1 class="text-[32px] lg:text-[36px] text-white font-bold mt-2 mb-3"><?php echo htmlspecialchars($title); ?></h1>
                    <div class="flex justify-between mb-3">
                        <div class="flex-1">
                            <p>Uploaded on: &nbsp;<?php echo htmlspecialchars($tstamp); ?></p>
                        </div>
                        <div class="flex-1 justify-end mr-5 md:mr-0">
                            <p class="text-right">Made by: &nbsp; <a href="profile.php?id=<?php echo $authorID ?>"><span class="underline font-bold text-[#BB86FC] hover:text-[#A25AFB]"><?php echo htmlspecialchars($authorName); ?></span></a></p>
                        </div>
                    </div>
                    <div class="flex justify-between mb-3">
                        <div class="flex-1">
                            <p>Model: &nbsp; <?php echo htmlspecialchars($model); ?></p>
                        </div>
                        <div class="flex flex-1 gap-4 justify-end mr-5 md:mr-0">
                            <p>Tags: </p>
                            <p><?php echo htmlspecialchars($tag1); ?></p>
                            <p><?php echo htmlspecialchars($tag2); ?></p>
                            <p><?php echo htmlspecialchars($tag3); ?></p>
                        </div>
                    </div>
                    <div class="mr-5 mb-5">
                        <h2 class="font-bold text-white text-[22px] mb-2">Description</h2>
                        <p><?php echo htmlspecialchars($description); ?></p>
                    </div>
                    <div class="flex mb-3 items-center">
                        <!-- if on approve page, show approve button -->
                        <?php if (isset($_GET['approve'])) : ?>
                            <form action="" method="post">
                                <button type=submit name=approve class="bg-[#BB86FC] hover:bg-[#A25AFB] text-white font-bold py-2 px-4 rounded mb-2">Approve prompt</a>
                            </form>
                        <?php else : ?>
                            <a href="#" class="bg-[#BB86FC] hover:bg-[#A25AFB] text-white font-bold py-2 px-4 rounded mb-2">Buy prompt</a>
                            <p class="text-white text-[16px] font-bold relative bottom-1 ml-3"><?php echo "€" . htmlspecialchars($price); ?></p>
                        <?php endif ?>
                    </div>

                </div>
            </div>
            <div class="flex justify-center ml-3 md:mt-[60px] lg:mt-5 lg:items-center xl:items-start"><img src="<?php echo htmlspecialchars($image2); ?>" alt="prompt example" class="rounded-md h-[300px] w-[500px] md:h-[450px] md:w-[700px] ld:h-[550px]"></div>
        </main>
    <?php endif ?>
</body>

</html>