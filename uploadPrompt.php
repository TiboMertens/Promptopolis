<?php
include_once("bootstrap.php");

if (isset($_SESSION["loggedin"])) {
    if (!empty($_POST["submit"])) {

        $prompt = new Prompt();
        $prompt->setUser_id($_SESSION["id"]["id"]);
        $prompt->setTitle($_POST["title"]);
        $prompt->setDescription($_POST["description"]);
        $prompt->setPrice($_POST["price"]);
        $prompt->setModel($_POST["model"]);

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
        $prompt->setTags($tags);
        
        // $prompt->setMainImage($_POST["image"]);
        // $prompt->setOverviewImage($_POST["overviewImage"]);

        $prompt->savePrompt();
        header("Location: profile.php");
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
    <title>Sell prompt</title>
</head>

<body>
    <form action="" method="post">
        <label for="title">Title</label>
        <input type="text" name="title" id="title">
        <label for="description">Description</label>
        <textarea name="description" id="description" cols="30" rows="10"></textarea>
        <label for="price">Price</label>
        <input type="text" name="price" id="price">
        <label for="model">Model</label>
        <select name="model" class="rounded-md">
            <option value="Midjourney">Midjourney</option>
            <option value="Dall-E">Dall-E</option>
        </select>
        <label for="tag1">tag 1</label>
        <input type="text" name="tag1" id="tag1">
        <div id="tag-container"></div>
        <button name="tag" id="add-tag-btn">Add Tag</button>
        <label for="mainImage">Main image</label>
        <input type="file" name="mainImage" id="mainImage">
        <label for="overviewImage">Overview image</label>
        <input type="file" name="overviewImage" id="overviewImage">
        <input name="submit" type="submit" value="Sell prompt">
    </form>
</body>

</html>