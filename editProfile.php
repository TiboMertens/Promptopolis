<?php
include_once("bootstrap.php");
//Get id from logged in user
session_start(); 
$id = $_SESSION['id']['id'];

$user = new User();
$user->setId($id);
$userDetails= $user->getUserDetails();
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
    $user->setUsername($newUsername);
    $user->setBio($newBio);
    //update user details
    $user->updateUserDetails();
    //redirect to profile
    header("Location: profile.php");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit profile</title>
</head>
<body>
    <form action="" method="post">
        <label for="username">Username</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>">
        <label for="bio">Bio</label>
        <input type="text" name="bio" value="<?php echo htmlspecialchars($bio); ?>">
        <button type="submit">Save</button>

    </form>
</body>
</html>