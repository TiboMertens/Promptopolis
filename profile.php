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

<body class="bg-gray-400">
    <?php include_once("inc/nav.inc.php") ?>

    <header class="bg-slate-200">
        <div class="flex flex-col items-center md:flex-row md:items-center xl:items-center">
            <img class="w-24 h-24 mt-10 md:w-36 md:h-36 md:m-20 xl:w-48 xl:h-48 xl:m-20 rounded-full" src="images/signup-image.jpg" alt="">
            <div class="mr-5 ml-5 mb-10  ">
                <div class="mt-10 flex justify-center items-center gap-5 md:mt-15 xl:mt-20 md:flex md:justify-start">
                    <h1 class="font-bold text-[36px] ml-2 mb-2 xl:text-[50px]"><?php echo htmlspecialchars($username); ?></h1>
                    <a class="w-20 h-10 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-700 flex justify-center items-center" href="editProfile.php">Edit</a>
                </div>

                <p class="w-50"><?php echo htmlspecialchars($bio); ?></p>
            </div>
        </div>
    </header>

</body>

</html>