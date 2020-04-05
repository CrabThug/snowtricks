<?php


namespace App\Service;


use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class CommentHandler
{
    private $entityManager;

    /**
     * CommentHandler constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @param Comment $comment
     * @param Trick $trick
     * @param User $user
     */
    public function handle(Comment $comment, Trick $trick, User $user): void
    {
        $comment->setTrick($trick);
        $comment->setUser($user);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();
    }
}
