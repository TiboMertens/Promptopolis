<?php
class prompt
{
    private int $id;

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

    public static function filter($filterApprove, $filterDate, $filterPrice, $filterModel, $limit, $offset)
    {
        try {
            $conn = Db::getInstance();
            $sql = "SELECT * FROM prompts WHERE 1=1";
    
            switch ($filterApprove) {
                case "all":
                    $sql .= " AND is_approved = 1";
                    break;
                case "not_approved":
                    $sql .= " AND is_approved = 0";
                    break;
            }
            
            switch ($filterModel) {
                case "Midjourney":
                    $sql .= " AND model = 'Midjourney'";
                    break;
                case "Dall-E":
                    $sql .= " AND model = 'Dall-E'";
                    break;
            }
    
            switch ($filterDate) {
                case "new":
                    $sql .= " ORDER BY tstamp DESC";
                    break;
                case "old":
                    $sql .= " ORDER BY tstamp ASC";
                    break;
            }
    
            switch ($filterPrice) {
                case "high":
                    $sql .= " ORDER BY price DESC";
                    break;
                case "low":
                    $sql .= " ORDER BY price ASC";
                    break;
            }
    
            if ($filterDate == "new" && $filterPrice == "low") {
                // Select the newest prompts with the lowest price
                $sql = "SELECT * FROM (" . $sql . ") AS new_prompts_low_price ORDER BY price ASC, tstamp DESC";
            } else if ($filterDate == "old" && $filterPrice == "high") {
                // Select the oldest prompts with the highest price
                $sql = "SELECT * FROM (" . $sql . ") AS old_prompts_high_price ORDER BY price DESC, tstamp ASC";
            } else if($filterDate == "new" && $filterPrice == "high") {
                // Select the newest prompts with the highest price
                $sql = "SELECT * FROM (" . $sql . ") AS new_prompts_high_price ORDER BY price DESC, tstamp DESC";
            } else if($filterDate == "old" && $filterPrice == "low") {
                // Select the oldest prompts with the lowest price
                $sql = "SELECT * FROM (" . $sql . ") AS old_prompts_low_price ORDER BY price ASC, tstamp ASC";
            }
    
            $sql .= " LIMIT $limit OFFSET $offset";
    
            $statement = $conn->prepare($sql);
            $statement->execute();
            $prompts = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $prompts;
        } catch (PDOException $e) {
            error_log("PDO error: " . $e->getMessage());
            return [];
        }
    }
    


    public static function getAll($filterApprove, $filterDate, $filterPrice, $filterModel)
    {
        try {
            $conn = Db::getInstance();
            $sql = "SELECT * FROM prompts WHERE 1=1";
            switch ($filterApprove) {
                case "approved":
                    $sql .= " AND is_approved = 1";
                    break;
                case "not_approved":
                    $sql .= " AND is_approved = 0";
                    break;
            }
            switch ($filterModel) {
                case "Midjourney":
                    $sql .= " AND model = 'Midjourney'";
                    break;
                case "Dall-E":
                    $sql .= " AND model = 'Dall-E'";
                    break;
            }
            // Add filtering for date
            switch ($filterDate) {
                case "new":
                    $sql .= " ORDER BY tstamp DESC";
                    break;
                case "old":
                    $sql .= " ORDER BY tstamp ASC";
                    break;
            }

            // Add filtering for price
            switch ($filterPrice) {
                case "high":
                    $sql .= " ORDER BY price DESC";
                    break;
                case "low":
                    $sql .= " ORDER BY price ASC";
                    break;
            }
            $statement = $conn->prepare($sql);
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
            $statement = $conn->prepare("SELECT * FROM prompts WHERE is_approved = 0 LIMIT 15");
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
    public static function getAllNewPrompts($limit, $offset)
    {
        try {
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM prompts WHERE is_approved = 1 ORDER BY tstamp DESC LIMIT :limit OFFSET :offset");
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
    public static function countAllNewPrompts(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM prompts WHERE is_approved = 1 ORDER BY tstamp DESC");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}
