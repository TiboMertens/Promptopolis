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
    
    
    $limit = 5; // number of prompts to display per page
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
</head>

<body class="bg-[#121212]">
    <?php include_once("inc/nav.inc.php"); ?>
    <div>
        <form id="filter-form" method="get">
            <select name="filterApprove" class="filter-select">
                <option value="all">All</option>
                <option value="approved">Approved</option>
                <option value="not_approved">Not approved</option>
            </select>
            <select name="filterDate" class="filter-select">
                <option value="all">All</option>
                <option value="new">New</option>
                <option value="old">Old</option>
            </select>
            <select name="filterPrice" class="filter-select">
                <option value="all">All</option>
                <option value="cheap">Price(lowest)</option>
                <option value="expensive">Price(highest)</option>
            </select>
            <select name="filterModel" class="filter-select">
                <option value="all">All</option>
                <option value="Midjourney">Midjourney</option>
                <option value="Dall-E">Dall-E</option>
            </select>
        </form>

        <script>
            const filterSelects = document.querySelectorAll('.filter-select');

            // Check if there's a selected filter in localStorage for each select element
            filterSelects.forEach(select => {
                const storedFilter = localStorage.getItem(`selectedFilter_${select.name}`);
                if (storedFilter) {
                    select.value = storedFilter;
                }

                // Add an event listener to each select element
                select.addEventListener('change', () => {
                    localStorage.setItem(`selectedFilter_${select.name}`, select.value);
                    document.getElementById('filter-form').submit();
                });
            });
        </script>
    </div>
    <?php if (isset($error)) : ?>
        <div class="error">
            <p><?php echo $error ?></p>
        </div>
    <?php endif; ?>
    <div class="flex justify-center py-5 lg:py-15">
        <h1 class="text-white text-[24px] font-extrabold lg:text-[36px]">Prompt showcase</h1>
    </div>
    <div class="ml-6">
        <p class="text-white">Current filter: <a href="showcase.php?filter=All"><span class="text-[#BB86FC] hover:bg-[#A25AFB] hover:text-white px-[7px] pb-[2px] rounded-lg"><?php echo $filter ?><i class="fa-solid fa-xmark fa-2xs ml-2 relative top-[2px]"></i></span></a></p>
    </div>
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
            <div class="pagination">
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