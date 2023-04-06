<?php
class prompt
{
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
}
