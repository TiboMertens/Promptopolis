<?php
class Moderator extends User {
    public function approve(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE prompts SET is_approved = 1 WHERE user_id = :user_id");
        $statement->bindValue(":user_id", $this->id);
        $result = $statement->execute();
        return $result;
    }
}