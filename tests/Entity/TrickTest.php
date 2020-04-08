<?php

namespace App\Tests\Entity;

use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Movie;
use App\Entity\Trick;
use PHPUnit\Framework\TestCase;

class TrickTest extends TestCase
{

    /**
     * @var Trick
     */
    private Trick $trick;

    protected function setUp(): void
    {
        $this->trick = new Trick();
    }

    public function testGetId()
    {
        $this->assertNull($this->trick->getId());
    }

    public function testGetDescription()
    {
        $this->trick->setDescription('test');
        $this->assertSame('test', $this->trick->getDescription());
    }

    public function testGetSlug()
    {
        $this->trick->setSlug('test');
        $this->assertSame('test', $this->trick->getSlug());
    }

    public function testGetTitle()
    {
        $this->trick->setTitle('test');
        $this->assertSame('test', $this->trick->getTitle());
    }

    public function testGetCreated()
    {
        $date = new \DateTime();
        $this->trick->setCreated();

        $this->assertSame($date->getTimestamp(), $this->trick->getCreated()->getTimestamp());
    }

    public function testGetUpdated()
    {
        $date = new \DateTime();
        $this->trick->setUpdated();

        $this->assertSame($date->getTimestamp(), $this->trick->getUpdated()->getTimestamp());
    }

    public function testGetComments()
    {
        $comment = new Comment();
        $this->trick->addComment($comment);
        self::assertSame(1, $this->trick->getComments()->count());
        $this->trick->removeComment($comment);
        self::assertSame(0, $this->trick->getComments()->count());
    }

    public function testGetImages()
    {
        $image = new Image();
        $this->trick->addImage($image);
        self::assertSame(1, $this->trick->getImages()->count());
        $this->trick->removeImage($image);
        self::assertSame(0, $this->trick->getImages()->count());
    }

    public function testGetMovies()
    {
        $movie = new Movie();
        $this->trick->addMovie($movie);
        self::assertSame(1, $this->trick->getMovies()->count());
        $this->trick->removeMovie($movie);
        self::assertSame(0, $this->trick->getMovies()->count());
    }

}
