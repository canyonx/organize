<?php

namespace App\EventListener;

use Doctrine\ORM\Events;
use App\Entity\TripRequest;
use App\Repository\MessageRepository;
use App\Service\NotificationService;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Notifier\Notification\Notification;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: TripRequest::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: TripRequest::class)]
class TripRequestListener extends AbstractController
{
    public function __construct(
        private NotificationService $notificationService,
        private TranslatorInterface $translator,
        private MessageRepository $messageRepository,
    ) {
    }

    // Notification on new TR
    public function postPersist(TripRequest $tripRequest, PostPersistEventArgs $event): void
    {
        $trip = $tripRequest->getTrip();
        $message = $this->messageRepository->findOneBy(['tripRequest' => $tripRequest, 'member' => $this->getUser()]);

        if ($tripRequest->getStatus() == TripRequest::OWNER) {
            return;
        }

        if ($trip->getMember()->getSetting() && $trip->getMember()->getSetting()->isIsNewTripRequest()) {
            $this->notificationService->send(
                $trip->getMember(),
                [
                    'title' => $tripRequest->getMember() . ' demande à rejoindre ' . $trip->getTitle(),
                    'message' => $message->getContent()
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
                    'title' => 'Demande de participation ' . $this->translator->trans(ucfirst(strtolower($tripRequest->getStatus()))),
                    'message' => 'Votre demande de participation à ' . $tripRequest->getTrip() . ' est ' . $this->translator->trans(ucfirst(strtolower($tripRequest->getStatus())))
                ]
            );
        }
    }
}
