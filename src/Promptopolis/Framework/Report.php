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
}