<?php

namespace App\Service;


use App\Entity\User;
use App\Repository\UserRepository;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

class TokenHandler
{

    public function __construct(ContainerInterface $container, EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->container = $container;
        $this->em = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function handle(User $user)
    {
        $tokenProvider = $this->container->get('security.csrf.token_manager');
        $token = $tokenProvider->getToken('reset-password')->getValue();
        $user->setToken($token);
        $user->setTokenDate(new DateTime());
        $this->em->persist($user);
        $this->em->flush();
        return $token;
    }

    public function isTokenValid($token): bool
    {
        $user = $this->userRepository->findOneBy(['token' => $token]);

        if ($user) {
            /* @var DateTime $tokenExpiration */
            $tokenExpiration = $user->getTokenDate();
            $tokenExpiration->add(new DateInterval('PT24H'));
            $now = new DateTime();

            return $now < $tokenExpiration;
        }

        return FALSE;
    }

    public function deleteToken(User $user)
    {
        $user->setToken('');
        $this->em->persist($user);
        $this->em->flush();
    }
}
