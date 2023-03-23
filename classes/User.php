<?php
class User {
    private string $username;
    private string $email;
    private string $password;

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
        } else {
            throw new Exception("Not a valid username");
        }
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        if (!empty($email) && strpos($email, "@")) {
            $this->email = $email;
            return $this;
        } else {
            throw new Exception("Not a valid email address");
        }
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword($password)
    {
        if (!empty($password) && strlen($password) >= 10) {
            //hash password with a factor of 12
            $password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

            $this->password = $password;
            return $this;
        } else {
            throw new Exception("Password cannot be empty and must be at least 10 characters long");
        }
    }

    public function save(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("insert into users (username, email, password) values (:username, :email, :password)");
        $statement->bindValue(":username", $this->username);
        $statement->bindValue(":email", $this->email);
        $statement->bindValue(":password", $this->password);
        $result = $statement->execute();
        return $result;
    }
}