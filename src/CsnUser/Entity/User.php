<?php

namespace CsnUser\Entity;

use Doctrine\ORM\Mapping as ORM;//MappedSuperclass (delete this comment)
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Zend\Form\Annotation;

/**
 * Default implementation of User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="CsnUser\Entity\Repository\UserRepository")
 * @Annotation\Name("User")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ClassMethods")
 */
class User
{
	/**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
	 * @Annotation\Exclude()
     */
    protected $id;
	
    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=100, nullable=false)
	 * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":1, "max":30}})
     * @Annotation\Validator({"name":"Regex", "options":{"pattern":"/^[a-zA-Z][a-zA-Z0-9_-]{0,24}$/"}})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Username:"})
     */
    protected $username;
	
    /**
     * @var string
     *
     * @ORM\Column(name="display_name", type="string", length=100, nullable=false)
	 * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":1, "max":40}})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Display name:"})
     */
    protected $displayName;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=100, nullable=false)
     * @Annotation\Attributes({"type":"password"})
     * @Annotation\Options({"label":"Password:"})	
     */
    protected $password;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=60, nullable=false)
	 * @Annotation\Type("Zend\Form\Element\Email")
     * @Annotation\Options({"label":"Your email address:"})
     */
    protected $email;

	/**
	* @var CsnUser\Entity\Role
	*
	* @ORM\ManyToOne(targetEntity="CsnUser\Entity\Role")
	* @ORM\JoinColumn(name="role_id", referencedColumnName="id")
	* @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
	* @Annotation\Options({
	* "label":"Role:",
	* "empty_option": "Please, choose your role",
	* "target_class":"CsnUser\Entity\Role",
	* "property": "name"})
	*/
	protected $role;
	 
	/**
	* @var CsnUser\Entity\Language
	*
	* @ORM\ManyToOne(targetEntity="CsnUser\Entity\Language")
	* @ORM\JoinColumn(name="language_id", referencedColumnName="id")
	* @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
	* @Annotation\Options({
	* "label":"Language:",
	* "empty_option": "Please, choose your language",
	* "target_class":"CsnUser\Entity\Language",
	* "property": "name"})
	*/
	protected $language;

    /**
     * @var integer
     *
     * @ORM\Column(name="state", type="integer", nullable=false)
	 * @Annotation\Type("Zend\Form\Element\Radio")
	 * @Annotation\Options({
	 * "label":"User Active:",
	 * "value_options":{"1":"Yes", "0":"No"}})
     */
    protected $state;

    /**
     * @var string
     *
     * @ORM\Column(name="question", type="string", length=100, nullable=true)
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"User Question:"})
     */
    protected $question;

    /**
     * @var string
     *
     * @ORM\Column(name="answer", type="string", length=100, nullable=true)
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"User Answer:"})
     */
    protected $answer;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255, nullable=true)
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"User Picture:"})
     */
    protected $picture;

    /**
     * @var string
     *
     * @ORM\Column(name="password_salt", type="string", length=100, nullable=true)
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Password Salt:"})
     */
    protected $passwordSalt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registration_date", type="datetime", nullable=true)
     * @Annotation\Attributes({"type":"datetime","min":"2010-01-01T00:00:00Z","max":"2020-01-01T00:00:00Z","step":"1"})
     * @Annotation\Options({"label":"Registration Date:", "format":"Y-m-d\TH:iP"})
     */
    protected $registrationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="registration_token", type="string", length=100, nullable=true)
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Registration Token:"})
     */
    protected $registrationToken;
	
    /**
     * @var boolean
     *
     * @ORM\Column(name="email_confirmed", type="boolean", nullable=false)
	 * @Annotation\Type("Zend\Form\Element\Radio")
	 * @Annotation\Options({
	 * "label":"User confirmed email:",
	 * "value_options":{"1":"Yes", "0":"No"}})
     */
    protected $emailConfirmed;
    
    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="myFriends")
     * @Annotation\Exclude()
     **/
    protected $friendsWithMe;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="friendsWithMe")
     * @ORM\JoinTable(name="friends",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="friend_id", referencedColumnName="id")}
     *      )
     * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
     * @Annotation\Attributes({"multiple":true})
     * @Annotation\Options({
     * "label":"My Friends:",
     * "empty_option": "Please, choose your Friends",
     * "target_class":"CsnUser\Entity\User",
     * "property": "displayName",
     * "is_method": true,
     * "find_metod":{"name": "notExisitng", "params":{"criteria":{"id": "1"}, "orderBy":{"id": "DESC"}}}})
     **/
    protected $myFriends;

    public function __construct()
    {
        $this->registrationDate = new \DateTime();
        $this->friendsWithMe = new ArrayCollection();
        $this->myFriends = new ArrayCollection();
    }
		
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
	
    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }
	
    /**
     * Set displayName
     *
     * @param string $displayName
     * @return User
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    
        return $this;
    }

    /**
     * Get displayName
     *
     * @return string 
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set role
     *
     * @param Role $role
     * @return User
     */
    public function setRole($role)
    {
        $this->role = $role;
    
        return $this;
    }

    /**
     * Get role
     *
     * @return Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set language
     *
     * @param Language $language
     * @return User
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    
        return $this;
    }

    /**
     * Get language
     *
     * @return Language 
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set user state
     *
     * @param boolean $state
     * @return User
     */
    public function setState($state)
    {
        $this->state = $state;
    
        return $this;
    }

    /**
     * Get user state
     *
     * @return boolean 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set question
     *
     * @param string $question
     * @return User
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    
        return $this;
    }

    /**
     * Get question
     *
     * @return string 
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set answer
     *
     * @param string $answer
     * @return User
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;
    
        return $this;
    }

    /**
     * Get answer
     *
     * @return string 
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set picture
     *
     * @param string $picture
     * @return User
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    
        return $this;
    }

    /**
     * Get picture
     *
     * @return string 
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set passwordSalt
     *
     * @param string $passwordSalt
     * @return User
     */
    public function setPasswordSalt($passwordSalt)
    {
        $this->passwordSalt = $passwordSalt;
    
        return $this;
    }

    /**
     * Get passwordSalt
     *
     * @return string 
     */
    public function getPasswordSalt()
    {
        return $this->passwordSalt;
    }

    /**
     * Set registrationDate
     *
     * @param string $registrationDate
     * @return User
     */
    public function setRegistrationDate($registrationDate)
    {
        $this->registrationDate = $registrationDate;
    
        return $this;
    }

    /**
     * Get registrationDate
     *
     * @return string 
     */
    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }

    /**
     * Set registrationToken
     *
     * @param string $registrationToken
     * @return User
     */
    public function setRegistrationToken($registrationToken)
    {
        $this->registrationToken = $registrationToken;
    
        return $this;
    }

    /**
     * Get registrationToken
     *
     * @return string 
     */
    public function getRegistrationToken()
    {
        return $this->registrationToken;
    }
	
    /**
     * Set emailConfirmed
     *
     * @param string $emailConfirmed
     * @return User
     */
    public function setEmailConfirmed($emailConfirmed)
    {
        $this->emailConfirmed = $emailConfirmed;
    
        return $this;
    }

    /**
     * Get emailConfirmed
     *
     * @return string 
     */
    public function getEmailConfirmed()
    {
        return $this->emailConfirmed;
    }
    
    /**
     * Get myFriends - mandatory with ManyToMany
     *
     * @return Collection
     */
    public function getMyFriends()
    {    
        return $this->myFriends;        
    }
    
    /**
     * Add myFriends - mandatory with ManyToMany
     *
     * @param Collection
     * @return User
     */
    public function addMyFriends(Collection $users)
    {
        foreach ($users as $user) {
            $this->addMyFriend($user);
        }
        return $this;        
    }
    
    /**
     * Add myFriend
     *
     * @param User $user
     * @return User
     */
    public function addMyFriend(\CsnUser\Entity\User $user)
    {
        $user->addFriendWithMe($this); // synchronously updating inverse side. Tell your new friend you have added him as a friend
        $this->myFriends[] = $user;
        return $this;
    }
    
    /**
     * Remove myFriends
     *
     * @param Collection
     * @return User
     */    
    public function removeMyFriends(Collection $users)
    {
        foreach ($users as $user) {
            $this->removeMyFriend($user);
        }
        return $this;        
    }
    
    /**
     * Remove myFriend
     *
     * @param User $user
     * @return User
     */
    public function removeMyFriend(\CsnUser\Entity\User $user)
    {
        $user->removeFriendWithMe($this); // synchronously updating inverse side.
        $this->myFriends->removeElement($user);
        return $this;
    }
    
    /**
     * Add friendWithMe
     *
     * @param User $user
     * @return User
     */
    public function addFriendWithMe(\CsnUser\Entity\User $user)    
    {
        $this->friendsWithMe[] = $user;
        return $this;
    }

    /**
     * Remove friendWithMe
     *
     * @param User $user
     * @return User
     */
    public function removeFriendWithMe(\CsnUser\Entity\User $user)
    {
        $this->friendsWithMe->removeElement($user);
        return $this;
    }    
}
