<?php

namespace Promptopolis\Framework;

class prompt
{
    private int $id;
    private string $title;
    private string $description;
    private string $price;
    private string $model;
    private string $category;
    private array $tags;
    private string $mainImage;
    private string $overviewImage;
    private string $image3;
    private string $image4;
    private int $user_id;
    private int $is_approved;

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        if ($id != null && !empty($id) && is_numeric($id)) {
            $this->id = $id;

            return $this;
        }
    }

    public static function getAllowedModels($filterModel)
    {
        // if one of the models is not dall-e or midjourney, return all
        $models = ['Midjourney', 'Dall-E'];

        if (!in_array($filterModel, $models)) {
            return 'all';
        } else {
            return $filterModel;
        }
    }

    public static function getAllowedOrder($filterOrder)
    {
        // if one of the models is not dall-e or midjourney, return all
        $orders = ['new', 'old', 'high', 'low'];

        if (!in_array($filterOrder, $orders)) {
            return 'all';
        } else {
            return $filterOrder;
        }
    }

    public static function getAllowedStatus($filterApprove)
    {
        // if one of the models is not dall-e or midjourney, return all
        $approved = ['not_approved'];

        if (!in_array($filterApprove, $approved)) {
            return 'all';
        } else {
            return $filterApprove;
        }
    }

    public static function getAllowedCategory($filterCategory)
    {
        // if one of the models is not dall-e or midjourney, return all
        $categories = ["Nature", "Logo", "Civilisation", "Line_art"];

        if (!in_array($filterCategory, $categories)) {
            return 'all';
        } else {
            return $filterCategory;
        }
    }

    public static function filter($filterApprove, $filterOrder, $filterModel, $filterCategory, $searchTerm, $limit, $offset)
    {
        try {
            // getallowedmodels functie
            $model = self::getAllowedModels($filterModel);
            $order = self::getAllowedOrder($filterOrder);
            $approve = self::getAllowedStatus($filterApprove);
            $category = self::getAllowedCategory($filterCategory);
            // of get all of custom
            // sql injectie voor deze filter

            $conn = Db::getInstance();
            $sql = "SELECT p.* FROM prompts p
            INNER JOIN prompt_tags pt ON p.id = pt.prompt_id
            INNER JOIN tags t ON pt.tag_id = t.id WHERE 1=1 " . ($model != 'all' ? "AND model = :model " : "") . ($category != 'all' ? "AND category = :category " : "") . ($approve == 'all' ? "AND is_approved = 1 " : ($approve == 'not_approved' ? "AND is_approved = 0 " : "")) . ($order == 'new' ? "ORDER BY tstamp DESC " : ($order == 'old' ? "ORDER BY tstamp ASC " : "")) . ($order == 'high' ? "ORDER BY price DESC " : ($order == 'low' ? "ORDER BY price ASC " : ""));
            if ($searchTerm != '') {
                $sql .= " AND LOWER (p.title) LIKE LOWER (:searchTerm) OR LOWER (t.name) LIKE LOWER (:searchTerm)";
            }
            $sql .= " LIMIT $limit OFFSET $offset";


            $statement = $conn->prepare($sql);
            if ($model != 'all') {
                $statement->bindValue(":model", $model);
            }
            if ($category != 'all') {
                $statement->bindValue(":category", $category);
            }
            if ($searchTerm != '') {
                $statement->bindValue(":searchTerm", '%' . $searchTerm . '%');
            }
            $statement->execute();
            $prompts = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $prompts;
        } catch (\PDOException $e) {
            error_log("PDO error: " . $e->getMessage());
            return [];
        }
    }



    public static function getAll($filterApprove, $filterOrder, $filterModel, $filterCategory, $searchTerm)
    {
        try {
            // getallowedmodels functie
            $model = self::getAllowedModels($filterModel);
            $order = self::getAllowedOrder($filterOrder);
            $approve = self::getAllowedStatus($filterApprove);
            $category = self::getAllowedCategory($filterCategory);
            // of get all of custom
            // sql injectie voor deze filter

            $conn = Db::getInstance();
            $sql = "SELECT * FROM prompts WHERE 1=1 " . ($model != 'all' ? "AND model = :model " : "") . ($category != 'all' ? "AND category = :category " : "") . ($approve == 'all' ? "AND is_approved = 1 " : ($approve == 'not_approved' ? "AND is_approved = 0 " : "")) . ($order == 'new' ? "ORDER BY tstamp DESC " : ($order == 'old' ? "ORDER BY tstamp ASC " : "")) . ($order == 'high' ? "ORDER BY price DESC " : ($order == 'low' ? "ORDER BY price ASC " : ""));
            if ($searchTerm != '') {
                $sql .= " AND LOWER (title) LIKE LOWER (:searchTerm)";
            }
           
            $statement = $conn->prepare($sql);
            if ($model != 'all') {
                $statement->bindValue(":model", $model);
            }
            if ($category != 'all') {
                $statement->bindValue(":category", $category);
            }
            if ($searchTerm != '') {
                $statement->bindValue(":searchTerm", '%' . $searchTerm . '%');
            }
            $statement->execute();
            $prompts = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $prompts;
        } catch (\PDOException $e) {
            error_log("PDO error: " . $e->getMessage());
            return [];
        }
    }


    public static function get15ToApprovePrompts()
    {
        try {
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM prompts WHERE is_approved = 0 ORDER BY tstamp DESC LIMIT 15");
            $statement->execute();
            $prompts = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $prompts;
        } catch (\PDOException $e) {
            error_log("PDO error: " . $e->getMessage());
            return [];
        }
    }

    public function getPromptDetails()
    {
        $conn = Db::getInstance();
        // Get the prompt details and the tag names
        $statement = $conn->prepare("SELECT p.*, GROUP_CONCAT(t.name SEPARATOR ', ') as tag_names FROM prompts p JOIN prompt_tags pt ON p.id = pt.prompt_id JOIN tags t ON pt.tag_id = t.id WHERE p.id = :id");
        $statement->bindValue(":id", $this->id);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        // Convert the string of tag names to an array
        $result['tag_names'] = explode(', ', $result['tag_names']);
        return $result;
    }

    public static function getPromptsByUser($user_id)
    {
        $conn = Db::getInstance();
        $sql = "SELECT * FROM prompts WHERE user_id = :user_id";

        if ($_SESSION['id']['id'] != $user_id) {
            $sql .= " AND is_approved = 1";
        }

        $sql .= " ORDER BY tstamp DESC";
        $statement = $conn->prepare($sql);
        $statement->bindValue(":user_id", $user_id);
        $statement->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function savePrompt()
    {
        $conn = Db::getInstance();

        // Insert or retrieve tag IDs for each tag
        $tags = $this->tags;
        $tagIds = array();
        foreach ($tags as $tag) {
            $statement = $conn->prepare("SELECT id FROM tags WHERE name = :name");
            $statement->bindValue(":name", $tag);
            $statement->execute();
            $row = $statement->fetch(\PDO::FETCH_ASSOC);
            if ($row) {
                // Tag already exists, use its ID
                $tagIds[] = $row['id'];
            } else {
                // Tag doesn't exist, insert it and get its new ID
                $statement = $conn->prepare("INSERT INTO tags (name) VALUES (:name)");
                $statement->bindValue(":name", $tag);
                $statement->execute();
                $tagIds[] = $conn->lastInsertId();
            }
        }

        // Insert prompt into prompts table
        $statement = $conn->prepare("INSERT INTO prompts (title, description, price, model, category, tstamp, user_id, cover_url, image_url2, image_url3, image_url4, is_approved) VALUES (:title, :description, :price, :model, :category, :tstamp, :user_id, :cover_url, :image_url2, :image_url3, :image_url4, :is_approved)");
        $statement->bindValue(":title", $this->title);
        $statement->bindValue(":description", $this->description);
        $statement->bindValue(":price", $this->price);
        $statement->bindValue(":model", $this->model);
        $statement->bindValue(":category", $this->category);
        $statement->bindValue(":tstamp", date('Y-m-d'));
        $statement->bindValue(":user_id", $this->user_id);
        $statement->bindValue(":cover_url", $this->mainImage);
        $statement->bindValue(":image_url2", $this->overviewImage);
        $statement->bindValue(":image_url3", $this->image3);
        $statement->bindValue(":image_url4", $this->image4);
        $statement->bindValue(":is_approved", $this->is_approved);
        $statement->execute();

        // Get ID of the prompt that was just inserted
        $promptId = $conn->lastInsertId();

        // Insert prompt ID and tag ID pairs into prompt_tags table for each tag
        foreach ($tagIds as $tagId) {
            $statement = $conn->prepare("INSERT INTO prompt_tags (prompt_id, tag_id) VALUES (:prompt_id, :tag_id)");
            $statement->bindValue(":prompt_id", $promptId);
            $statement->bindValue(":tag_id", $tagId);
            $statement->execute();
        }
    }

    /**
     * Get the value of title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */
    public function setTitle($title)
    {
        if (!empty($title)) {
            $this->title = $title;
            return $this;
        } else {
            throw new \exception("Title cannot be empty");
        }
    }

    /**
     * Get the value of description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */
    public function setDescription($description)
    {
        if (!empty($description)) {
            $this->description = $description;
            return $this;
        } else {
            throw new \exception("Description cannot be empty");
        }
    }

    /**
     * Get the value of price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @return  self
     */
    public function setPrice($price)
    {
        if (!empty($price) && is_numeric($price)) {
            $this->price = $price;
            return $this;
        } else {
            throw new \exception("Price must be a number");
        }
    }

    /**
     * Get the value of model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set the value of model
     *
     * @return  self
     */
    public function setModel($model)
    {
        if (!empty($model)) {
            $this->model = $model;
            return $this;
        } else {
            throw new \exception("Model cannot be empty");
        }
    }



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
     * Get the value of user_id
     */
    public function getUser_id()
    {
        return $this->user_id;
    }

    /**
     * Set the value of user_id
     *
     * @return  self
     */
    public function setUser_id($user_id)
    {
        if (!empty($user_id) && is_numeric($user_id)) {
            $this->user_id = $user_id;
            return $this;
        } else {
            throw new \exception("User id must be a number");
        }
    }



    /**
     * Get the value of tags
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set the value of tags
     *
     * @return  self
     */
    public function setTags($tags)
    {
        if (!empty($tags)) {
            $this->tags = $tags;
            return $this;
        } else {
            throw new \exception("Tags cannot be empty");
        }
    }

    public static function getNewPrompts()
    {
        try {
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM prompts WHERE is_approved = 1 ORDER BY tstamp DESC LIMIT 15 ");
            $statement->execute();
            $prompts = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $prompts;
        } catch (\PDOException $e) {
            error_log("PDO error: " . $e->getMessage());
            return [];
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

    /**
     * Get the value of is_approved
     */
    public function getIs_approved()
    {
        return $this->is_approved;
    }

    /**
     * Set the value of is_approved
     *
     * @return  self
     */
    public function setIs_approved($is_approved)
    {
        $this->is_approved = $is_approved;

        return $this;
    }

    /**
     * Get the value of category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the value of category
     *
     * @return  self
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }
}