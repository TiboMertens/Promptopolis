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

    public static function getAllToApprovePrompts($offset, $limit)
    {
        try {
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT cover_url, id FROM prompts WHERE is_approved = 0 LIMIT :offset, :limit");
            $statement->bindValue(":offset", $offset, PDO::PARAM_INT);
            $statement->bindValue(":limit", $limit, PDO::PARAM_INT);
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
            $statement = $conn->prepare("SELECT cover_url, id FROM prompts WHERE is_approved = 0 LIMIT 15");
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

    public function approvePrompt()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE prompts SET is_approved = 1 WHERE id = :id");
        $statement->bindValue(":id", $this->id);
        $statement->execute();
    }
}
