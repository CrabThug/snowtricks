<?php


namespace App\DataFixtures;


use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\User;
use App\Repository\TrickRepository;
use App\Repository\UserRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var UserRepository
     */
    private UserRepository $users;
    /**
     * @var TrickRepository
     */
    private TrickRepository $trickRepository;

    public function __construct(UserRepository $users, TrickRepository $trickRepository)
    {
        $this->users = $users;
        $this->trickRepository = $trickRepository;
    }

    public function load(ObjectManager $manager)
    {
        $users = $this->users->findAll();
        $tricks = $this->trickRepository->findAll();
        foreach ($tricks as $iTrick) {
            $nComment = 0;
            foreach ($users as $iUser) {
                $comment = new Comment();
                $comment->setUser($iUser);
                $comment->setTrick($iTrick);
                $comment->setContent('Commentaire numero : ' . ($nComment += 1));
                $manager->persist($comment);
            }
            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [
            TrickFixtures::class,
        ];
    }
}
