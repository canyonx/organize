<?php

namespace App\EventListener;

use Doctrine\ORM\Events;
use App\Entity\TripRequest;
use App\Service\NotificationService;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: TripRequest::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: TripRequest::class)]
class TripRequestListener extends AbstractController
{
    public function __construct(
        private NotificationService $notificationService,
        private TranslatorInterface $translator
    ) {
    }

    // Notification on new TR
    public function postPersist(TripRequest $tripRequest, PostPersistEventArgs $event): void
    {
        $trip = $tripRequest->getTrip();
        $message = $tripRequest->getMessages();
        $message = $message->key(0);

        if ($tripRequest->getStatus() == TripRequest::OWNER) {
            return;
        }

        if ($trip->getMember()->getSetting() && $trip->getMember()->getSetting()->isIsNewTripRequest()) {
            $this->notificationService->send(
                $trip->getMember(),
                [
                    'title' => $tripRequest->getMember() . ' demande à rejoindre ' . $trip->getTitle(),
                    'message' => $message
                ]
            );
        }
    }

    // Notification on status change TR 
    public function postUpdate(TripRequest $tripRequest, PostUpdateEventArgs $event): void
    {
        $user = $this->getUser();
        $to = ($user == $tripRequest->getMember()) ? $tripRequest->getTrip()->getMember() : $tripRequest->getMember();

        // If $to user setting isIsNewMessage
        if ($to->getSetting() && $to->getSetting()->isIsTripRequestStatusChange()) {
            $this->notificationService->send(
                $to,
                [
                    'title' => 'Changement de status pour ' . $tripRequest->getTrip()->getTitle(),
                    'message' => 'Votre demande à maintenant le status ' . $this->translator->trans(ucfirst(strtolower($tripRequest->getStatus())))
                ]
            );
        }
    }
}
