<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;

class NotificationService
{
    public function __construct(
        #[Autowire('%app_admin_mail%')] private string $adminMail,
        #[Autowire('%app_site_name%')] private string $siteName,
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
        $email = (new NotificationEmail())
            ->from(new Address($this->adminMail, $this->siteName))
            ->to(new Address($to->getEmail(), $to->getPseudo()))
            ->locale('fr')
            ->importance('')
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
//      $to, // to User
//      // vars
//     [
//         'title' => 'Nouveau message de ' . $from,
//         'message' => $message->getContent()
//     ]
// );
