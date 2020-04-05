<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AskResetPasswordType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use App\Service\Mail;
use App\Service\PasswordHandler;
use App\Service\TokenHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordController extends AbstractController
{
    /**
     * @var Mail
     */
    private $mail;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var TokenHandler
     */
    private $tokenHandler;

    public function __construct(Mail $mail, EntityManagerInterface $entityManager, UserRepository $userRepository, TokenHandler $tokenHandler)
    {
        $this->mail = $mail;
        $this->em = $entityManager;
        $this->userRepository = $userRepository;
        $this->tokenHandler = $tokenHandler;
    }

    /**
     * @Route("/ask_reset_password", name="ask_password_reset")
     * @param Request $request
     * @param TokenHandler $tokenHandler
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function index(Request $request)
    {

        $userEntity = new User();
        $form = $this->createForm(AskResetPasswordType::class, $userEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $email = $request->request->get('ask_reset_password')['email'];
            $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($user) {
                $token = $this->tokenHandler->handle($user);
                $this->mail->sendResetEmail($user, $token);
                return $this->redirectToRoute('home');
            }
            if (!$user) {
                $this->addFlash('error', 'User not found for ' . $email);
            }
        }

        return $this->render('password/index.html.twig', [
            'askResetPasswordForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/reset_password/{token}", name="password_reset")
     * @param Request $request
     * @param $token
     * @param PasswordHandler $passwordHandler
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws TransportExceptionInterface
     */
    public function reset(Request $request, $token, PasswordHandler $passwordHandler)
    {
        if ($this->tokenHandler->isTokenValid($token)) {
            /* @var User $user */
            $user = $this->userRepository->findOneBy(['token' => $token]);
            $form = $this->createForm(ResetPasswordType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                try {
                    $passwordHandler->handle($form, $user);
                    $this->mail->sendResetedEmail($user);
                    $this->tokenHandler->deleteToken($user);
                    $this->addFlash('success', 'Mot de passe changé');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Une erreur est survenue');
                }
            }

            return $this->render('password/reset.html.twig', [
                'resetPasswordForm' => $form->createView(),
            ]);

        }

        $this->addFlash('error', '404 - Page introuvable');
        return $this->redirectToRoute('home');
    }

}
