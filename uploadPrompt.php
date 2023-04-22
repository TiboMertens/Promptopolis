<?php
include_once("bootstrap.php");

if (isset($_SESSION["loggedin"])) {

    if (!empty($_FILES["mainImage"]["tmp_name"])) {

        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["mainImage"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    }
    if (!empty($_FILES["overviewImage"]["tmp_name"])) {
        $target_dir = "uploads/";
        $target_file_overview = $target_dir . basename($_FILES["overviewImage"]["name"]);
        $uploadOk = 1;
        $imageFileType_overview = strtolower(pathinfo($target_file_overview, PATHINFO_EXTENSION));
    }

    if (!empty($_POST["submit"])) {
        try {
            $prompt = new Prompt();

            $prompt->setUser_id($_SESSION["id"]["id"]);

            $exceptionCaught = false;

            try {
                $prompt->setTitle($_POST["title"]);
            } catch (Exception $e) {
                $titleError = $e->getMessage();
                $exceptionCaught = true;
            }
            try {
                $prompt->setDescription($_POST["description"]);
            } catch (Exception $e) {
                $descriptionError = $e->getMessage();
                $exceptionCaught = true;
            }
            try {
                $prompt->setPrice($_POST["price"]);
            } catch (Exception $e) {
                $priceError = $e->getMessage();
                $exceptionCaught = true;
            }
            try {
                $prompt->setModel($_POST["model"]);
            } catch (Exception $e) {
                $modelError = $e->getMessage();
                $exceptionCaught = true;
            }
            try {
                if(!isset($imageFileType)){
                    throw new exception("Please upload an image");
                }else{
                    $prompt->setMainImage($imageFileType, $target_file);
                }
            } catch (Exception $e) {
                $mainImageError = $e->getMessage();
                $exceptionCaught = true;
            }
            try {
                if(!isset($imageFileType_overview)){
                    throw new exception("Please upload an image");
                }else{
                    $prompt->setOverviewImage($imageFileType_overview, $target_file_overview);
                }
            } catch (Exception $e) {
                $overviewImageError = $e->getMessage();
                $exceptionCaught = true;
            }

            $tags = array();
            if (!empty($_POST['tag1'])) {
                $tags[] = $_POST['tag1'];
            }
            if (!empty($_POST['tag2'])) {
                $tags[] = $_POST['tag2'];
            }
            if (!empty($_POST['tag3'])) {
                $tags[] = $_POST['tag3'];
            }
            try {
                $prompt->setTags($tags);
            } catch (Exception $e) {
                $tagsError = $e->getMessage();
                $exceptionCaught = true;
            }

            if (!$exceptionCaught) {
                $prompt->savePrompt();
                header("Location: profile.php");
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
} else {
    header("Location: login.php");
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="js/tagInput.js" defer></script>
    <script src="js/previewImage.js" defer></script>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Sell prompt</title>
</head>

<body>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="title">Title</label>
        <input type="text" name="title" id="title">
        <?php if (isset($titleError)) : ?>
            <p class="text-red-500 text-xs italic"><?php echo $titleError; ?></p>
        <?php endif; ?>
        <label for="description">Description</label>
        <textarea name="description" id="description" cols="30" rows="10"></textarea>
        <?php if (isset($descriptionError)) : ?>
            <p class="text-red-500 text-xs italic"><?php echo $descriptionError; ?></p>
        <?php endif; ?>
        <label for="price">Price</label>
        <input type="text" name="price" id="price">
        <?php if (isset($priceError)) : ?>
            <p class="text-red-500 text-xs italic"><?php echo $priceError; ?></p>
        <?php endif; ?>
        <label for="model">Model</label>
        <select name="model" class="rounded-md">
            <option value="Midjourney">Midjourney</option>
            <option value="Dall-E">Dall-E</option>
        </select>
        <?php if (isset($modelError)) : ?>
            <p class="text-red-500 text-xs italic"><?php echo $modelError; ?></p>
        <?php endif; ?>
        <label for="tag1">tag 1</label>
        <input type="text" name="tag1" id="tag1">
        <div id="tag-container"></div>
        <button name="tag" id="add-tag-btn">Add Tag</button>
        <?php if (isset($tagsError)) : ?>
            <p class="text-red-500 text-xs italic"><?php echo $tagsError; ?></p>
        <?php endif; ?>
        <label for="mainImage">Main image</label>
        <div><img id="preview" src="" alt="cover image"></div>
        <input type="file" name="mainImage" id="mainImage" onchange="previewFile()">
        <?php if (isset($mainImageError)) : ?>
            <p class="text-red-500 text-xs italic"><?php echo $mainImageError; ?></p>
        <?php endif; ?>
        <label for="overviewImage">Overview image</label>
        <input type="file" name="overviewImage" id="overviewImage" onchange="previewFileOverview()">
        <div><img src="" alt="overview image" id="previewOverview"></div>
        <?php if (isset($overviewImageError)) : ?>
            <p class="text-red-500 text-xs italic"><?php echo $overviewImageError; ?></p>
        <?php endif; ?>
        <input name="submit" type="submit" value="Sell prompt">
    </form>
</body>

</html>