<?php

namespace App\Tests\Entity;

use App\Entity\Comment;
use App\Entity\User;
use App\Service\FileUploader;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

    /**
     * @var User
     */
    private User $user;

    protected function setUp(): void
    {
        $this->user = new User();
    }

    public function testGetId()
    {
        $this->assertNull($this->user->getId());
    }

    public function testGetName()
    {
        $this->user->setName('test');
        $this->assertSame('test', $this->user->getName());

    }

    public function testGetToken()
    {
        $this->user->setToken('test');
        $this->assertSame('test', $this->user->getToken());
    }


    public function testGetPicture()
    {
        $this->user->setPicture('test');
        $this->assertSame('test', $this->user->getPicture());
    }


    public function testGetPassword()
    {
        $this->user->setPassword('test');
        $this->assertSame('test', $this->user->getPassword());
    }

    public function testGetEmail()
    {
        $this->user->setEmail('test');
        $this->assertSame('test', $this->user->getEmail());
    }

    public function testGetTokenDate()
    {
        $date = new \DateTime();
        $this->user->setTokenDate($date);
        $this->assertSame($date, $this->user->getTokenDate());
    }

    public function testGetUsername()
    {
        $this->user->setEmail('test');
        $this->assertSame('test', $this->user->getUsername());
    }

    public function testGetRoles()
    {
        $this->user->setRoles(['ROLE_USER']);
        $this->assertSame(['ROLE_USER'], $this->user->getRoles());
    }

    public function testGetRolesEmpty()
    {
        $this->user->setRoles([]);
        $this->assertSame(['ROLE_USER'], $this->user->getRoles());
    }

    public function testGetFile()
    {
        $file = new FileUploader('test');
        $this->user->setFile($file);
        self::assertSame($file, $this->user->getFile());
    }

    public function testSerialize()
    {
        $serialized = $this->user->serialize();
        self::assertIsString($serialized);
        $unserialized = $this->user->unserialize($serialized);
        self::assertIsNotString($unserialized);
    }

    public function testGetSalt()
    {
        self::assertNull($this->user->getSalt());
    }

    public function testEraseCredentials()
    {
        self::assertNull($this->user->eraseCredentials());
    }

    public function testGetComments()
    {
        $comment = new Comment();
        $this->user->addComment($comment);
        self::assertSame(1, $this->user->getComments()->count());
        $this->user->removeComment($comment);
        self::assertSame(0, $this->user->getComments()->count());
    }
}
