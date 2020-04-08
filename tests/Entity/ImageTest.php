<?php

namespace App\Tests\Entity;

use App\Entity\Image;
use App\Entity\Trick;
use App\Service\FileUploader;
use PHPUnit\Framework\TestCase;

class ImageTest extends TestCase
{

    /**
     * @var Image
     */
    private Image $image;

    protected function setUp(): void
    {
        $this->image = new Image();
    }

    public function testGetId()
    {
        $this->assertNull($this->image->getId());
    }

    public function testGetAlt()
    {
        $this->image->setAlt('test');
        $this->assertSame('test', $this->image->getAlt());
    }

    public function testGetName()
    {
        $this->image->setName('test');
        $this->assertSame('test', $this->image->getName());
    }

    public function testGetBoolTrue()
    {
        $this->image->setBool(TRUE);
        $this->assertTrue($this->image->getBool());
    }

    public function testGetBoolFalse()
    {
        $this->image->setBool(FALSE);
        $this->assertFalse($this->image->getBool());
    }

    public function testGetFile()
    {
        $file = new FileUploader('test');
        $this->image->setFile($file);
        $this->assertSame($file, $this->image->getFile());
    }

    public function testGetTrick()
    {
        $trick = new Trick();
        $this->image->setTrick($trick);
        $this->assertSame($trick, $this->image->getTrick());
        $this->assertInstanceOf(Trick::class, $this->image->getTrick());
    }
}
