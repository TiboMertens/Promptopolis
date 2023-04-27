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

    public function updateVotes($id){
        $conn = Db::getInstance();
        //if the current user has already voted for the user, he cannot vote again
        $statement = $conn->prepare("SELECT * FROM user_vote WHERE voted_for = :voted_for AND voted_by = :voted_by");
        $statement->bindValue(":voted_for", $id);
        $statement->bindValue(":voted_by", $_SESSION['id']['id']);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        //if the user has not voted for the user yet, insert the vote into the database
        if(!$result){
            $statement = $conn->prepare("INSERT INTO user_vote (voted_for, voted_by) VALUES (:voted_for, :voted_by)");
            $statement->bindValue(":voted_for", $id);
            $statement->bindValue(":voted_by", $_SESSION['id']['id']);
            $statement->execute();
        }
    }
}
