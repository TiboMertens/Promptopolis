<?php

namespace Promptopolis\Framework;

//traits    

use Exception;
use Promptopolis\Framework\Traits\EmailVerificationTrait;

class User
{
    protected int $id;
    protected string $username;
    protected string $email;
    protected string $password;
    protected string $verifyToken;
    protected string $resetToken;
    protected string $bio;

    use EmailVerificationTrait;

    /**
     * Get the value of id
     */
    public function getId($username)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT id FROM users WHERE username = :username");
        $statement->bindValue(":username", $username);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
            return $result["id"];
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
        if (!empty($username) && self::checkUsername($username)) {
            $this->username = $username;
            return $this;
        } else {
            throw new \exception("Not a valid username");
        }
    }

    public function checkUsername($username) {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT username FROM users WHERE username = :username");
        $statement->bindValue(":username", $username);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($result) {
            return false;
        } else {
            return true;
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
        if (filter_var($email, FILTER_VALIDATE_EMAIL) && self::checkEmail($email)) {
            $this->email = $email;
            return $this;
        } else {
            throw new \exception("Not a valid email address");
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
            throw new \exception("Password cannot be empty and must be at least 5 characters long");
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
        } catch (\Throwable $e) {
            throw new \exception("connection failed.");
        }
        $statement = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $statement->bindValue(":username", $username);
        $statement->execute();
        $user = $statement->fetch(\PDO::FETCH_ASSOC);

        //check if user exists, if not throw exception
        if (!$user) {
            throw new \exception("Incorrect username.");
        }

        $hash = $user['password'];

        //check if password is correct, if not throw exception
        if (password_verify($password, $hash)) {
            if ($user['can_login'] == 1) {
                return true;
            } else {
                throw new \exception("Please verify your email first.");
            }
        } else {
            throw new \exception("Incorrect password.");
        }
    }

    public static function isModerator($id)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT is_admin FROM users WHERE id = :id");
        $statement->bindValue(":id", intval($id));
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        return $result['is_admin'];
    }

    public function getUserDetails($id)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM users WHERE id = :id");
        $statement->bindValue(":id", $id);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function checkToVerify()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM prompts WHERE user_id = :id AND is_approved = 1");
        $statement->bindValue(":id", $this->id);
        $statement->execute();
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

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
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        //if result is 1, email is already in use, else email is not in use
        if ($result) {
            return false;
        } else {
            return true;
        }
    }

    public function checkExistingEmail($email)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $statement->bindValue(":email", $email);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        //if result is 1, email is already in use, else email is not in use
        if ($result) {
            return true;
        } else {
            throw new \exception("Email is not in use.");
        }
    }

    public function sendResetMail($key)
    {
        $token = $this->resetToken;


        // send an email to the user
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom("r0892926@student.thomasmore.be", "Promptopolis");
        $email->setSubject("Reset email");
        $email->addTo($this->email);
        $email->addContent("text/plain", "Hi! Please reset your password. Here is the reset link http://localhost/php/eindwerk/resetPassword.php?token=$token");
        $email->addContent(
            "text/html",
            "Hi! Please reset your password. <strong>Here is the reset link :</strong> http://localhost/php/eindwerk/resetPassword.php?token=$token"
        );

        $sendgrid = new \SendGrid($key);

        try {
            $response = $sendgrid->send($email);
            return true;
        } catch (\exception $e) {
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
    public function saveResetToken()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET reset_token = :token, tstamp= :tstamp WHERE email = :email");
        $statement->bindValue(":token", $this->resetToken);
        $statement->bindValue(":tstamp", time());
        $statement->bindValue(":email", $this->email);
        $result = $statement->execute();
        return $result;
    }
    public function checkResetToken()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM users WHERE reset_token = :token");
        $statement->bindValue(":token", $this->resetToken);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        //if result is 1, token is valid, else token is not valid
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    public function checkTimestamp()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT tstamp FROM users WHERE reset_token = :token");
        $statement->bindValue(":token", $this->resetToken);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        $result = $result['tstamp'];

        //if result is 1, token is valid, else token is not valid
        if (time() - $result < 86400) {
            return true;
        } else {
            return false;
        }
    }
    public function updatePassword()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET password = :password, reset_token = NULL, tstamp = NULL WHERE reset_token = :token");
        $statement->bindValue(":password", $this->password);
        $statement->bindValue(":token", $this->resetToken);
        $result = $statement->execute();
        return $result;
    }
    public function updateUserDetails()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET username = :username, bio = :bio, profile_picture_url= :profile_picture_url WHERE id = :id");
        $statement->bindValue(":username", $this->username);
        $statement->bindValue(":bio", $this->bio);
        $statement->bindValue(":id", $_SESSION['id']);
        $statement->bindValue(":profile_picture_url", $this->profile_picture_url);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * Get the value of bio
     */
    public function getBio()
    {
        return $this->bio;
    }

    /**
     * Set the value of bio
     *
     * @return  self
     */
    public function setBio($bio)
    {
        $this->bio = $bio;

        return $this;
    }

    public function deleteAccount()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("DELETE FROM users WHERE id = :id");
        $statement->bindValue(":id", $this->id);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }

    private string $profile_picture_url;

    /**
     * Get the value of profile_picture_url
     */
    public function getProfile_picture_url()
    {
        return $this->profile_picture_url;
    }

    /**
     * Set the value of profile_picture_url
     *
     * @return  self
     */
    public function setProfile_picture_url($profile_picture_url)
    {
        $this->profile_picture_url = $profile_picture_url;

        return $this;
    }

    public function canChangePassword($password)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM users WHERE id = :id");
        $statement->bindValue(":id", $this->id);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        //check if filled in password is the same as the one in the database
        if (password_verify($password, $result['password'])) {
            return true;
        } else {
            return false;
        }
    }
    public function changePassword()
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET password = :password WHERE id = :id");
        $statement->bindValue(":password", $this->password);
        $statement->bindValue(":id", $this->id);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        return $result;
    }

    public function signup($key)
    {
        self::save();
        $this->sendVerifyEmail($key);
        header("Location:index.php");
    }

    public function getVotes($id)
    {
        $conn = Db::getInstance();
        //get all the rows where the user has been voted for
        $statement = $conn->prepare("SELECT * FROM user_vote WHERE voted_for = :user_id");
        $statement->bindValue(":user_id", $id);
        $statement->execute();
        //count the amount of rows and return it
        $count = $statement->rowCount();
        return $count;
    }
    public function followUser($followsId)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("INSERT INTO user_follow (followed_by, follows) VALUES (:followed_by, :follows)");
        $statement->bindValue(":followed_by", $_SESSION['id']);
        $statement->bindValue(":follows", $followsId);
        $statement->execute();
    }

    public function unfollowUser($followsId)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("DELETE FROM user_follow WHERE followed_by = :followed_by AND follows = :follows");
        $statement->bindValue(":followed_by", $_SESSION['id']);
        $statement->bindValue(":follows", $followsId);
        $statement->execute();
    }

    public function isFollowing($followsId)
    {
        $conn = Db::getInstance();
        $statement = $conn->prepare("SELECT * FROM user_follow WHERE followed_by = :followed_by AND follows = :follows");
        $statement->bindValue(":followed_by", $_SESSION['id']);
        $statement->bindValue(":follows", $followsId);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        return !empty($result);
    }
}
