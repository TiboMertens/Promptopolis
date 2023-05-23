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
        $result = self::check($id, $loggedInUser_id);
        if (!$result) {
            $result = self::addLike($id, $loggedInUser_id);
        } else {
            $result = self::removeLike($id, $loggedInUser_id);
        }
        return $result;
    }

    public function check($id, $loggedInUser_id)
    {
        //if the current user has already voted for the user, he cannot vote again
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM user_like WHERE liked_for = :liked_for AND liked_by = :liked_by");
        $statement->bindValue(":liked_for", $id);
        $statement->bindValue(":liked_by", $loggedInUser_id);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function addLike($id, $loggedInUser_id)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("INSERT INTO user_like (liked_for, liked_by) VALUES (:liked_for, :liked_by)");
        $statement->bindValue(":liked_for", $id);
        $statement->bindValue(":liked_by", $loggedInUser_id);
        $statement->execute();
        return true;
    }

    public function removeLike($id, $loggedInUser_id)
    {
        $conn = Db::getInstance();
        //if the user has already liked for the user, delete the like from the database
        $statement = $conn->prepare("DELETE FROM user_like WHERE liked_for = :liked_for AND liked_by = :liked_by");
        $statement->bindValue(":liked_for", $id);
        $statement->bindValue(":liked_by", $loggedInUser_id);
        $statement->execute();
        return false;
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
