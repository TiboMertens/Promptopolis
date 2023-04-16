<?php
include_once("bootstrap.php");
//DO NOT FORGET XSS PROTECTION

if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
}
//if id is set, get prompt details
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
    // echo htmlspecialchars($title);
    // echo htmlspecialchars($description);
    // echo htmlspecialchars($cover_url);
    // echo htmlspecialchars($image2);
    // echo htmlspecialchars($image3);
    // echo htmlspecialchars($tstamp);
    // echo htmlspecialchars($price);

    //get author id
    $authorID = $promptDetails['user_id'];
    $user = new User();
    $user->setId($authorID);
    //check if user is a moderator
    $isModerator = $user->isModerator($_SESSION['id']['id']);
    //get author name
    $userDetails = $user->getUserDetails();
    $authorName = $userDetails['username'];
} else {
    $error = "No prompt id provided";
}

//if on aprove page and approve button is clicked, approve prompt
if (isset($_GET['approve'])) {
    //if user is not a moderator, redirect to index
    if (!$isModerator) {
        header("Location: index.php");
    } else {
        $moderator = new Moderator();
    }

    if ($_GET['approve'] === "true") {
        $moderator->approve($prompt_id);
        //if prompt is appoved, check if user can be verified - if yes, verify user
        if ($user->checkToVerify()) {
            $user->verify();
        }
        //redirect to showcase
        header("Location: showcase.php");
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <title><?php echo $title ?> - Details</title>
</head>

<body>
    <!-- if on approve page, show approve button -->
    <?php if (isset($_GET['approve'])) : ?>
        <div>
            <a href="promptDetails.php?id=<?php echo $prompt_id ?>&approve=true">Approve prompt</a>
        </div>
    <?php endif ?>
    <!-- if error, show error -->
    <?php if (isset($error)) : ?>
        <div>
            <p><?php echo $error ?></p>
        </div>
    <?php endif ?>
</body>

</html>