<?php 
namespace Promptopolis\Framework;

class Purchase{
    public function purchase($id, $user_id, $author_id){
        $creditsU =  \Promptopolis\Framework\User::getCredits($user_id);
        $creditsA = \Promptopolis\Framework\User::getCredits($author_id);
        $price = self::getPrice($id);
        if($creditsU >= $price){
            self::buy($id, $user_id, $price);
            $creditsU -= $price;
            \Promptopolis\Framework\User::updateCredits($user_id, $creditsU);
            $creditsA += 3;
            \Promptopolis\Framework\User::updateCredits($author_id, $creditsA);
        }else{
            throw new \Exception("You don't have enough credits to buy this prompt.");
        }
    }

    public function getPrice($id){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT price FROM prompts WHERE id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        return $result['price'];
    }

    public function buy($id, $user_id){
        $conn = Db::getInstance();
        $statement = $conn->prepare("INSERT INTO purchases (prompt_id, user_id) VALUES (:prompt_id, :user_id)");
        $statement->bindValue(":prompt_id", $id);
        $statement->bindValue(":user_id", $user_id);
        $statement->execute();
    }

    public static function checkBought($prompt_id, $user_id){
        //check if user has already bought this prompt
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM purchases WHERE prompt_id = :prompt_id AND user_id = :user_id");
        $statement->bindValue(":prompt_id", $prompt_id);
        $statement->bindValue(":user_id", $user_id);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        if($result){
            return true;
        }else{
            return false;
        }
    }
}