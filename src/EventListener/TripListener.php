<?php

namespace App\EventListener;

use App\Entity\Trip;
use Doctrine\ORM\Events;
use App\Entity\TripRequest;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\NotificationService;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PreRemoveEventArgs;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Trip::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Trip::class)]
#[AsEntityListener(event: Events::preRemove, method: 'preRemove', entity: Trip::class)]
class TripListener extends AbstractController
{
    public function __construct(
        private NotificationService $notificationService,
        private EntityManagerInterface $em
    ) {
    }

    // Create join request for trip owner
    public function postPersist(Trip $trip, PostPersistEventArgs $event): void
    {
        // Create a new JoinRequest for user who create the trip
        $tr = new TripRequest();
        $tr->setStatus(TripRequest::OWNER)
            ->setTrip($trip)
            ->setMember($trip->getMember());
        $this->em->persist($tr);
        $this->em->flush();

        $friendWith = $trip->getMember()->getFriendsWithMe();
        foreach ($friendWith as $to) {
            if ($to->getMember()->getSetting() && $to->getMember()->getSetting()->isIsFriendNewTrip()) {
                $this->notificationService->send(
                    $to->getMember(),
                    [
                        'title' => $trip->getMember() . ' a crée une nouvelle sortie',
                        'message' => $trip->getTitle()
                    ]
                );
            }
        }
    }

    // Send email when trip is updated
    public function postUpdate(Trip $trip, PostUpdateEventArgs $event): void
    {
        foreach ($trip->getTripRequests() as $tr) {
            if ($tr->getStatus() == TripRequest::ACCEPTED || $tr->getStatus() == TripRequest::PENDING) {
                $this->notificationService->send(
                    $tr->getMember(),
                    [
                        'title' => $trip->getMember() . ' a modifié la sortie ' . $trip->getTitle(),
                        'message' => $trip->getMember() . ' a modifié une sortie à laquelle vous avez demander à participer. ' . "\r\n" . 'Veuillez vérifier votre planning pour que cette sortie corresponde toujours avec vos disponibilités !'
                    ]
                );
            }
        }
    }

    // Send email when trip is updated
    public function preRemove(Trip $trip, PreRemoveEventArgs $event): void
    {
        foreach ($trip->getTripRequests() as $tr) {
            if ($tr->getStatus() == TripRequest::ACCEPTED || $tr->getStatus() == TripRequest::PENDING) {
                $this->notificationService->send(
                    $tr->getMember(),
                    [
                        'title' => $trip->getMember() . ' a supprimé la sortie ' . $trip->getTitle(),
                        'message' => $trip->getMember() . ' a supprimé une sortie à laquelle vous avez demander à participer. ' . "\r\n" . 'Veuillez vérifier votre planning pour que cette sortie corresponde toujours avec vos disponibilités !'
                    ]
                );
            }
        }
    }
}
