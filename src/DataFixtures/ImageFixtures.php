<?php


namespace App\DataFixtures;


use App\Entity\Image;
use App\Repository\TrickRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ImageFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var TrickRepository
     */
    private $trickRepository;

    /**
     * /**
     * ImageFixtures constructor.
     * @param TrickRepository $trickRepository
     */
    public function __construct(TrickRepository $trickRepository)
    {
        $this->trickRepository = $trickRepository;
    }

    public function load(ObjectManager $manager)
    {
        $images = [
            'Mute' => ['17.jpg', '16.jpg', '1.jpg', '15.jpg', '7.jpg'],
            'Indy' => ['2.jpg', '6.jpg', '8.jpg'],
            'Stalefish' => ['3.jpg'],
            '360' => ['4.jpg'],
            '720' => ['5.jpg'],
            'Frontflip' => ['6.jpg'],
            'Backflip' => ['7.jpg'],
            'Mac Twist' => ['8.jpg'],
            'Haakon Flip' => ['9.jpg'],
            'Corkscrew' => ['10.jpg'],
            'Rodeo' => ['11.jpg'],
            'Misty Flip' => ['12.jpg'],
            'Tail Slide' => ['13.jpg'],
            'Nose Slide' => ['14.jpg'],
            'Method Air' => ['15.jpg'],
        ];

        foreach ($images as $trick => $img) {
            $trick = $this->trickRepository->findOneBy(['title' => $trick]);
            $n = 0;
            foreach ($img as $i) {
                $image = new Image();
                $image->setTrick($trick);
                $image->setName($i);
                $image->setAlt($i);
                ($n === 0) ? $image->setBool(TRUE) : $image->setBool(FALSE);
                $manager->persist($image);
                $n++;
            }
            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [
            TrickFixtures::class
        ];
    }
}
