<?php

namespace Promptopolis\Framework;

class Upload
{
    private $mainImage;
    private $overviewImage;
    private $image3;
    private $image4;

    /**
     * Get the value of mainImage
     */
    public function getMainImage()
    {
        return $this->mainImage;
    }

    /**
     * Set the value of mainImage
     *
     * @return  self
     */
    public function setMainImage($imageFileType, $target_file)
    {
        if (!empty($_FILES["mainImage"]["name"])) {
            try {
                $check = getimagesize($_FILES["mainImage"]["tmp_name"]);
                if ($check !== false) {

                    $uploadOk = 1;
                } else {
                    throw new \exception("File is not an image.");
                    $uploadOk = 0;
                }
                // Check file size, if file is larger than 1MB give error

                if ($_FILES["mainImage"]["size"] < 1000000) {

                    $uploadOk = 1;
                } else {
                    throw new \exception("File is too large.");
                }

                // Allow certain file formats
                if (
                    $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                    && $imageFileType != "gif"
                ) {
                    throw new \exception("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
                    $uploadOk = 0;
                }

                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    throw new \exception("Sorry, your file was not uploaded.");
                    // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["mainImage"]["tmp_name"], $target_file)) {

                        //var_dump the file that was uploaded
                        $this->mainImage = $target_file;
                        // $user->setProfile_picture_url($target_file);
                    } else {
                        throw new \exception("Sorry, there was an error uploading your file.");
                    }
                }
            } catch (\exception $e) {
                $mainImageError = $e->getMessage();
            }
        }
    }

    /**
     * Get the value of overviewImage
     */
    public function getOverviewImage()
    {
        return $this->overviewImage;
    }

    /**
     * Set the value of overviewImage
     *
     * @return  self
     */
    public function setOverviewImage($imageFileType, $target_file_overview)
    {
        // Validate overview image file
        if (!empty($_FILES["overviewImage"]["name"])) {
            try {
                $check = getimagesize($_FILES["overviewImage"]["tmp_name"]);
                if ($check === false) {
                    throw new \exception("File is not an image.");
                }

                if ($_FILES["overviewImage"]["size"] > 1000000) {
                    throw new \exception("File is too large.");
                }

                if (
                    $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                    && $imageFileType != "gif"
                ) {
                    throw new \exception("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
                }

                if (move_uploaded_file($_FILES["overviewImage"]["tmp_name"], $target_file_overview)) {
                    $this->overviewImage = $target_file_overview;
                } else {
                    throw new \exception("Sorry, there was an error uploading your file.");
                }
            } catch (\exception $e) {
                $overviewImageError = $e->getMessage();
            }
        }
    }

    /**
     * Get the value of image3
     */
    public function getImage3()
    {
        return $this->image3;
    }

    /**
     * Set the value of image3
     *
     * @return  self
     */
    public function setImage3($imageFileType, $target_file_overview)
    {
        // Validate overview image file
        if (!empty($_FILES["image3"]["name"])) {
            try {
                $check = getimagesize($_FILES["image3"]["tmp_name"]);
                if ($check === false) {
                    throw new \exception("File is not an image.");
                }

                if ($_FILES["image3"]["size"] > 1000000) {
                    throw new \exception("File is too large.");
                }

                if (
                    $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                    && $imageFileType != "gif"
                ) {
                    throw new \exception("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
                }

                if (move_uploaded_file($_FILES["image3"]["tmp_name"], $target_file_overview)) {
                    $this->image3 = $target_file_overview;
                } else {
                    throw new \exception("Sorry, there was an error uploading your file.");
                }
            } catch (\exception $e) {
                $overviewImageError = $e->getMessage();
            }
        }
    }

    /**
     * Get the value of image4
     */
    public function getImage4()
    {
        return $this->image4;
    }

    /**
     * Set the value of image4
     *
     * @return  self
     */
    public function setImage4($imageFileType, $target_file_overview)
    {
        // Validate overview image file
        if (!empty($_FILES["image4"]["name"])) {
            try {
                $check = getimagesize($_FILES["image4"]["tmp_name"]);
                if ($check === false) {
                    throw new \exception("File is not an image.");
                }

                if ($_FILES["image4"]["size"] > 1000000) {
                    throw new \exception("File is too large.");
                }

                if (
                    $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                    && $imageFileType != "gif"
                ) {
                    throw new \exception("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
                }

                if (move_uploaded_file($_FILES["image4"]["tmp_name"], $target_file_overview)) {
                    $this->image4 = $target_file_overview;
                } else {
                    throw new \exception("Sorry, there was an error uploading your file.");
                }
            } catch (\exception $e) {
                $overviewImageError = $e->getMessage();
            }
        }
    }
}