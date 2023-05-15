<?php 
namespace Promptopolis\Framework\Traits;

trait EmailVerificationTrait {
    public function sendVerifyEmail($key) 
    { 
        $token = $this->verifyToken; 
  
        //prevent XSS 
        $username = htmlspecialchars($this->username); 
  
        // send an email to the user 
        $email = new \SendGrid\Mail\Mail(); 
        $email->setFrom("r0896059@student.thomasmore.be", "Promptopolis"); 
        $email->setSubject("Verification email"); 
        $email->addTo($this->email, $this->username); 
        $email->addContent("text/plain", "Hi $username! Please activate your email. Here is the activation link http://localhost/php/eindwerk/verification.php?token=$token"); 
        $email->addContent( 
            "text/html", 
            "Hi $username! Please activate your email. <strong>Here is the activation link:</strong> http://localhost/php/eindwerk/verification.php?token=$token" 
        ); 
  
        $sendgrid = new \SendGrid($key); 
  
        try { 
            $response = $sendgrid->send($email); 
            return true; 
        } catch (\Exception $e) { 
            echo 'Caught exception: ' . $e->getMessage() . "\n"; 
            return false; 
        } 
  
        exit(); 
    }
}
