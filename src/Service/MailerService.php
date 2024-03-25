<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;

class MailerService
{
    // Service dans un service, Injection de dépendance
    private $mailer;
    private $adminMail;

    public function __construct(
        string $adminMail,
        MailerInterface $mailer,
    ) {
        $this->mailer = $mailer;
        $this->adminMail = $adminMail;
    }

    /**
     * Envoyer un email
     *
     * @param string $to destination of the mail
     * @param string $subject of the mail
     * @param string $template link to template
     * @param array $context array variables for the template
     * @return void
     */
    public function send(string $to, string $subject, string $template, array $context)
    {
        $email = (new TemplatedEmail())
            ->from($this->adminMail)
            ->to($to)
            ->subject($subject)
            ->htmlTemplate("email/$template")
            ->context($context);

        $this->mailer->send($email);
    }
}


/**
 * Envoyer un email simple
 */
// $mailerService->send(
//      $to->getEmail(), // to
//     'Nouveau message', // subject
//     'notification.html.twig', // template
//      // vars
//     [
//         'title' => 'Nouveau message de ' . $from,
//         'message' => $message->getContent()
//     ]
// );
