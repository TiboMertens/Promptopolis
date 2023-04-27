<?php
class prompt
{
    private int $id;
    protected string $title;
    protected string $description;
    protected string $price;
    protected string $model;
    protected string $category;
    protected array $tags;
    protected string $mainImage;
    protected string $overviewImage;
    protected string $image3;
    protected string $image4;
    protected int $user_id;
    protected int $is_approved;

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

    public static function getAllowedDate($filterDate)
    {
        // if one of the models is not dall-e or midjourney, return all
        $dates = ['new', 'old'];

        if (!in_array($filterDate, $dates)) {
            return 'all';
        } else {
            return $filterDate;
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

    public static function getAllowedPrice($filterPrice)
    {
        // if one of the models is not dall-e or midjourney, return all
        $prices = ['low', 'high'];

        if (!in_array($filterPrice, $prices)) {
            return 'all';
        } else {
            return $filterPrice;
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

    public static function filter($filterApprove, $filterDate, $filterPrice, $filterModel, $filterCategory, $limit, $offset)
    {
        try {
            // getallowedmodels functie
            $model = self::getAllowedModels($filterModel);
            $date = self::getAllowedDate($filterDate);
            $approve = self::getAllowedStatus($filterApprove);
            $price = self::getAllowedPrice($filterPrice);
            $category = self::getAllowedCategory($filterCategory);
            // of get all of custom
            // sql injectie voor deze filter

            $conn = Db::getInstance();
            $sql = "SELECT * FROM prompts WHERE 1=1 " . ($model != 'all' ? "AND model = :model " : "") . ($category != 'all' ? "AND category = :category " : "") . ($approve == 'all' ? "AND is_approved = 1 " : ($approve == 'not_approved' ? "AND is_approved = 0 " : "")) . ($date == 'new' ? "ORDER BY tstamp DESC " : ($date == 'old' ? "ORDER BY tstamp ASC " : "")) . ($price == 'high' ? "ORDER BY price DESC " : ($price == 'low' ? "ORDER BY price ASC " : ""));
            if ($filterDate == "new" && $filterPrice == "low") {
                // Select the newest prompts with the lowest price
                $sql = "SELECT * FROM (" . $sql . ") AS new_prompts_low_price ORDER BY price ASC, tstamp DESC";
            } else if ($filterDate == "old" && $filterPrice == "high") {
                // Select the oldest prompts with the highest price
                $sql = "SELECT * FROM (" . $sql . ") AS old_prompts_high_price ORDER BY price DESC, tstamp ASC";
            } else if ($filterDate == "new" && $filterPrice == "high") {
                // Select the newest prompts with the highest price
                $sql = "SELECT * FROM (" . $sql . ") AS new_prompts_high_price ORDER BY price DESC, tstamp DESC";
            } else if ($filterDate == "old" && $filterPrice == "low") {
                // Select the oldest prompts with the lowest price
                $sql = "SELECT * FROM (" . $sql . ") AS old_prompts_low_price ORDER BY price ASC, tstamp ASC";
            }
            $sql .= " LIMIT $limit OFFSET $offset";

            $statement = $conn->prepare($sql);
            if ($model != 'all') {
                $statement->bindValue(":model", $model);
            }
            if ($category != 'all') {
                $statement->bindValue(":category", $category);
            }
            $statement->execute();
            $prompts = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $prompts;
        } catch (PDOException $e) {
            error_log("PDO error: " . $e->getMessage());
            return [];
        }
    }



    public static function getAll($filterApprove, $filterDate, $filterPrice, $filterModel, $filterCategory)
    {
        try {
            // getallowedmodels functie
            $model = self::getAllowedModels($filterModel);
            $date = self::getAllowedDate($filterDate);
            $approve = self::getAllowedStatus($filterApprove);
            $price = self::getAllowedPrice($filterPrice);
            $category = self::getAllowedCategory($filterCategory);
            // of get all of custom
            // sql injectie voor deze filter

            $conn = Db::getInstance();
            $sql = "SELECT * FROM prompts WHERE 1=1 " . ($model != 'all' ? "AND model = :model " : "") . ($category != 'all' ? "AND category = :category " : "") . ($approve == 'all' ? "AND is_approved = 1 " : ($approve == 'not_approved' ? "AND is_approved = 0 " : "")) . ($date == 'new' ? "ORDER BY tstamp DESC " : ($date == 'old' ? "ORDER BY tstamp ASC " : "")) . ($price == 'high' ? "ORDER BY price DESC " : ($price == 'low' ? "ORDER BY price ASC " : ""));
            if ($filterDate == "new" && $filterPrice == "low") {
                // Select the newest prompts with the lowest price
                $sql = "SELECT * FROM (" . $sql . ") AS new_prompts_low_price ORDER BY price ASC, tstamp DESC";
            } else if ($filterDate == "old" && $filterPrice == "high") {
                // Select the oldest prompts with the highest price
                $sql = "SELECT * FROM (" . $sql . ") AS old_prompts_high_price ORDER BY price DESC, tstamp ASC";
            } else if ($filterDate == "new" && $filterPrice == "high") {
                // Select the newest prompts with the highest price
                $sql = "SELECT * FROM (" . $sql . ") AS new_prompts_high_price ORDER BY price DESC, tstamp DESC";
            } else if ($filterDate == "old" && $filterPrice == "low") {
                // Select the oldest prompts with the lowest price
                $sql = "SELECT * FROM (" . $sql . ") AS old_prompts_low_price ORDER BY price ASC, tstamp ASC";
            }

            $statement = $conn->prepare($sql);
            if ($model != 'all') {
                $statement->bindValue(":model", $model);
            }
            if ($category != 'all') {
                $statement->bindValue(":category", $category);
            }
            $statement->execute();
            $prompts = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $prompts;
        } catch (PDOException $e) {
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
            $prompts = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $prompts;
        } catch (PDOException $e) {
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
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        // Convert the string of tag names to an array
        $result['tag_names'] = explode(', ', $result['tag_names']);
        return $result;
    }

    public static function getPromptsByUser($user_id)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM prompts WHERE user_id = :user_id AND is_approved = 1");
        $statement->bindValue(":user_id", $user_id);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
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
            $row = $statement->fetch(PDO::FETCH_ASSOC);
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
            throw new Exception("Title cannot be empty");
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
            throw new Exception("Description cannot be empty");
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
            throw new Exception("Price must be a number");
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
            throw new Exception("Model cannot be empty");
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
                        $this->mainImage = $target_file;
                        // $user->setProfile_picture_url($target_file);
                    } else {
                        throw new Exception("Sorry, there was an error uploading your file.");
                    }
                }
            } catch (Exception $e) {
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
                    $this->overviewImage = $target_file_overview;
                } else {
                    throw new Exception("Sorry, there was an error uploading your file.");
                }
            } catch (Exception $e) {
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
            throw new Exception("User id must be a number");
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
            throw new Exception("Tags cannot be empty");
        }
    }

