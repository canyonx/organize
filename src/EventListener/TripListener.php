<?php

namespace App\EventListener;

use App\Entity\Join;
use App\Entity\Trip;
use App\Entity\TripRequest;
use Doctrine\ORM\Events;
use App\Service\MailerService;
use App\Repository\JoinRepository;
use App\Repository\TripRepository;
use App\Repository\TripRequestRepository;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Trip::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Trip::class)]
class TripListener extends AbstractController
{
    public function __construct(
        private MailerService $mailerService,
        private TripRequestRepository $tripRequestRepository,
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

        // TODO : send notification to my friends
    }

    // Send email when trip is updated
    public function postUpdate(Trip $trip, PostUpdateEventArgs $event): void
    {
        foreach ($trip->getTripRequests() as $tr) {
            if ($tr->getStatus() == TripRequest::ACCEPTED || $tr->getStatus() == TripRequest::PENDING) {
                // $this->mailerService->updatedTripNotification($tr->getMember()->getEmail(), $trip);
            }
        }
    }
}
