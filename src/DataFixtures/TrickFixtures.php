<?php

namespace App\DataFixtures;

use App\Repository\CategoryRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Trick;
use Symfony\Component\String\Slugger\SluggerInterface;

class TrickFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var SluggerInterface
     */
    private SluggerInterface $slugger;
    /**
     * @var CategoryRepository
     */
    private CategoryRepository $categoryRepository;

    public function __construct(SluggerInterface $slugger, CategoryRepository $categoryRepository)
    {
        $this->slugger = $slugger;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $tricksName = [
            ['Mute', 'saisie de la carre frontside de la planche entre les deux pieds avec la main avant', 'Grabs'],
            ['Indy', 'saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière', 'Grabs'],
            ['Stalefish', 'saisie de la carre backside de la planche entre les deux pieds avec la main arrière', 'Grabs'],
            ['360', 'trois six pour un tour complet', 'Rotations'],
            ['720', 'sept deux pour deux tours complets', 'Rotations'],
            ['Frontflip', 'rotation verticale en avant', 'Flips'],
            ['Backflip', 'rotation verticale en arriere', 'Flips'],
            ['Mac Twist', 'Frontflip combiné avec une rotation a 540°', 'Flips'],
            ['Haakon Flip', 'Une manœuvre aérienne effectuée dans une demi-lune en décollant en arrière, et en effectuant une rotation inversée de 720°.', 'Flips'],
            ['Corkscrew', "Un cork est une rotation horizontale plus ou moins désaxée, selon un mouvement d'épaules effectué juste au moment du saut.", 'Rotations désaxées'],
            ['Rodeo', 'Le rodeo est une rotation désaxée, qui se reconnaît par son aspect vrillé.', 'Rotations désaxées'],
            ['Misty Flip', 'FrontFlip combiné avec une roration avant de 180°.', 'Rotations désaxées'],
            ['Tail Slide', "Un tail slide consiste à glisser sur une barre de slide avec l'arrière de la planche sur la barre.", 'Slides'],
            ['Nose Slide', "Un nose slide consiste à glisser sur une barre de slide avec l'avant de la planche sur la barre.", 'Slides'],
            ['Method Air', "Attraper sa planche d'une main et la tourner perpendiculairement au sol", 'Old school']
        ];

        foreach ($tricksName as $iValue) {
            $trick = new Trick();
            $category = $this->categoryRepository->findOneBy(['name' => $iValue[2]]);
            $trick->setTitle($iValue[0])
                ->setSlug($this->slugger->slug($iValue[0])->lower())
                ->setDescription($iValue[1])
                ->setCategory($category);
            $manager->persist($trick);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class
        ];
    }
}
