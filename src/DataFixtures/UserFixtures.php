<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $users = [
            ['admin', 'user.jpeg'],
            ['toto', 'guest.jpg'],
            ['tata', 'usertile10.jpg'],
            ['titi', 'usertile11.jpg'],
            ['tutu', 'usertile12.jpg'],
        ];

        foreach ($users as $iUser) {
            $user = new User();
            $user->setEmail($iUser[0] . '@snowtrick.fr')
                ->setName($iUser[0])
                ->setPassword(password_hash('passwordtest', PASSWORD_DEFAULT))
                ->setPicture($iUser[1]);
            $manager->persist($user);
        }
        $manager->flush();
    }
}
