<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DataFixtures\DependentFixtureInterface;
use App\Entity\Trick;

class TrickFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     *
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $tricksName = [
            ['Mute', 'saisie de la carre frontside de la planche entre les deux pieds avec la main avant', 'Grabs'],
            ['Indy', 'saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière'],
            ['Stalefish', 'saisie de la carre backside de la planche entre les deux pieds avec la main arrière'],
            ['360', 'trois six pour un tour complet'],
            ['720', 'sept deux pour deux tours complets'],
            ['Frontflip', 'rotation verticale en avant'],
            ['Backflip', 'rotation verticale en arriere'],
            ['Mac Twist', 'Frontflip combiné avec une rotation a 540°'],
            ['Haakon Flip', 'Une manœuvre aérienne effectuée dans une demi-lune en décollant en arrière, et en effectuant une rotation inversée de 720°.'],
            ['Corkscrew', "Un cork est une rotation horizontale plus ou moins désaxée, selon un mouvement d'épaules effectué juste au moment du saut."],
            ['Rodeo', 'Le rodeo est une rotation désaxée, qui se reconnaît par son aspect vrillé.'],
            ['Misty Flip', 'FrontFlip combiné avec une roration avant de 180°.'],
            ['Tail Slide', "Un tail slide consiste à glisser sur une barre de slide avec l'arrière de la planche sur la barre."],
            ['Nose Slide', "Un nose slide consiste à glisser sur une barre de slide avec l'avant de la planche sur la barre."],
            ['Method Air', "Attraper sa planche d'une main et la tourner perpendiculairement au sol"]
        ];

        for ($i = 0, $iMax = \count($tricksName); $i < $iMax; $i++) {
            $trick = new Trick();
            $trick->setTitle($tricksName[$i][0])
                ->setDescription($tricksName[$i][1])
                ->setCategory($this->getReference('category-' . rand(0, 6)));
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
