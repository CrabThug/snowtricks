<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Twig\Environment;

class Mail
{

    /**
     * @var MailerInterface
     */
    private $mailer;
    /**
     * @var FlashBagInterface
     */
    private $flashBag;
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var $from
     */
    private $from;

    /**
     * Mail constructor.
     * @param MailerInterface $mailer
     * @param FlashBagInterface $flashBag
     * @param Environment $twig
     * @param $from
     */
    public function __construct(MailerInterface $mailer, FlashBagInterface $flashBag, Environment $twig, $from)
    {
        $this->mailer = $mailer;
        $this->flashBag = $flashBag;
        $this->twig = $twig;
        $this->from = $from;
    }

    /**
     * @param $to
     * @param $subject
     * @param $template
     * @param $context
     * @return RedirectResponse
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function sendEmail($to, $subject, $template, $context)
    {
        $email = (new TemplatedEmail())
            ->from($this->from)
            ->to(new Address($to))
            ->subject($subject)

            // path of the Twig template to render
            ->htmlTemplate($template)

            // pass variables (name => value) to the template
            ->context($context);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            return $this->flashBag->add('error', 'le mail n\'a pas pu vous etre envoyé');
        }
        return $this->flashBag->add('success', 'un email vous a ete envoyé');
    }

    /**
     * @param User $user
     * @param $token
     * @return RedirectResponse
     * @throws TransportExceptionInterface
     */
    public function sendResetEmail(User $user, $token)
    {
        $to = $user->getEmail();
        $subject = "Réinitialisation de mot de passe";
        $template = 'mail/ask_reset_password.html.twig';
        $context = ['token' => $token, 'user' => $user];

        return $this->sendEmail($to, $subject, $template, $context);
    }

    /**
     * @param User $user
     * @return RedirectResponse
     * @throws TransportExceptionInterface
     */
    public function sendResetedEmail(User $user)
    {
        $to = $user->getEmail();
        $subject = "Votre mot de passe a ete réinitialisé";
        $template = 'mail/reseted_password.html.twig';
        $context = ['user' => $user];

        return $this->sendEmail($to, $subject, $template, $context);
    }

    /**
     * @param User $user
     * @return RedirectResponse
     * @throws TransportExceptionInterface
     */
    public function sendNewAccountEmail(User $user)
    {
        $username = $user->getName();
        $to = $user->getEmail();
        $subject = "Bienvenue $username !" ;
        $template = 'mail/new.html.twig';
        $context = ['user' => $user];

        return $this->sendEmail($to, $subject, $template, $context);
    }
}
