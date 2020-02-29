<?php


namespace App\Service;


use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickHandler
{
    private $fileUploader;
    private $entityManager;

    public function __construct(FileUploader $fileUploader, EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        $this->fileUploader = $fileUploader;
        $this->entityManager = $entityManager;
        $this->slugger = $slugger;
    }

    public function handle(Trick $trick)
    {
        foreach ($trick->getImages() as $image) {
            if (!$image->getId()) {
                $filename = $this->fileUploader->upload($image->getFile());
                $image->setName($filename);
                $image->setTrick($trick);
            }
        }
        $trick->setSlug($this->slugger->slug($trick->getTitle())->lower());

        $this->entityManager->persist($trick);
        $this->entityManager->flush();
    }
}
