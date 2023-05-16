<?php

namespace Promptopolis\Framework;

use Cloudinary\Api\Upload\UploadApi;

class Upload
{
    public static function uploadImage($image, $imageFileType)
    {
        self::checkImage($image);
        self::checkSize($image);
        self::checkImageType($imageFileType);
        $url = self::upload($image);
        return $url;
    }

    public static function checkImage($image)
    {
        $check = getimagesize($_FILES[$image]["tmp_name"]);
        if ($check === false) {
            throw new \exception("File is not an image.");
        }
    }

    public static function checkSize($image)
    {
        if ($_FILES[$image]["size"] > 1000000) {
            throw new \exception("File is too large.");
        }
    }

    public static function checkImageType($imageFileType)
    {
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            throw new \exception("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
        }
    }

    public static function upload($image)
    {
        $tmpFilePath = $_FILES[$image]['tmp_name'];

        if ($image != "mainImage") {
            $uploadResult = (new UploadApi())->upload($tmpFilePath, [
                'transformation' => [
                    'width' => 500,
                    'height' => 300,
                    'crop' => 'fit'
                ]
            ]);
        } else {
            $uploadResult = (new UploadApi())->upload($tmpFilePath, [
                'transformation' => [
                    'width' => 700,
                    'height' => 500,
                    'crop' => 'fit'
                ]
            ]);
        }


        $url = $uploadResult['secure_url'];
        return $url;
    }
}
