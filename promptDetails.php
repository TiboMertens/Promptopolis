<?php
include_once("bootstrap.php");
//DO NOT FORGET XSS PROTECTION

//get id from url
if (isset($_GET['id'])) {
    $prompt_id = $_GET['id'];
    $prompt = new Prompt();
    $prompt->setId($prompt_id);

    //get prompt details
    $promptDetails = $prompt->getPromptDetails();

    //get data
    $title = $promptDetails['title'];
    $description = $promptDetails['description'];
    $cover_url = $promptDetails['cover_url'];
    $image2 = $promptDetails['image_url2'];
    $image3 = $promptDetails['image_url3'];
    $tstamp = $promptDetails['tstamp'];
    $price = $promptDetails['price'];

    //show data
    echo htmlspecialchars($title);
    echo htmlspecialchars($description);
    echo htmlspecialchars($cover_url);
    echo htmlspecialchars($image2);
    echo htmlspecialchars($image3);
    echo htmlspecialchars($tstamp);
    echo htmlspecialchars($price);
    //TODO: get user details
    // $authorID = $promptDetails['user_id'];
    // $user = new User(); 
} else {
    $error = "No prompt id provided";
}

if (isset($_GET['approve'])) {
    if ($_GET['approve'] === "true") {
        $prompt->approvePrompt();
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title><?php echo $title ?> - Details</title>
</head>

<body>
    <?php if (isset($_GET['approve'])) : ?>
        <div>
            <a href="promptDetails.php?id=<?php echo $prompt_id ?>&approve=true">Approve prompt</a>
        </div>
    <?php endif ?>
    <?php if (isset($error)) : ?>
        <div>
            <p><?php echo $error ?></p>
        </div>
    <?php endif ?>
</body>

</html>