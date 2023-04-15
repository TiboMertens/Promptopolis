<?php
include_once("bootstrap.php");
//Get id from logged in user
session_start();
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
</head>

<body>
    <?php include_once("inc/nav.inc.php") ?>

    <div class="flex justify-center items-center pt-20">
        <div class="bg-slate-200 rounded-lg p-8 max-w-md">
            <h1 class="text-2xl font-bold mb-4">Edit Your Profile</h1>
            <form action="" method="post">
                <div class="mb-4">
                    <label for="username" class="block">Username</label>
                    <input class="w-full px-3 py-2 border-2 rounded hover:border-[#143DF1] active:border-[#143DF1] <?php echo isset($usernameError) ? 'border-red-500' : ''; ?>" style="height: 35px; font-size:1rem;" type="text" name="username" value="<?php echo htmlspecialchars($username); ?>">
                    <?php if (isset($usernameError)) : ?>
                        <p class="text-red-500 text-xs italic"><?php echo $usernameError; ?></p>
                    <?php endif; ?>
                </div>
                <div class="mb-4">
                    <label for="bio" class="block">Bio</label>
                    <textarea class="w-full px-3 py-2 border-2 rounded" rows="4" name="bio"><?php echo htmlspecialchars($bio); ?></textarea>
                </div>
                <div class="flex justify-center">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>



</body>

</html>