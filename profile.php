<?php
require_once 'vendor/autoload.php';
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

        $user = new \Promptopolis\Framework\User();
        if ($id != 0) {
            $userDetails = $user->getUserDetails($id);
            $ownUserDetails = $user->getUserDetails($_SESSION['id']['id']);
        } else {
            throw new Exception("User not found");
        }
        if ($userDetails === false) {
            throw new Exception("User not found");
        }

        //check if logged in user is_admin
        if ($ownUserDetails['is_admin'] === 1) {
            $ownIsAdmin = true;
            $moderator = new \Promptopolis\Framework\Moderator();
            //check if the user is_admin
            if ($userDetails['is_admin'] === 1) {
                $isAdmin = true;
                $votes = $user->getVotes($id);
            } else {
                $isAdmin = false;
                $votes = $user->getVotes($id);
            }
        } else {
            $ownIsAdmin = false;
        }
        //get username form userdetails
        $username = $userDetails['username'];
        //get bio from userdetails
        $bio = $userDetails['bio'];
        $accountProfilePicture = $userDetails['profile_picture_url'];
        $profilePicture = $ownUserDetails['profile_picture_url'];

        //get user's prompts
        $prompts = Promptopolis\Framework\Prompt::getPromptsByUser($id);
        $amount = count($prompts);

        
        if($id != $sessionid) {
            if($user->isFollowing($id)) {      
                $following=true;
                $followingbtn="Unfollow";
            } else {
                $following=false;
                $followingbtn="Follow";
            }
        } 
        
        

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

    <header class="mt-[50px] md:mt-[100px]">
        <div class="flex flex-col items-center md:flex-row md:justify-center lg:ml-[75px]">
            <div class="mb-8 mt-10 md:mt-2"><img class="w-[150px] h-[150px] lg:w-[200px] lg:h-[200px] rounded-full" src="<?php echo htmlspecialchars($accountProfilePicture); ?>" alt="profile picture"></div>
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
                    <?php elseif ($isAdmin == false && $ownIsAdmin) : ?>
                        <div class="flex justify-center items-center mb-[4px] ml-4 bg-[#121212]"><input type="submit" data-id="<?php echo htmlspecialchars($id) ?>" ?>" value="Vote for admin" name="voted" class="text-[#BB86FC] font-semibold rounded-lg hover:text-[#A25AFB] flex justify-center items-center bg-[#121212] cursor-pointer"></div>
                        <div class="ml-4 text-[#BB86FC] font-bold relative bottom-[1px]">
                            <p class="voting">Votes: <?php echo htmlspecialchars($votes)  ?>/2</p>
                        </div>
                    <?php elseif ($isAdmin && $ownIsAdmin) : ?>
                        <div class="flex justify-center items-center mb-[4px] ml-4 bg-[#121212]"><input type="submit" data-id="<?php echo htmlspecialchars($id) ?>" data-loggedInUserId="<?php echo htmlspecialchars($_SESSION['id']['id']) ?>" value="Vote to remove admin" name="voted" class="text-[#BB86FC] font-semibold rounded-lg hover:text-[#A25AFB] flex justify-center items-center bg-[#121212] cursor-pointer"></div>
                        <div class="ml-4 text-[#BB86FC] font-bold relative bottom-[2px]">
                            <p class="voting">Votes: <?php echo htmlspecialchars($votes)  ?>/2</p>
                        </div>
                    <?php endif ?>

                    <?php if ($id != $sessionid) : ?>
                        <div><button data-id="<?php echo $id?>" data-state="<?php echo $followingbtn ?>" name="follow" class="bg-[#BB86FC] hover:bg-[#A25AFB] text-white font-bold py-1 px-7 text-lg  rounded flex justify-center ml-3"><?php echo $followingbtn ?></button></div>
                    <?php endif ?>
                
                    <?php if($id != $sessionid): ?>
                    <div class="message text-red-500 text-xs italic ml-3">
                        <p class="text-red-500 text-xs italic"></p>
                    </div>
                    <?php endif ?>

                </div>
                
                <div class="text-center w-[400px] sm:w-[500px] md:text-left md:w-[500px] lg:w-[700px] text-[16px] lg:text-[18px] text-white">
                    <p><?php echo htmlspecialchars($bio); ?></p>
                </div>
                <?php if ($id == $sessionid) : ?>
                    <!-- a button that redirects to the change password page -->
                    <div class="flex justify-center md:justify-start items-center mt-[20px] mb-[4px]  ">
                        <a class="text-[#BB86FC] underline font-semibold rounded-lg hover:text-[#A25AFB] flex justify-center items-center" href="changePassword.php">Change Password</a>
                    </div>
                <?php endif ?>
                
            </div>
    </header>
    <section class="mt-10">
        <h1 class="font-bold text-[24px] text-white mb-2 ml-5">Prompts</h1>
        <div class="flex overflow-x-auto bg-[#2A2A2A] m-5 pt-7 px-7 pb-4 rounded-lg <?php echo htmlspecialchars($amount) <= 0 ? 'justify-center items-center' : '' ?>">
            <div class="flex flex-shrink-0 gap-5">
                <?php if ($amount <= 0) : ?>
                    <p class="text-[#BB86FC] text-[20px] font-bold relative bottom-1">User has no prompts</p>
                <?php endif ?>
                <?php foreach ($prompts as $prompt) : ?>
                    <a href="promptDetails.php?id=<?php echo $prompt['id'] ?>">
                        <img src="<?php echo htmlspecialchars($prompt['cover_url']); ?>" alt="prompt" class="w-[270px] h-[150px] object-cover object-center rounded-lg">
                        <h2 class="text-white font-bold text-[18px] mt-2"><?php echo htmlspecialchars($prompt['title']) ?></h2>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <script>
        // get the follow button
        const followBtn = document.querySelector('button[name="follow"]')
        // add console.log when follow button is clicked
        followBtn.addEventListener('click', (e) => {
            e.preventDefault();
            // get the id of the user being followed
            const id = e.target.dataset.id;
            // get the state of the follow button
            const state = e.target.dataset.state;
            let formData = new FormData();
            //append user id to formdata
            formData.append("id", id);
            formData.append("state", state);
            fetch("ajax/follow.php", {
                    method: "POST",
                    body: formData,
                })
                .then(function (response) {
                    return response.json();
                })
                .then(function (json) {
                    followBtn.innerHTML = json.message;
                
                    followBtn.setAttribute("data-state", json.message);
                

                });
        });
    </script>

    <script src="js/voting.js"></script>
</body>

</html>