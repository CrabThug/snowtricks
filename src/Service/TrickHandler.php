<?php


namespace App\Service;


use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickHandler
{
    private $imageHandler;
    private $movieHandler;
    private $em;
    private $slugger;

    public function __construct(ImageHandler $imageHandler, MovieHandler $movieHandler, EntityManagerInterface $em, SluggerInterface $slugger)
    {
        $this->imageHandler = $imageHandler;
        $this->movieHandler = $movieHandler;
        $this->em = $em;
        $this->slugger = $slugger;
    }

    public function handle(Trick $trick)
    {
        foreach ($trick->getImages() as $image) {
            if (!$image->getId()) {
                $this->imageHandler->handle($image, $trick);
            }
        }
        foreach ($trick->getMovies() as $movie) {
            if (!$movie->getId()) {
                $this->movieHandler->handle($movie, $trick);
            }
        }
        $trick->setSlug($this->slugger->slug($trick->getTitle())->lower());

        $this->em->persist($trick);
        $this->em->flush();
    }
}
