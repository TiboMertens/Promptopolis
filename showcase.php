<?php
try {
    require_once 'vendor/autoload.php';
    include_once("bootstrap.php");

    $pageName = 'showcase';

    if (!empty($_GET['search'])) {
        $searchTerm = $_GET['search'];
    } else {
        $searchTerm = "";
    }

    if (isset($_SESSION["loggedin"])) {
        $user = new \Promptopolis\Framework\User();
        $userDetails = $user->getUserDetails($_SESSION['id']['id']);
        $profilePicture = $userDetails['profile_picture_url'];
        if (Promptopolis\Framework\User::isModerator($_SESSION['id']['id'])) {
            $moderator = true;
        }
    }

    $filters = ['filterApprove', 'filterOrder', 'filterModel', 'filterCategory'];

    // This loop iterates over an array of the four filter variables, and for each one,
    // it checks if the corresponding $_GET parameter is set. If it is, it sets the variable with a dynamic variable variable
    // ($$filter) to the value of the parameter. If it's not set, it sets the variable to the default value of 'all'.
    foreach ($filters as $filter) {
        if (!empty($_GET[$filter])) {
            //$$ is a dynamic variable, it will create a variable with the name of the value of $filter
            $$filter = $_GET[$filter];
        } else {
            $$filter = 'all';
        }
    }

    if (!isset($_SESSION['loggedin']) || !Promptopolis\Framework\User::isModerator($_SESSION['id']['id']) || $filterApprove != 'not_approved') {
        $filterApprove = 'all';
    }

    $limit = 15; // number of prompts to display per page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // current page number
    $offset = ($page - 1) * $limit; // calculate the offset for SQL LIMIT

    // get the prompts with the selected filter, limited to the current page
    $prompts = Promptopolis\Framework\Prompt::filter($filterApprove,  $filterOrder, $filterModel, $filterCategory, $searchTerm, $limit, $offset);

    // count the total number of prompts with the selected filter
    $totalPrompts = count(Promptopolis\Framework\Prompt::getAll($filterApprove, $filterOrder, $filterModel, $filterCategory, $searchTerm));

    $amount = count($prompts);

    // calculate the total number of pages
    $totalPages = ceil($totalPrompts / $limit);
} catch (Throwable $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>showcase</title>
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
        <h1 class="text-white text-[24px] font-extrabold lg:text-[36px]">Prompt showcase</h1>
    </div>
    <section class="flex justify-between mr-6">
        <div class="ml-6 flex justify-start"></div>
        <div class="flex justify-end">
            <form id="filter-form" method="get">
                <?php if (isset($_SESSION['loggedin'])) : ?>
                    <?php if (isset($moderator)) : ?>
                        <label for="filterApprove" class="text-white relative bottom-[2px] ml-[10px]">Status: &nbsp;</label>
                        <select name="filterApprove" class="filter-select rounded-md">
                            <option value="all">All</option>
                            <option value="not_approved">Not approved</option>
                        </select>
                    <?php endif; ?>
                <?php endif; ?>
                <label for="filterOrder" class="text-white relative bottom-[2px] ml-[10px]">Order by: &nbsp;</label>
                <select name="filterOrder" class="filter-select rounded-md">
                    <option value="new">New</option>
                    <option value="old">Old</option>
                    <option value="low">Price(lowest)</option>
                    <option value="high">Price(highest)</option>
                </select>
                <label for="filterModel" class="text-white relative bottom-[2px] ml-[10px]">Model: &nbsp;</label>
                <select name="filterModel" class="filter-select rounded-md">
                    <option value="all">All</option>
                    <option value="Midjourney">Midjourney</option>
                    <option value="Dall-E">Dall-E</option>
                </select>
                <label class="text-white relative bottom-[2px] ml-[10px]" for="filterCategory">Category</label>
                <select class="filter-select rounded-md" name="filterCategory" class="rounded-md">
                    <option value="None">None</option>
                    <option value="Nature">Nature</option>
                    <option value="Logo">Logo</option>
                    <option value="Civilisation">Civilisation</option>
                    <option value="Line_art">Line art</option>
                </select>
            </form>
        </div>
    </section>
    <main class="flex flex-wrap bg-[#2A2A2A] m-5 pt-7 px-7 pb-4 rounded-lg justify-center">
        <div id="image-container" class="flex flex-wrap gap-5 justify-center">
            <?php if ($amount <= 0) : ?>
                <p class="text-[#BB86FC] text-[20px] font-bold relative bottom-1">No prompts found</p>
            <?php endif ?>
            <?php foreach ($prompts as $prompt) : ?>
                <a href="promptDetails.php?id=<?php echo htmlspecialchars($prompt['id']) ?>">
                    <img src="<?php echo htmlspecialchars($prompt['cover_url']) ?>" alt="Prompt" class="w-[170px] h-[100px] sm:w-[220px] sm:h-[120px] lg:w-[270px] lg:h-[150px] object-cover object-center rounded-lg">
                    <h2 class="text-white font-bold text-[14px] sm:text-[18px] mt-2"><?php echo htmlspecialchars($prompt['title']) ?></h2>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- pagination links -->
        <?php if ($totalPages > 1) : ?>
            <div class="pagination text-white">
                <?php if ($page > 1) : ?>
                    <a href="?filterApprove=<?php echo $filterApprove . "&filterOrder=" . $filterOrder . "&filterModel=" . $filterModel ?>&page=<?php echo $page - 1 ?>">Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <a href="?filterApprove=<?php echo $filterApprove . "&filterOrder=" . $filterOrder . "&filterModel=" . $filterModel ?>&page=<?php echo $i ?>" <?php if ($i === $page) echo 'class="active"'; ?>><?php echo $i ?></a>
                <?php endfor; ?>

                <?php if ($page < $totalPages) : ?>
                    <a href="?filterApprove=<?php echo $filterApprove . "&filterOrder=" . $filterOrder . "&filterModel=" . $filterModel ?>&page=<?php echo $page + 1 ?>">Next</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </main>

</body>

</html>