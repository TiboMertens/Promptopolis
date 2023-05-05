<?php 
namespace Promptopolis\Framework;

class Purchase{
    public function purchase($id, $user_id){
        $credits =  self::getCredits($user_id);
        $price = self::getPrice($id);
        if($credits >= $price){
            self::buy($id, $user_id, $price);
        }else{
            throw new \Exception("You don't have enough credits to buy this prompt.");
        }
    }

    public function getCredits($user_id){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT credits FROM users WHERE id = :id");
        $statement->bindValue(":id", $user_id);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        return $result['credits'];
    }

    public function getPrice($id){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT price FROM prompts WHERE id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        return $result['price'];
    }

    public function buy($id, $user_id, $price){
        $conn = Db::getInstance();
        $statement = $conn->prepare("INSERT INTO purchases (prompt_id, user_id) VALUES (:prompt_id, :user_id)");
        $statement->bindValue(":prompt_id", $id);
        $statement->bindValue(":user_id", $user_id);
        $statement->execute();

        //update the credits of the user
        $statement = $conn->prepare("UPDATE users SET credits = credits - ($price) WHERE id = :user_id");
        $statement->bindValue(":user_id", $user_id);
        $statement->execute();
    }
}