<?php
/**
 * CsnUser - Coolcsn Zend Framework 2 User Module
 * 
 * @link https://github.com/coolcsn/CsnUser for the canonical source repository
 * @copyright Copyright (c) 2005-2013 LightSoft 2005 Ltd. Bulgaria
 * @license https://github.com/coolcsn/CsnUser/blob/master/LICENSE BSDLicense
 * @author Stoyan Cheresharov <stoyan@coolcsn.com>
 * @author Svetoslav Chonkov <svetoslav.chonkov@gmail.com>
 * @author Nikola Vasilev <niko7vasilev@gmail.com>
 * @author Stoyan Revov <st.revov@gmail.com>
 * @author Martin Briglia <martin@mgscreativa.com>
 */
 
namespace CsnUser\Service;
 
use Zend\Crypt\Password\Bcrypt;
use CsnUser\Entity\User;
 
class UserService
{
    /**
    * Static function for checking hashed password (as required by Doctrine)
    *
    * @param snUser\Entity\User $user The identity object
    * @param string $passwordGiven Password provided to be verified
    * @return boolean true if the password was correct, else, returns false
    */
    public static function verifyHashedPassword(User $user, $passwordGiven)
    {
        $bcrypt = new Bcrypt(array('cost' => 10));
        return $bcrypt->verify($passwordGiven, $user->getPassword());
    }
    
    /**
     * Encrypt Password
     *
     * Creates a Bcrypt password hash
     *
     * @return String
     */
    public static function encryptPassword($password)
    {
        $bcrypt = new Bcrypt(array('cost' => 10));
        return $bcrypt->create($password);
    }
}
