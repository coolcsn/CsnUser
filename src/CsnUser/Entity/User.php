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

namespace CsnUser\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Doctrine ORM implementation of User entity
 * 
 * @ORM\Entity(repositoryClass="CsnUser\Entity\Repository\UserRepository")
 * @ORM\Table(name="`user`",
 *   indexes={@ORM\Index(name="search_idx", columns={"username", "first_name", "last_name", "email"})}
 * )
 * @Annotation\Name("User")
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
     * @ORM\Column(name="username", type="string", length=30, nullable=false, unique=true)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "min":6, "max":30}})
     * @Annotation\Validator({"name":"Regex", "options":{"pattern":"/^[ña-zÑA-Z][ña-zÑA-Z0-9\_\-]+$/"}})
     * @Annotation\Required(true)
     * @Annotation\Attributes({
     *   "type":"text",
     *   "required":"true"
     * })
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=40, nullable=true)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":40}})
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=40, nullable=true)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "max":40}})
     */
    protected $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=60, nullable=false, unique=true)
     * @Annotation\Type("Zend\Form\Element\Email")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"EmailAddress"})
     * @Annotation\Required(true)
     * @Annotation\Attributes({
     *   "type":"email",
     *   "required":"true"
     * })
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=60, nullable=false)
     * @Annotation\Type("Zend\Form\Element\Password")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "min":6, "max":20}})
     * @Annotation\Required(true)
     * @Annotation\Attributes({
     *   "type":"password",
     *   "required":"true"
     * })
     */
    protected $password;

    /**
     * @var CsnUser\Entity\Role
     * 
     * @ORM\ManyToOne(targetEntity="CsnUser\Entity\Role")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id", nullable=false)
     * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"Digits"})
     * @Annotation\Required(true)
     * @Annotation\Options({
     *   "required":"true",
     *   "empty_option": "User Role",
     *   "target_class":"CsnUser\Entity\Role",
     *   "property": "name"
     * })
     */
    protected $role;

    /**
    * @var CsnUser\Entity\Language
    *
    * @ORM\ManyToOne(targetEntity="CsnUser\Entity\Language")
    * @ORM\JoinColumn(name="language_id", referencedColumnName="id", nullable=false)
    * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
    * @Annotation\Filter({"name":"StripTags"})
    * @Annotation\Filter({"name":"StringTrim"})
    * @Annotation\Validator({"name":"Digits"})
    * @Annotation\Options({
    *   "label":"Language:",
    *   "empty_option": "User Language",
    *   "target_class":"CsnUser\Entity\Language",
    *   "property": "name"
    * })
    */
    protected $language;

    /**
     * @var CsnUser\Entity\State
     *
     * @ORM\ManyToOne(targetEntity="CsnUser\Entity\State")
     * @ORM\JoinColumn(name="state_id", referencedColumnName="id", nullable=false)
     * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"Digits"})
     * @Annotation\Required(true)
     * @Annotation\Options({
     *   "required":"true",
     *   "empty_option": "User State",
     *   "target_class":"CsnUser\Entity\State",
     *   "property": "state"
     * })
     */
    protected $state;

    /**
     * @var CsnUser\Entity\Question
     *
     * @ORM\ManyToOne(targetEntity="CsnUser\Entity\Question")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id", nullable=false)
     * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"Digits"})
     * @Annotation\Required(true)
     * @Annotation\Options({
     *   "required":"true",
     *   "empty_option": "Security Question",
     *   "target_class":"CsnUser\Entity\Question",
     *   "property": "question"
     * })
     */
    protected $question;

    /**
     * @var string
     *
     * @ORM\Column(name="answer", type="string", length=100, nullable=false)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Filter({"name":"StringToLower", "options":{"encoding":"UTF-8"}})
     * @Annotation\Validator({"name":"StringLength", "options":{"encoding":"UTF-8", "min":6, "max":100}})
     * @Annotation\Validator({"name":"Alnum", "options":{"allowWhiteSpace":true}})
     * @Annotation\Required(true)
     * @Annotation\Options({
     *   "required":"true",
     *   "autocomplete":"off"
     * })
     */
    protected $answer;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255, nullable=true)
     * @Annotation\Type("Zend\Form\Element\File")
     */
    protected $picture;

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
     * @ORM\Column(name="registration_token", type="string", length=32, nullable=true)
     */
    protected $registrationToken;

    /**
     * @var boolean
     *
     * @ORM\Column(name="email_confirmed", type="boolean", nullable=false)
     */
    protected $emailConfirmed;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="myFriends")
     **/
    protected $friendsWithMe;

    /**
     * @ORM\ManyToMany(targetEntity="User", inversedBy="friendsWithMe")
     * @ORM\JoinTable(name="friends",
     *   joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="friend_id", referencedColumnName="id")}
     * )
     * @Annotation\Type("DoctrineModule\Form\Element\ObjectSelect")
     * @Annotation\Attributes({"multiple":true})
     * @Annotation\Options({
     *   "empty_option": "Please, choose your Friends",
     *   "target_class":"CsnUser\Entity\User",
     *   "property": "displayName",
     *   "is_method": true,
     *   "find_metod":{"name": "notExisitng", "params":{"criteria":{"id": "1"}, "orderBy":{"id": "DESC"}}}}
     * )
     **/
    protected $myFriends;

    public function __construct()
    {
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
     * @param  string $username
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
     * @param  string $displayName
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
     * Set firstName
     *
     * @param  string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param  string $lastName
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set password
     *
     * @param  string $password
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
     * @param  string $email
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
     * @param  Role $role
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
     * @param  Language $language
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
     * @param  boolean $state
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
     * @param  Question $question
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
     * @param  string $answer
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
     * @param  string $picture
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
     * Set registrationDate
     *
     * @param  string $registrationDate
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
     * @param  string $registrationToken
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
     * @param  string $emailConfirmed
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
     * @param  User $user
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
     * @param  User $user
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
     * @param  User $user
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
     * @param  User $user
     * @return User
     */
    public function removeFriendWithMe(\CsnUser\Entity\User $user)
    {
        $this->friendsWithMe->removeElement($user);

        return $this;
    }
}
