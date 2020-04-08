<?php

namespace App\Tests\Entity;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{

    /**
     * @var Comment
     */
    private Comment $comment;

    public function setUp(): void
    {
        $this->comment = new Comment();
    }

    public function testGetId()
    {
        $this->assertNull($this->comment->getId());
    }

    public function testGetContent()
    {
        $this->comment->setContent('test');
        $this->assertSame('test', $this->comment->getContent());
    }

    public function testGetCreation()
    {
        $date = new \DateTime();
        $this->comment->setCreation();

        $this->assertSame($date->getTimestamp(), $this->comment->getCreation()->getTimestamp());
    }

    public function testGetTrick(){
        $trick = new Trick();
        $this->comment->setTrick($trick);
        self::assertSame($trick, $this->comment->getTrick());
    }

    public function testGetUser(){
        $user = new User();
        $this->comment->setUser($user);
        self::assertSame($user, $this->comment->getUser());
    }

}
