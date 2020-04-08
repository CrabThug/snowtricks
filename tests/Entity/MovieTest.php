<?php

namespace App\Tests\Entity;

use App\Entity\Movie;
use App\Entity\Trick;
use PHPUnit\Framework\TestCase;

class MovieTest extends TestCase
{
    /**
     * @var Movie
     */
    private Movie $movie;

    protected function setUp(): void
    {
        $this->movie = new Movie();
    }

    public function testGetId()
    {
        $this->assertNull($this->movie->getId());
    }

    public function testGetTrick()
    {
        $trick = new Trick();
        $this->movie->setTrick($trick);
        $this->assertSame($trick, $this->movie->getTrick());
        $this->assertInstanceOf(Trick::class,$this->movie->getTrick());
    }

    public function testGetEmbed()
    {
        $this->movie->setEmbed('test');
        $this->assertSame('test', $this->movie->getEmbed());
    }

    public function testGetAlt()
    {
        $this->movie->setAlt('test');
        $this->assertSame('test', $this->movie->getAlt());
    }
}
