<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $categoryName = ['Grabs', 'Rotations', 'Flips', 'Rotations désaxées', 'Slides', 'One foot', 'Old school'];

        for ($i = 0; $i < \count($categoryName); $i++) {
            $category = new Category();
            $category->setName($categoryName[$i]);

            $manager->persist($category);
        }
        $manager->flush();
    }
}
