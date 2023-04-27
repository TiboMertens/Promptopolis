<?php
namespace Promptopolis\Framework;

class Moderator extends User
{
    public function approve($id)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE prompts SET is_approved = 1 WHERE id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
    }
}
