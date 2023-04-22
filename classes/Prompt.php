<?php
class prompt
{
    private int $id;
    //prompt properties
    protected string $title;
    protected string $description;
    protected string $price;
    protected string $model;
    protected array $tags;
    protected string $mainImage;
    protected string $overviewImage;
    protected int $user_id;

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

    public static function getAllToApprovePrompts($limit, $offset)
    {
        try {
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM prompts WHERE is_approved = 0 LIMIT :limit OFFSET :offset");
            $statement->bindValue(":limit", $limit, PDO::PARAM_INT);
            $statement->bindValue(":offset", $offset, PDO::PARAM_INT);
            $statement->execute();
            $prompts = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $prompts;
        } catch (PDOException $e) {
            error_log("PDO error: " . $e->getMessage());
            return [];
        }
    }

    public static function countAllToApprovePrompts(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM prompts WHERE is_approved = 0");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function get15ToApprovePrompts()
    {
        try {
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM prompts WHERE is_approved = 0 LIMIT 15");
            $statement->execute();
            $prompts = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $prompts;
        } catch (PDOException $e) {
            error_log("PDO error: " . $e->getMessage());
            return [];
        }
    }

    public static function getAllPrompts($limit, $offset)
    {
        try {
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM prompts LIMIT :limit OFFSET :offset");
            $statement->bindValue(":limit", $limit, PDO::PARAM_INT);
            $statement->bindValue(":offset", $offset, PDO::PARAM_INT);
            $statement->execute();
            $prompts = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $prompts;
        } catch (PDOException $e) {
            error_log("PDO error: " . $e->getMessage());
            return [];
        }
    }

    public static function countAllPrompts(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM prompts");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getPromptDetails()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM prompts WHERE id = :id");
        $statement->bindValue(":id", $this->id);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function getPromptsByUser($user_id)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM prompts WHERE user_id = :user_id");
        $statement->bindValue(":user_id", $user_id);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    
    public function savePrompt(){
        //insert data into database

        
        $conn = Db::getInstance();
       
            // Insert tags into tags table
            
            $tags= $this->tags;
            $tagIds = array();
            $conn = Db::getInstance();
            foreach ($tags as $tag) {
                $statement = $conn->prepare("INSERT INTO tags (name) VALUES (:name)");
                $statement->bindValue(":name", $tag);
                $statement->execute();
                $tagIds[] = $conn->lastInsertId();
            }
        
            $statement = $conn->prepare("INSERT INTO prompts (title, description, price, model, tstamp, user_id, cover_url, image_url2) VALUES (:title, :description, :price, :model, :tstamp, :user_id, :cover_url, :image_url2)");
            $statement->bindValue(":title", $this->title);
            $statement->bindValue(":description", $this->description);
            $statement->bindValue(":price", $this->price);
            $statement->bindValue(":model", $this->model);
            $statement->bindValue(":tstamp", date('Y-m-d'));
            $statement->bindValue(":user_id", $this->user_id);            
            $statement->bindValue(":cover_url", $this->mainImage);
            $statement->bindValue(":image_url2", $this->overviewImage);
    
             

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
        if(!empty($title)){
            $this->title = $title;
            return $this;
        }else{
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
        if(!empty($description)){
            $this->description = $description;
            return $this;
        }else{
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
        if(!empty($price) && is_numeric($price)){
            $this->price = $price;
            return $this;
        }else{
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
        if(!empty($model)){
            $this->model = $model;
            return $this;
        }else{
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
        if(!empty($user_id) && is_numeric($user_id)){
            $this->user_id = $user_id;
            return $this;
        }else{
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
        if(!empty($tags)){
            $this->tags = $tags;
            return $this;
        }else{
            throw new Exception("Tags cannot be empty");
        }
    }
}
