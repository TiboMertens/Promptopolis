<?php

namespace Promptopolis\Framework;

class Upload
{
    public static function uploadImage($image, $imageFileType, $target_file){
            self::checkImage($image);
            self::checkSize($image);
            self::checkImageType($imageFileType);
            self::move($image, $target_file);
    }

    public static function checkImage($image){
        $check = getimagesize($_FILES[$image]["tmp_name"]);
        if ($check === false) {
            throw new \exception("File is not an image.");
        }
    }

    public static function checkSize($image){
        if ($_FILES[$image]["size"] > 1000000) {
            throw new \exception("File is too large.");
        }
    }

    public static function checkImageType($imageFileType){
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            throw new \exception("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
        }
    }

    public static function move($image, $target_file){
        if (move_uploaded_file($_FILES[$image]["tmp_name"], $target_file)) {
            //is uploaded
        } else {
            throw new \exception("Sorry, there was an error uploading your file.");
        }
    }
}