<?php
try {
    include_once("bootstrap.php");
    $filters = ['filterApprove', 'filterDate', 'filterPrice', 'filterModel'];

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

    if (!isset($_SESSION['loggedin']) || !User::isModerator($_SESSION['id']['id']) || $filterApprove != 'not_approved') {
        $filterApprove = 'all';
    }

    if ($filterDate != 'all' || $filterDate != 'new' || $filterDate != 'old') {
        $filterDate = 'all';
    }

    if ($filterPrice != 'all' || $filterPrice != 'low' || $filterPrice != 'high') {
        $filterPrice = 'all';
    }

    if ($filterModel != 'all' || $filterModel != 'Midjourney' || $filterModel != 'Dall-E') {
        $filterModel = 'all';
    }

    if ($filterApprove == 'not_approved') {
        $approve = "&approve";
    } else {
        $approve = "";
    }

    $limit = 15; // number of prompts to display per page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // current page number
    $offset = ($page - 1) * $limit; // calculate the offset for SQL LIMIT

    // get the prompts with the selected filter, limited to the current page
    $prompts = Prompt::filter($filterApprove,  $filterDate, $filterPrice, $filterModel, $limit, $offset);

    // count the total number of prompts with the selected filter
    $totalPrompts = count(Prompt::getAll($filterApprove, $filterDate, $filterPrice, $filterModel));

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
</head>

<body class="bg-[#121212]">
    <?php include_once("inc/nav.inc.php"); ?>
    <?php if (isset($error)) : ?>
        <div class="error">
            <p><?php echo $error ?></p>
        </div>
    <?php endif; ?>

    <div class="flex justify-center py-5 lg:py-15">
        <h1 class="text-white text-[24px] font-extrabold lg:text-[36px]">Prompt showcase</h1>
    </div>
    <section class="flex justify-between mr-6">
        <div class="ml-6 flex justify-start">
            <p class="text-white">Current filter:
                <?php if (isset($_SESSION['loggedin'])) : ?>
                    <?php if (User::isModerator($_SESSION['id']['id'])) : ?>
                        <a id="approve" href="showcase.php?<?php echo "filterApprove=all" . "&filterDate=" . $filterDate . "&filterPrice=" . $filterPrice . "&filterModel=" . $filterModel ?>"><span class="text-[#BB86FC] hover:bg-[#A25AFB] hover:text-white px-[7px] pb-[2px] rounded-lg"><?php echo $filterApprove ?><i class="fa-solid fa-xmark fa-2xs ml-2 relative top-[2px]"></i></span></a>
                    <?php endif; ?>
                <?php endif; ?>
                <a id="date" href="showcase.php?<?php echo "filterApprove=" . $filterDate . "&filterDate=All" . "&filterPrice=" . $filterPrice . "&filterModel=" . $filterModel ?>"><span class="text-[#BB86FC] hover:bg-[#A25AFB] hover:text-white px-[7px] pb-[2px] rounded-lg"><?php echo $filterDate ?><i class="fa-solid fa-xmark fa-2xs ml-2 relative top-[2px]"></i></span></a>
                <a id="price" href="showcase.php?<?php echo "filterApprove=" . $filterDate . "&filterDate=" . $filterDate . "&filterPrice=All" . "&filterModel=" . $filterModel ?>"><span class="text-[#BB86FC] hover:bg-[#A25AFB] hover:text-white px-[7px] pb-[2px] rounded-lg"><?php echo $filterPrice ?><i class="fa-solid fa-xmark fa-2xs ml-2 relative top-[2px]"></i></span></a>
                <a id="model" href="showcase.php?<?php echo "filterApprove=" . $filterDate . "&filterDate=" . $filterDate . "&filterPrice=" . $filterPrice . "&filterModel=All" ?>"><span class="text-[#BB86FC] hover:bg-[#A25AFB] hover:text-white px-[7px] pb-[2px] rounded-lg"><?php echo $filterModel ?><i class="fa-solid fa-xmark fa-2xs ml-2 relative top-[2px]"></i></span></a>
            </p>
        </div>
        <div class="flex justify-end">
            <form id="filter-form" method="get">
                <?php if (isset($_SESSION['loggedin'])) : ?>
                    <?php if (User::isModerator($_SESSION['id']['id'])) : ?>
                        <label for="filterApprove" class="text-white relative bottom-[2px] ml-[10px]">Status: &nbsp;</label>
                        <select name="filterApprove" class="filter-select rounded-md">
                            <option value="all">All</option>
                            <option value="not_approved">Not approved</option>
                        </select>
                    <?php endif; ?>
                <?php endif; ?>
                <label for="filterDate" class="text-white relative bottom-[2px] ml-[10px]">New/old: &nbsp;</label>
                <select name="filterDate" class="filter-select rounded-md">
                    <option value="all">All</option>
                    <option value="new">New</option>
                    <option value="old">Old</option>
                </select>
                <label for="filterPrice" class="text-white relative bottom-[2px] ml-[10px]">Price: &nbsp;</label>
                <select name="filterPrice" class="filter-select rounded-md">
                    <option value="all">All</option>
                    <option value="low">Price(lowest)</option>
                    <option value="high">Price(highest)</option>
                </select>
                <label for="filterModel" class="text-white relative bottom-[2px] ml-[10px]">Model: &nbsp;</label>
                <select name="filterModel" class="filter-select rounded-md">
                    <option value="all">All</option>
                    <option value="Midjourney">Midjourney</option>
                    <option value="Dall-E">Dall-E</option>
                </select>
            </form>
        </div>
    </section>
    <main class="flex flex-wrap bg-[#2A2A2A] m-5 pt-7 px-7 pb-4 rounded-lg justify-center">
        <div id="image-container" class="flex flex-wrap gap-5 justify-center">
            <?php foreach ($prompts as $prompt) : ?>
                <a href="promptDetails.php?id=<?php echo $prompt['id'] . $approve ?>">
                    <img src="<?php echo $prompt['cover_url'] ?>" alt="Prompt" class="w-[170px] h-[100px] sm:w-[220px] sm:h-[120px] lg:w-[270px] lg:h-[150px] object-cover object-center rounded-lg">
                    <h2 class="text-white font-bold text-[14px] sm:text-[18px] mt-2"><?php echo $prompt['title'] ?></h2>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- pagination links -->
        <?php if ($totalPages > 1) : ?>
            <div class="pagination text-white">
                <?php if ($page > 1) : ?>
                    <a href="?filterApprove=<?php echo $filterApprove . "&filterDate=" . $filterDate . "&filterPrice=" . $filterPrice . "&filterModel=" . $filterModel ?>&page=<?php echo $page - 1 ?>">Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <a href="?filterApprove=<?php echo $filterApprove . "&filterDate=" . $filterDate . "&filterPrice=" . $filterPrice . "&filterModel=" . $filterModel ?>&page=<?php echo $i ?>" <?php if ($i === $page) echo 'class="active"'; ?>><?php echo $i ?></a>
                <?php endfor; ?>

                <?php if ($page < $totalPages) : ?>
                    <a href="?filterApprove=<?php echo $filterApprove . "&filterDate=" . $filterDate . "&filterPrice=" . $filterPrice . "&filterModel=" . $filterModel ?>&page=<?php echo $page + 1 ?>">Next</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </main>

</body>

</html>