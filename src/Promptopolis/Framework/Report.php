<?php

namespace Promptopolis\Framework;

class Report
{
    public function flagPrompt($id)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE prompts SET is_reported = 1 WHERE id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
    }

    public function flagUser($id)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET is_reported = 1 WHERE id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
    }

    public function unflagUser($id){
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET is_reported = 0 WHERE id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
    }

    public static function allFlaggedUsers()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM users WHERE is_reported = 1");
        $statement->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }
}