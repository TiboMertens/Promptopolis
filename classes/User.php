<?php
class User
{
    protected int $id;
    protected string $username;
    protected string $email;
    protected string $password;
    protected string $verifyToken;
    protected string $resetToken;

    /**
     * Get the value of id
     */
    public function getId($username)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT id FROM users WHERE username = :username");
        $statement->bindValue(":username", $username);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        if ($id != null && !empty($id) && is_numeric($id)) {
            $this->id = $id;

            return $this;
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
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
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
        //check if password is not empty and at least 10 characters long
        if (!empty($password) && strlen($password) >= 5) {
            //hash password with a factor of 12
            $password = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);

            $this->password = $password;
            return $this;
        } else {
            throw new Exception("Password cannot be empty and must be at least 5 characters long");
        }
    }

    /**
     * Get the value of token
     */
    public function getVerifyToken()
    {
        return $this->verifyToken;
    }

    /**
     * Set the value of token
     *
     * @return  self
     */
    public function setVerifyToken($verifyToken)
    {
        $this->verifyToken = $verifyToken;

        return $this;
    }

    public function sendVerifyEmail()
    {
        $token = $this->verifyToken;

        //prevent XSS
        $username = htmlspecialchars($this->username);

        // send an email to the user
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom("r0892926@student.thomasmore.be", "Prompthub");
        $email->setSubject("Verification email");
        $email->addTo($this->email, $this->username);
        $email->addContent("text/plain", "Hi $username! Please activate your email. Here is the activation link http://localhost/php/eindwerk/verification.php?token=$token");
        $email->addContent(
            "text/html",
            "Hi $username! Please activate your email. <strong>Here is the activation link:</strong> http://localhost/php/eindwerk/verification.php?token=$token"
        );

        $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));

        try {
            $response = $sendgrid->send($email);
            return true;
        } catch (Exception $e) {
            echo 'Caught exception: ' . $e->getMessage() . "\n";
            return false;
        }

        exit();
    }

    public function save()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("INSERT INTO users (username, email, password, verify_token) VALUES (:username, :email, :password, :token)");
        $statement->bindValue(":username", $this->username);
        $statement->bindValue(":email", $this->email);
        $statement->bindValue(":password", $this->password);
        $statement->bindValue(":token", $this->verifyToken);
        $result = $statement->execute();
        return $result;
    }

    public function checkVerifyToken($token)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM users WHERE verify_token = :token");
        $statement->bindValue(":token", $token);
        $statement->execute();
        $result = $statement->fetch();
        return $result;
    }

    public function activate($id)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET can_login = 1, verify_token = NULL WHERE id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
    }

    public function canLogin($username, $password)
    {
        try {
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT * FROM users WHERE username = :username");
            $statement->bindValue(":username", $username);
            $statement->execute();
            $user = $statement->fetch(PDO::FETCH_ASSOC);

            //check if user exists, if not throw exception
            if (!$user) {
                throw new Exception("Incorrect username.");
            }

            $hash = $user['password'];

            //check if password is correct, if not throw exception
            if (password_verify($password, $hash)) {
                if($user['can_login']==1){
                    return true;
                } else {
                    throw new Exception("Please verify your email first.");
                }
            } else {
                throw new Exception("Incorrect password.");
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function isModerator($id)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT is_admin FROM users WHERE id = :id");
        $statement->bindValue(":id", intval($id));
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $result = $result['is_admin'];

        //if result is 1, user is admin, else user is not admin
        if ($result === 1) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserDetails()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM users WHERE id = :id");
        $statement->bindValue(":id", $this->id);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function checkToVerify()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM prompts WHERE user_id = :id AND is_approved = 1");
        $statement->bindValue(":id", $this->id);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);

        //if result is 1, user is eligible to be verified, else user is not
        if (count($result) >= 3) {
            return true;
        } else {
            return false;
        }
    }

    public function verify()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET is_verified = 1 WHERE id = :id");
        $statement->bindValue(":id", $this->id);
        $statement->execute();
    }
    public function checkEmail($email)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $statement->bindValue(":email", $email);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        //if result is 1, email is already in use, else email is not in use
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    public function sendResetMail(){
        $token = $this->resetToken;


        // send an email to the user
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom("r0892926@student.thomasmore.be", "Prompthub");
        $email->setSubject("Reset email");
        $email->addTo($this->email);
        $email->addContent("text/plain", "Hi! Please reset your password. Here is the reset link http://localhost/php/eindwerk/resetPassword.php?token=$token");
        $email->addContent(
            "text/html",
            "Hi! Please reset your password. <strong>Here is the reset link :</strong> http://localhost/php/eindwerk/resetPassword.php?token=$token"
        );

        $sendgrid = new \SendGrid(getenv('SENDGRID_API_KEY'));

        try {
            $response = $sendgrid->send($email);
            return true;
        } catch (Exception $e) {
            echo 'Caught exception: ' . $e->getMessage() . "\n";
            return false;
        }

        exit();
    }

    /**
     * Get the value of resetToken
     */ 
    public function getResetToken()
    {
        return $this->resetToken;
    }

    /**
     * Set the value of resetToken
     *
     * @return  self
     */ 
    public function setResetToken($resetToken)
    {
        $this->resetToken = $resetToken;

        return $this;
    }
    public function saveResetToken(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET reset_token = :token, tstamp= :tstamp WHERE email = :email");
        $statement->bindValue(":token", $this->resetToken);
        $statement->bindValue(":tstamp", time());
        $statement->bindValue(":email", $this->email);
        $result = $statement->execute();
        return $result;

    }
    public function checkResetToken(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM users WHERE reset_token = :token");
        $statement->bindValue(":token", $this->resetToken);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        //if result is 1, token is valid, else token is not valid
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    public function checkTimestamp(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT tstamp FROM users WHERE reset_token = :token");
        $statement->bindValue(":token", $this->resetToken);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $result = $result['tstamp'];

        //if result is 1, token is valid, else token is not valid
        if (time() - $result < 86400) {
            return true;
        } else {
            return false;
        }
    }
    public function updatePassword(){
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET password = :password, reset_token = NULL, tstamp = NULL WHERE reset_token = :token");
        $statement->bindValue(":password", $this->password);
        $statement->bindValue(":token", $this->resetToken);
        $result = $statement->execute();
        return $result;
    }
}