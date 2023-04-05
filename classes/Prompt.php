<?php
class prompt
{
    public function getToApproveImages($offset, $limit)
    {
        try {
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT image_url, user_id FROM prompts WHERE is_approved = 0 LIMIT :offset, :limit");
            $statement->bindValue(":offset", $offset, PDO::PARAM_INT);
            $statement->bindValue(":limit", $limit, PDO::PARAM_INT);
            $statement->execute();
            $images = $statement->fetchAll(PDO::FETCH_ASSOC);
            return $images;
        } catch (PDOException $e) {
            error_log("PDO error: " . $e->getMessage());
            return [];
        }
    }
}
