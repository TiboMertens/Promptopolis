<?php

namespace Promptopolis\Framework;

class like
{

    public function getLikes($id)
    {
        $conn = Db::getInstance();
        //get all the rows where the user has been liked for
        $statement = $conn->prepare("SELECT * FROM user_like WHERE liked_for = :prompt_id");
        $statement->bindValue(":prompt_id", $id);
        $statement->execute();
        //count the amount of rows and return it
        $count = $statement->rowCount();
        return $count;
    }
    public function updateLikes($id, $loggedInUser_id)
    {
        $conn = Db::getInstance();
        //if the current user has already voted for the user, he cannot vote again
        $statement = $conn->prepare("SELECT * FROM user_like WHERE liked_for = :liked_for AND liked_by = :liked_by");
        $statement->bindValue(":liked_for", $id);
        $statement->bindValue(":liked_by", $loggedInUser_id);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        //if the user has not voted for the user yet, insert the vote into the database
        if (!$result) {
            $statement = $conn->prepare("INSERT INTO user_like (liked_for, liked_by) VALUES (:liked_for, :liked_by)");
            $statement->bindValue(":liked_for", $id);
            $statement->bindValue(":liked_by", $loggedInUser_id);
            $statement->execute();
            return true;
        } else {
            //if the user has already liked for the user, delete the like from the database
            $statement = $conn->prepare("DELETE FROM user_like WHERE liked_for = :liked_for AND liked_by = :liked_by");
            $statement->bindValue(":liked_for", $id);
            $statement->bindValue(":liked_by", $loggedInUser_id);
            $statement->execute();
            return false;
        }
    }

    public function checkLiked($id, $loggedInUser_id)
    {
        //check if the user has already liked for the prompt
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM user_like WHERE liked_for = :liked_for AND liked_by = :liked_by");
        $statement->bindValue(":liked_for", $id);
        $statement->bindValue(":liked_by", $_SESSION['id']);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        return !empty($result);
    }
}
