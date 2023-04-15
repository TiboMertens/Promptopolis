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

<body class="bg-gray-200">
    <?php include_once("inc/nav.inc.php") ?>

    <header class="bg-slate-200 md:mt-[50px]">
        <div class="flex flex-col items-center md:flex-row md:justify-center lg:ml-[75px]">
            <div class="mb-8 mt-10 md:mt-2"><img class="w-[150px] h-[150px] lg:w-[200px] lg:h-[200px] rounded-full" src="images/signup-image.jpg" alt="profile picture"></div>
            <div class="mr-5 ml-5 mb-10  ">
                <div class="flex justify-center items-center gap-4 md:mt-15 md:flex md:justify-start">
                    <h1 class="font-bold text-[26px] lg:text-[32px] mb-2"><?php echo htmlspecialchars($username); ?></h1>
                    <div class="flex justify-center items-center mb-[4px]">
                        <i class="fa-solid fa-pen fa-xs mt-1 mr-2 text-blue-500"></i>
                        <a class="text-blue-500 underline font-semibold rounded-lg hover:text-blue-700 flex justify-center items-center" href="editProfile.php">Edit</a>
                    </div>
                </div>
                <div class="text-center w-[400px] sm:w-[500px] md:text-left md:w-[500px] lg:w-[700px] text-[16px] lg:text-[18px] ">
                    <p><?php echo htmlspecialchars($bio); ?></p>
                </div>
            </div>
        </div>
    </header>

</body>

</html>