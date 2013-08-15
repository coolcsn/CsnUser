<?php
namespace CsnUserTest\Entity;

use CsnUser\Entity\Language;
use PHPUnit_Framework_TestCase;

class LanguageTest extends PHPUnit_Framework_TestCase
{
    public function testLanguageInitialState()
    {
        $user = new Language();

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