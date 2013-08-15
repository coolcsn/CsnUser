<?php
namespace CsnUserTest\Entity;

use CsnUser\Entity\Role;
use PHPUnit_Framework_TestCase;

class RoleTest extends PHPUnit_Framework_TestCase
{
    public function testRoleInitialState()
    {
        $user = new Role();

        $this->assertNull(
            $user->getId(),
            '"id" should initially be null'
        );
        $this->assertNull(
            $user->getName(),
            '"name" should initially be null'
        );
    }
}