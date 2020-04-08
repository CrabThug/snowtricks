<?php

namespace App\Service;

use App\Entity\Image;
use App\Entity\Trick;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;

class ImageHandler
{
    private $fileUploader;
    private $imageRepository;
    private $em;

    public function __construct(FileUploader $fileUploader, ImageRepository $imageRepository, EntityManagerInterface $em)
    {
        $this->fileUploader = $fileUploader;
        $this->imageRepository = $imageRepository;
        $this->em = $em;
    }

    public function handle(Image $image, Trick $trick)
    {
        if ($image->getFile()) {
            $filename = $this->fileUploader->upload($image->getFile());
            $image->setName($filename);
            $this->em->persist($image);
        }
        $image->setTrick($trick);
        $this->bool($image);

        if ($image->getId()) {
            $this->em->flush();
        }

    }

    public function bool(Image $image)
    {
        $oldCover = $this->imageRepository->findOneBy(['trick' => $image->getTrick(), 'bool' => TRUE]);

        if ($image !== $oldCover && $oldCover !== NULL && $image->getBool()) {
            return $oldCover->setBool(FALSE);
        }

        if (!$image->getBool()) {
            $findOneByID = $this->imageRepository->findOneBy(['trick' => $image->getTrick(), 'bool' => FALSE], ['id' => 'ASC']);
            if ($image === $oldCover && $findOneByID !== NULL) {
                return $findOneByID->setBool(TRUE);
            }
            if ($findOneByID === NULL) {
                return $image->setBool(TRUE);
            }
        }
    }
}

