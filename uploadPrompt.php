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
        $imageFileType = strtolower(pathinfo($target_file_overview, PATHINFO_EXTENSION));
    }

        if (!empty($_POST["submit"])) {

            $prompt = new Prompt();
            if (!empty($_FILES["mainImage"]["name"])) {

                $check = getimagesize($_FILES["mainImage"]["tmp_name"]);
                if ($check !== false) {

                    $uploadOk = 1;
                } else {
                    throw new Exception("File is not an image.");
                    $uploadOk = 0;
                }




                // Check file size, if file is larger than 1MB give error

                if ($_FILES["mainImage"]["size"] < 1000000) {

                    $uploadOk = 1;
                } else {
                    throw new Exception("File is too large.");
                }

                // Allow certain file formats
                if (
                    $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                    && $imageFileType != "gif"
                ) {
                    throw new Exception("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
                    $uploadOk = 0;
                }

                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    throw new Exception("Sorry, your file was not uploaded.");
                    // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["mainImage"]["tmp_name"], $target_file)) {

                        //var_dump the file that was uploaded
                        $mainImage = $target_file;
                        // $user->setProfile_picture_url($target_file);
                    } else {
                        throw new Exception("Sorry, there was an error uploading your file.");
                    }
                }
            }
            
            // Validate overview image file
            if (!empty($_FILES["overviewImage"]["name"])) {
                $check = getimagesize($_FILES["overviewImage"]["tmp_name"]);
                if ($check === false) {
                    throw new Exception("File is not an image.");
                }

                if ($_FILES["overviewImage"]["size"] > 1000000) {
                    throw new Exception("File is too large.");
                }

                if (
                    $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                    && $imageFileType != "gif"
                ) {
                    throw new Exception("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
                }

                if (move_uploaded_file($_FILES["overviewImage"]["tmp_name"], $target_file_overview)) {
                    $overviewImage = $target_file_overview;
                } else {
                    throw new Exception("Sorry, there was an error uploading your file.");
                }
            }
            $prompt->setUser_id($_SESSION["id"]["id"]);
            $prompt->setTitle($_POST["title"]);
            $prompt->setDescription($_POST["description"]);
            $prompt->setPrice($_POST["price"]);
            $prompt->setModel($_POST["model"]);
            $prompt->setMainImage($mainImage);
            $prompt->setOverviewImage($overviewImage);
            

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
    <script src="js/previewImage.js" defer></script>
    <title>Sell prompt</title>
</head>

<body>
    <form action="" method="post" enctype="multipart/form-data">
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
        <div><img id="preview" src="" alt="cover image"></div>
        <input type="file" name="mainImage" id="mainImage" onchange="previewFile()">
        <label for="overviewImage">Overview image</label>
        <input type="file" name="overviewImage" id="overviewImage" onchange="previewFileOverview()">
        <div><img src="" alt="overview image" id="previewOverview"></div>
        <input name="submit" type="submit" value="Sell prompt">
    </form>
</body>

</html>