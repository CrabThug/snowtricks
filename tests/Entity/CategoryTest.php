<?php

namespace App\Tests\Entity;

use App\Entity\Category;
use App\Entity\Trick;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{

    /**
     * @var Category
     */
    private $category;

    public function setUp(): void
    {
        $this->category = new Category();
    }

    public function testGetId()
    {
        $this->assertNull($this->category->getId());
    }

    public function testName()
    {
        $this->category->setName('test');
        $this->assertSame('test', $this->category->getName());
    }

    public  function testGetTrick(){

        $trick = new Trick();
        $this->category->addTrick($trick);
        self::assertSame(1, $this->category->getTricks()->count());
        $this->category->removeTrick($trick);
        self::assertSame(0, $this->category->getTricks()->count());

    }
}
