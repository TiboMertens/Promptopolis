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
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <form action="" method="post">
        <label for="username">Username</label>
        <input class="w-full lg:w-55 px-3 py-2 border-2 rounded hover:border-[#143DF1] active:border-[#143DF1] <?php echo isset($usernameError) ? 'border-red-500' : ''; ?>" style="height: 35px; font-size:1rem;"  type="text" name="username" value="<?php echo htmlspecialchars($username); ?>">
        <?php if (isset($usernameError)) : ?>
            <p class="text-red-500 text-xs italic" style="width: 200px;"><?php echo $usernameError; ?></p>
        <?php endif; ?>
        <label for="bio">Bio</label>
        <input type="text" name="bio" value="<?php echo htmlspecialchars($bio); ?>">
        <button type="submit">Save</button>

    </form>
</body>

</html>