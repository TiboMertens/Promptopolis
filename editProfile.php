<?php
include_once("bootstrap.php");

//check if user is logged in, else redirect to login page
if (isset($_SESSION['loggedin'])) {
    //Get id from logged in user
    $id = $_SESSION['id']['id'];

    $user = new User();
    $user->setId($id);
    $userDetails = $user->getUserDetails();
    //get username form userdetails
    $username = $userDetails['username'];
    //get bio from userdetails
    $bio = $userDetails['bio'];
    //check if button save is clicked
    if (!empty($_POST)) {
        //get data from form
        $newUsername = $_POST['username'];
        $newBio = $_POST['bio'];
        //set data to user
        try {
            $user->setUsername($newUsername);
            $user->setBio($newBio);
            //update user details
            $user->updateUserDetails();
            //redirect to profile
            header("Location: profile.php");
        } catch (Throwable $e) {
            $usernameError = $e->getMessage();
        }
    }
} else {
    header('Location: login.php');
}

if (isset($_POST['delete'])) {
    try {
        // create a new User object and set its id to the current user's id
        $user = new User();
        $user->setId($id);

        // delete the user's account and redirect to the login page
        $user->deleteAccount();
        session_destroy();
        
        header('Location: login.php');
        exit();
    } catch (Throwable $e) {
        $deleteError = $e->getMessage();
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit profile</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/c2626c7e45.js" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/c2626c7e45.js" crossorigin="anonymous"></script>
</head>

<body class="bg-[#121212]">
    <?php include_once("inc/nav.inc.php") ?>

    <div class="flex justify-center items-center pt-20">
        <div class="bg-[#2A2A2A] rounded-lg p-8 max-w-md">
            <div class="text-white">
                <a href="profile.php?id=<?php echo $id ?>"><i class="fa-solid fa-arrow-left"></i></a>
            </div>
            <h1 class="text-2xl font-bold mb-4 text-white">Edit Your Profile</h1>
            <form action="" method="post">
                <div class="mb-4">
                    <label for="username" class="block font-bold mb-0.5 text-white">Username</label>
                    <input class="w-full px-3 py-2 border-[3px] rounded hover:border-[#A25AFB] active:border-[#A25AFB] <?php echo isset($usernameError) ? 'border-red-500' : ''; ?>" style="height: 35px; font-size:1rem;" type="text" name="username" value="<?php echo htmlspecialchars($username); ?>">
                    <?php if (isset($usernameError)) : ?>
                        <p class="text-red-500 text-xs italic"><?php echo $usernameError; ?></p>
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <label for="bio" class="block font-bold mb-0.5 text-white">Bio</label>
                    <textarea class="w-full px-3 py-2 border-[3px] rounded hover:border-[#A25AFB] active:border-[#A25AFB]" rows="4" name="bio"><?php echo htmlspecialchars($bio); ?></textarea>
                </div>
                <div class="flex justify-center">
                    <button type="submit" class="bg-[#BB86FC] hover:bg-[#A25AFB] text-white px-4 py-2 rounded" style="padding-left: 6rem; padding-right: 6rem;">Save</button>
                </div>
                <div class="flex justify-center">
                        <button name= "delete" class="text-red-500 mt-5" href="#">Delete account</button>
                </div>
            </form>
        </div>
    </div>



</body>

</html>