<?php
require 'vendor/autoload.php';

use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Configuration\Configuration;

$config = parse_ini_file(__DIR__ . "\src\Promptopolis\Framework\config\config.ini");

$name = $config['CLOUDINARY_NAME'];
$apiKey = $config['CLOUDINARY_API_KEY'];
$apiSecret = $config['CLOUDINARY_API_SECRET'];

$config = Configuration::instance();
$config->cloud->cloudName = $name;
$config->cloud->apiKey = $apiKey;
$config->cloud->apiSecret = $apiSecret;
$config->url->secure = true;

if (isset($_POST['submit'])) {
  // Check if a file was uploaded
  if (isset($_FILES['image'])) {
    $tmpFilePath = $_FILES['image']['tmp_name'];
    $uploadResult = (new UploadApi())->upload($tmpFilePath);

    $url = $uploadResult['secure_url'];
    // Check if the upload was successful
    // $imageUrl = $uploadResult['secure_url'];
  }
} else {
  echo 'No file selected';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <form action="" method="post" enctype="multipart/form-data">
    <input type="file" name="image">
    <input type="submit" name="submit" value="Upload">
  </form>
    <img src="<?php echo $url; ?>" alt="Uploaded Image">
</body>

</html>