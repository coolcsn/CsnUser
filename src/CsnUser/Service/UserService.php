<?php
 
namespace CsnUser\Service;
 
use Zend\Crypt\Password\Bcrypt;
use CsnUser\Entity\User;
 
class UserService
{
    /**
    * Static function for checking hashed password (as required by Doctrine)
    * @param snUser\Entity\User $user The identity object
    * @param string $passwordGiven Password provided to be verified
    * @return boolean true if the password was correct, else, returns false
    */
    public static function verifyHashedPassword(User $user, $passwordGiven)
    {
        $bcrypt = new Bcrypt(array('cost' => 10));
        return $bcrypt->verify($passwordGiven, $user->getPassword());
    }
}
