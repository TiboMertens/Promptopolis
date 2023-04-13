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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <p><?php echo htmlspecialchars($username); ?></p>
    <p><?php echo htmlspecialchars($bio); ?></p>
    <a href="editProfile.php">Edit</a>
</body>
</html>