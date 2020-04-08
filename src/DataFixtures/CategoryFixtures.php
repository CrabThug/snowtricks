<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    /**
     * Undocumented function
     *
     * @param ObjectManager $manager
     *
     * @return void
     */

    public function load(ObjectManager $manager)
    {
        $categoryName = ['Grabs', 'Rotations', 'Flips', 'Rotations désaxées', 'Slides', 'One foot', 'Old school'];

        foreach ($categoryName as $i => $iValue) {
            $category = new Category();
            $category->setName($iValue);
            $manager->persist($category);

            $this->addReference('category-' . $i, $category);
        }
        $manager->flush();
    }
}
