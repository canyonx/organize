<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\BodyRendererInterface;

class NotificationService
{
    public function __construct(
        private string $adminMail, // Declaration in service.yaml
        private MailerInterface $mailer,
        private BodyRendererInterface $bodyRenderer
    ) {
    }

    /**
     * Send notification email
     *
     * @param User $to destination of the mail
     * @param array $context array variables for the template
     * @return void
     */
    public function send(User $to, array $context)
    {
        $email = (new TemplatedEmail())
            ->from($this->adminMail)
            ->to(new Address($to->getEmail(), $to->getPseudo()))
            ->subject('Notification')
            ->htmlTemplate("email/notification.html.twig")
            ->context($context);

        $this->bodyRenderer->render($email);

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
