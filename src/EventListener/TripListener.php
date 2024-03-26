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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Trip::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Trip::class)]
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
        // foreach ($trip->getTripRequests() as $tr) {
        //     if ($tr->getStatus() == TripRequest::ACCEPTED || $tr->getStatus() == TripRequest::PENDING) {
        //         // $this->mailerService->updatedTripNotification($tr->getMember()->getEmail(), $trip);
        //     }
        // }
    }
}
