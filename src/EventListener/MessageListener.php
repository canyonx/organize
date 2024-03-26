<?php

namespace App\EventListener;

use App\Entity\Message;
use Doctrine\ORM\Events;
use App\Service\NotificationService;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Message::class)]
class MessageListener extends AbstractController
{
    public function __construct(
        private NotificationService $notificationService,
    ) {
    }

    // Notification on new Message
    public function postPersist(Message $message, PostPersistEventArgs $event): void
    {
        $user = $this->getUser();
        $tripRequest = $message->getTripRequest();

        // If first message send on TR notification
        if ($tripRequest->getMessages()->count() <= 1) {
            return;
        }

        $from = ($user == $tripRequest->getMember()) ? $tripRequest->getMember() : $tripRequest->getTrip()->getMember();
        $to = ($user == $tripRequest->getMember()) ? $tripRequest->getTrip()->getMember() : $tripRequest->getMember();

        // If $to user setting isIsNewMessage
        if ($to->getSetting() && $to->getSetting()->isIsNewMessage()) {
            $this->notificationService->send(
                $to,
                [
                    'title' => 'Nouveau message de ' . $from,
                    'message' => $message->getContent()
                ]
            );
        }
    }
}
