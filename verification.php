<?php
include_once(__DIR__ . "/bootstrap.php");
// retrieve the token from the URL
$token = $_GET['token'];

// retrieve the user from the database using the token
$conn = Db::getInstance();
$statement = $conn->prepare("select * from users where token = :token");
$statement->bindValue(":token", $token);
$statement->execute();
$user = $statement->fetch();

// if the token is valid, activate the user's account
if ($user) {
    $statement = $conn->prepare("update users set is_verified = 1 where id = :id");
    $statement->bindValue(":id", $user['id']);
    $statement->execute();
    echo "Your account has been activated!";
} else {
    echo "Invalid activation link.";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Verify account</title>
</head>

<body>

</body>

</html>