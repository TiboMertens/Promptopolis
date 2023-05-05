<?php

namespace Promptopolis\Framework;

class comment
{
    private string $comment;
    private string $username;

    

    /**
     * Get the value of comment
     */ 
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set the value of comment
     *
     * @return  self
     */ 
    public function setComment($comment)
    {
        if (!empty($comment)) {
            $this->comment = $comment;

            return $this;
        }else {
            throw new \exception('Comment cannot be empty');
        }
        
    }

    /**
     * Get the value of username
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */ 
    public function setUsername($username)
    {
        if (!empty($username)) {
            $this->username = $username;

            return $this;
        }else {
            throw new \exception('Comment cannot be empty');
        }
       
    }

   public function save($id){
    $conn=Db::getInstance();
    $statement = $conn->prepare("INSERT INTO user_comment (comment, comment_by, comment_for) VALUES (:comment, :comment_by, :comment_for)");
    $statement->bindValue(':comment', $this->getComment());
    $statement->bindValue(':comment_by', $this->getUsername());
    $statement->bindValue(':comment_for', $id);
    $statement->execute();
   }

    public function getComments($id){
     $conn=Db::getInstance();
     $statement = $conn->prepare("SELECT * FROM user_comment WHERE comment_for = :comment_for  ORDER BY date DESC");
     $statement->bindValue(':comment_for', $id);
     $statement->execute();
     $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
     return $result;
    }
}