    public static function getNewPrompts()
    {
        try {
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM prompts WHERE is_approved = 1 ORDER BY tstamp DESC LIMIT 15 ");
            $statement->execute();
            $prompts = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $prompts;
        } catch (PDOException $e) {
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
                    throw new Exception("File is not an image.");
                }

                if ($_FILES["image3"]["size"] > 1000000) {
                    throw new Exception("File is too large.");
                }

                if (
                    $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                    && $imageFileType != "gif"
                ) {
                    throw new Exception("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
                }

                if (move_uploaded_file($_FILES["image3"]["tmp_name"], $target_file_overview)) {
                    $this->image3 = $target_file_overview;
                } else {
                    throw new Exception("Sorry, there was an error uploading your file.");
                }
            } catch (Exception $e) {
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
                    throw new Exception("File is not an image.");
                }

                if ($_FILES["image4"]["size"] > 1000000) {
                    throw new Exception("File is too large.");
                }

                if (
                    $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                    && $imageFileType != "gif"
                ) {
                    throw new Exception("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
                }

                if (move_uploaded_file($_FILES["image4"]["tmp_name"], $target_file_overview)) {
                    $this->image4 = $target_file_overview;
                } else {
                    throw new Exception("Sorry, there was an error uploading your file.");
                }
            } catch (Exception $e) {
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
