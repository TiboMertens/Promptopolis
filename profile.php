<?php
include_once("bootstrap.php");
//Check if user is logged in
if (isset($_SESSION['loggedin'])) {
    try {
        //Get id from the url
        // $id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT) : NULL;
        if (isset($_GET['id'])) {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
        } else {
            $id = NULL;
        }

        //Get id from logged in user
        $sessionid = $_SESSION['id']['id'];

        //If id is not set, set it to the id of the logged in user
        if ($id === "" || $id === null) {
            $id = $_SESSION['id']['id'];
        }

        $user = new User();
        $user->setId($id);
        $userDetails = $user->getUserDetails();
        if ($userDetails === false) {
            throw new Exception("User not found");
        }
        //get username form userdetails
        $username = $userDetails['username'];
        //get bio from userdetails
        $bio = $userDetails['bio'];
        $profilePicture = $userDetails['profile_picture_url'];

        //get user's prompts
        $prompts = Prompt::getPromptsByUser($id);
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
} else {
    //if user is not logged in, redirect to login page
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/c2626c7e45.js" crossorigin="anonymous"></script>
</head>

<body class="bg-[#121212]">
    <?php include_once("inc/nav.inc.php") ?>
    <?php if (isset($error)) : ?>
        <div class="flex flex-col items-center justify-center h-screen">
            <h1 class="text-center text-[26px] font-bold text-white"><?php echo $error ?></h1>
            <a class="mt-4 text-[#BB86FC] hover:text-[#A25AFB]" href="index.php">Go to homepage</a>
        </div>
    <?php endif ?>

    <header class="md:mt-[50px]">
        <div class="flex flex-col items-center md:flex-row md:justify-center lg:ml-[75px]">
            <div class="mb-8 mt-10 md:mt-2"><img class="w-[150px] h-[150px] lg:w-[200px] lg:h-[200px] rounded-full" src="<?php echo $profilePicture; ?>" alt="profile picture"></div>
            <div class="mr-5 ml-5 mb-10  ">
                <div class="flex justify-center items-center md:mt-15 md:flex md:justify-start">
                    <h1 class="font-bold text-[26px] lg:text-[32px] mb-2 text-white"><?php echo htmlspecialchars($username); ?></h1>
                    <?php if ($userDetails['is_verified'] === 1) : ?>
                        <div class="ml-2 mb-1"><i class="fa-solid fa-circle-check text-[#BB86FC]" title="verified user"></i></div>
                    <?php endif ?>
                    <!-- check if the logged in user is the same as the user being viewed -->
                    <?php if ($id == $sessionid) : ?>
                        <div class="flex justify-center items-center mb-[4px] ml-4">
                            <i class="fa-solid fa-pen fa-xs mt-1 mr-2 text-[#BB86FC]"></i>
                            <a class="text-[#BB86FC] underline font-semibold rounded-lg hover:text-[#A25AFB] flex justify-center items-center" href="editProfile.php">Edit</a>
                        </div>
                    <?php endif ?>
                </div>
                <div class="text-center w-[400px] sm:w-[500px] md:text-left md:w-[500px] lg:w-[700px] text-[16px] lg:text-[18px] text-white">
                    <p><?php echo htmlspecialchars($bio); ?></p>
                </div>
            </div>
        </div>
    </header>
    <section class="mt-10">
        <h1 class="font-bold text-[24px] text-white mb-2 ml-5">Prompts</h1>
        <div class="flex overflow-x-auto bg-[#2A2A2A] m-5 pt-7 px-7 pb-4 rounded-lg">
            <div class=" flex flex-shrink-0 gap-5">
                <?php foreach ($prompts as $prompt) : ?>
                    <a href="promptDetails.php?id=<?php echo $prompt['id'] ?>&approve">
                        <img src="<?php echo $prompt['cover_url']; ?>" alt="prompt" class="w-[270px] h-[150px] object-cover object-center rounded-lg">
                        <h2 class="text-white font-bold text-[18px] mt-2"><?php echo $prompt['title'] ?></h2>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

</body>

</html>