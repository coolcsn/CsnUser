<?php
namespace CsnUserTest\Entity;

use CsnUser\Entity\User;
use PHPUnit_Framework_TestCase;

class UserTest extends PHPUnit_Framework_TestCase
{
    public function testUserInitialState()
    {
        $user = new User();

        $this->assertNull(
            $user->getId(),
            '"id" should initially be null'
        );
        $this->assertNull(
            $user->getUsername(),
            '"username" should initially be null'
        );
        $this->assertNull(
            $user->getDisplayName(),
            '"displayName" should initially be null'
        );
        $this->assertNull(
            $user->getPassword(),
            '"password" should initially be null'
        );
        $this->assertNull(
            $user->getEmail(),
            '"email" should initially be null'
        );
        $this->assertNull(
            $user->getRole(),
            '"role" should initially be null'
        );
        $this->assertNull(
            $user->getLanguage(),
            '"language" should initially be null'
        );
        $this->assertNull(
            $user->getState(),
            '"state" should initially be null'
        );
        $this->assertNull(
            $user->getQuestion(),
            '"question" should initially be null'
        );
        $this->assertNull(
            $user->getAnswer(),
            '"answer" should initially be null'
        );
        $this->assertNull(
            $user->getPicture(),
            '"picture" should initially be null'
        );
        $this->assertNull(
            $user->getPasswordSalt(),
            '"passwordSalt" should initially be null'
        );
        $this->assertEquals(
            $user->getRegistrationDate(),
            new \DateTime(),
            '"registrationDate" should initially be <Now>'
        );
        $this->assertNull(
            $user->getRegistrationToken(),
            '"registrationToken" should initially be null'
        );
        $this->assertNull(
            $user->getEmailConfirmed(),
            '"emailConfirmed" should initially be null'
        );
    }
}