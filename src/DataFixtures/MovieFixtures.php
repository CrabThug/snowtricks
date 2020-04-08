<?php


namespace App\DataFixtures;

use App\Entity\Movie;
use App\Repository\TrickRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * @var TrickRepository
     */
    private TrickRepository $trickRepository;

    public function __construct(TrickRepository $trickRepository)
    {
        $this->trickRepository = $trickRepository;
    }

    public function load(ObjectManager $manager)
    {
        $movies = [
            'Mute' => ['url' => 'https://www.youtube.com/embed/AzJPhQdTRQQ'],
            'Indy' => ['url' => 'https://www.youtube.com/embed/AzJPhQdTRQQ'],
        ];

        foreach ($movies as $trick => $mov) {
            $trick = $this->trickRepository->findOneBy(['title' => $trick]);
            foreach ($mov as $k => $m) {
                $movie = new Movie();
                $movie->setTrick($trick);
                $movie->setAlt($k);
                $movie->setEmbed($m);
                $manager->persist($movie);
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
