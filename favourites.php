<?php
require_once 'vendor/autoload.php';
session_start();

if (isset($_SESSION['loggedin'])) {
    $user = new \Promptopolis\Framework\User();
    $userDetails = $user->getUserDetails($_SESSION['id']);
    $profilePicture = $userDetails['profile_picture_url'];

    $id = $_SESSION['id'];

    $limit = 15; // number of prompts to display per page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // current page number
    $offset = ($page - 1) * $limit; // calculate the offset for SQL LIMIT

    $favourites = \Promptopolis\Framework\User::getFavourites($id, $limit, $offset);

    $totalFavourites = count(Promptopolis\Framework\User::getAllFavourites($id));

    $amount = count($favourites);

    // calculate the total number of pages
    $totalPages = ceil($totalFavourites / $limit);
} else {
    header('Location: login.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Favourites</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/c2626c7e45.js" crossorigin="anonymous"></script>
    <script src="js/showcase.js" defer></script>
    <script src="js/refresh.js" defer></script>
</head>

<body class="bg-[#121212]">
<?php include_once("inc/nav.inc.php"); ?>
    <?php if (isset($error)) : ?>
        <div class="error">
            <p><?php echo $error ?></p>
        </div>
    <?php endif; ?>

    <div class="flex justify-center pb-5 pt-[70px] lg:py-15">
        <h1 class="text-white text-[24px] font-extrabold lg:text-[36px]">Your favourites</h1>
    </div>

    <main class="flex flex-wrap bg-[#2A2A2A] m-5 pt-7 px-7 pb-4 rounded-lg justify-center">
        <div id="image-container" class="flex flex-wrap gap-5 justify-center">
            <?php if ($amount <= 0) : ?>
                <p class="text-[#BB86FC] text-[20px] font-bold relative bottom-1">No prompts found</p>
            <?php endif ?>
            <?php foreach ($favourites as $fav) : ?>
                <a href="promptDetails.php?id=<?php echo htmlspecialchars($fav['prompt_id']) ?>">
                    <img src="<?php echo htmlspecialchars($fav['cover_url']) ?>" alt="Prompt" class="w-[170px] h-[100px] sm:w-[220px] sm:h-[120px] lg:w-[270px] lg:h-[150px] object-cover object-center rounded-lg">
                    <h2 class="text-white font-bold text-[14px] sm:text-[18px] mt-2"><?php echo htmlspecialchars($fav['title']) ?></h2>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- pagination links -->
        <?php if ($totalPages > 1) : ?>
            <div class="pagination text-white">
                <?php if ($page > 1) : ?>
                    <a href="favourites.php?page=<?php echo $page - 1 ?>">Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <a href="favourites.php?page=<?php echo $i?>" <?php if ($i === $page) echo 'class="active"'; ?>><?php echo $i ?></a>
                <?php endfor; ?>

                <?php if ($page < $totalPages) : ?>
                    <a href="favourites.php?page=<?php echo $page + 1 ?>">Next</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </main>
</body>

</html>