<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class NotificationService
{
    public function __construct(
        private string $adminMail, // Declaration in service.yaml
        private string $siteName, // Declaration in service.yaml
        private MailerInterface $mailer
    ) {
    }

    /**
     * Send notification email
     *
     * @param User $to destination of the mail
     * @param array $context array variables for the template
     * @return void
     */
    public function send(User $to, array $context): void
    {
        $email = (new TemplatedEmail())
            ->from(new Address($this->adminMail, $this->siteName))
            ->to(new Address($to->getEmail(), $to->getPseudo()))
            ->locale('fr_FR')
            ->subject($context['title'])
            ->htmlTemplate("email/notification.html.twig")
            ->context($context);

        $this->mailer->send($email);
    }
}


/**
 * Usecase
 */
// $notificationService->send(
//      $to->getEmail(), // to
//      // vars
//     [
//         'title' => 'Nouveau message de ' . $from,
//         'message' => $message->getContent()
//     ]
// );
