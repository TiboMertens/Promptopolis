<?php
try {
    include_once("bootstrap.php");

    $limit = 5; // number of prompts to display per page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // current page number
    $offset = ($page - 1) * $limit; // calculate the offset for SQL LIMIT

    $filter = isset($_GET['filter']) ? $_GET['filter'] : null;
    
    if ($filter === "toApprove") {
        $approve = "&approve";
        // fetch the prompts with the selected filter
        $prompts = Prompt::getAllToApprovePrompts($limit, $offset);

        // count the total number of prompts with the selected filter
        $totalPrompts = count(Prompt::countAllToApprovePrompts());
    } else {
        // fetch all
        $approve = "";
        $prompts = Prompt::getAllPrompts($limit, $offset);

        // count all
        $totalPrompts = count(Prompt::countAllPrompts());
    }
    // calculate the total number of pages
    $totalPages = ceil($totalPrompts / $limit);
} catch (\Throwable $th) {
    $error = $th->getMessage();
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
</head>

<body>
    <?php if (isset($error)) : ?>
        <div class="error">
            <p><?php echo $error ?></p>
        </div>
    <?php endif; ?>
    <main class="flex flex-wrap">
        <div id="image-container" class="flex flex-wrap">
            <?php foreach ($prompts as $prompt) : ?>
                <a href="promptDetails.php?id=<?php echo $prompt['id'] . $approve?>">
                    <img src="<?php echo $prompt['cover_url'] ?>" alt="Prompt">
                </a>
            <?php endforeach; ?>
        </div>

        <!-- pagination links -->
        <?php if ($totalPages > 1) : ?>
            <div class="pagination">
                <?php if ($page > 1) : ?>
                    <a href="?filter=<?php echo $filter ?>&page=<?php echo $page - 1 ?>">Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <a href="?filter=<?php echo $filter ?>&page=<?php echo $i ?>" <?php if ($i === $page) echo 'class="active"'; ?>><?php echo $i ?></a>
                <?php endfor; ?>

                <?php if ($page < $totalPages) : ?>
                    <a href="?filter=<?php echo $filter ?>&page=<?php echo $page + 1 ?>">Next</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </main>

</body>

</html>