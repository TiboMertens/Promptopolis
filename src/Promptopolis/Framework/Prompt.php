<?php

namespace Promptopolis\Framework;

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
}